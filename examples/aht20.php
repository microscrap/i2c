<?php

/**
 * aht20.php - read humidity/temperature from an AHT20 over Linux i2c-dev.
 *
 * Usage:
 *   php examples/aht20.php [/dev/i2c-N] [addr] [interval_seconds] [samples]
 *
 * Examples:
 *   php examples/aht20.php /dev/i2c-1 0x38 2 0   # endless loop every 2s
 *   php examples/aht20.php /dev/i2c-1 0x38 1 10  # 10 samples at 1s interval
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

use Microscrap\Bindings\I2C\DataObjects\I2CBus;
use Microscrap\Bindings\I2C\Enums\SMBusReadWrite;

function parse_int_arg(string $value): int
{
    $value = trim($value);
    if (str_starts_with(strtolower($value), '0x')) {
        return (int) hexdec(substr($value, 2));
    }

    return (int) $value;
}

function write_exact(I2CBus $bus, string $payload): bool
{
    $written = i2c_write($bus, $payload);
    return $written === strlen($payload);
}

function aht20_read_measurement(I2CBus $bus): ?array
{
    // Trigger measurement: AC 33 00
    if (! write_exact($bus, pack('C*', 0xAC, 0x33, 0x00))) {
        return null;
    }

    // Typical conversion time is around 80ms.
    usleep(80_000);

    // Poll a few times in case BUSY bit is still set.
    for ($attempt = 0; $attempt < 10; $attempt++) {
        $raw = i2c_read($bus, 7);
        if (! is_string($raw) || strlen($raw) !== 7) {
            return null;
        }

        $bytes = array_values(unpack('C7', $raw));
        $status = $bytes[0];
        $busy = ($status & 0x80) !== 0;
        if ($busy) {
            usleep(10_000);
            continue;
        }

        $rawHumidity = (($bytes[1] << 12) | ($bytes[2] << 4) | ($bytes[3] >> 4)) & 0xFFFFF;
        $rawTemp = ((($bytes[3] & 0x0F) << 16) | ($bytes[4] << 8) | $bytes[5]) & 0xFFFFF;

        $humidity = ($rawHumidity / 1048576.0) * 100.0;
        $temperature = ($rawTemp / 1048576.0) * 200.0 - 50.0;

        return [
            'status' => $status,
            'humidity' => $humidity,
            'temperature' => $temperature,
        ];
    }

    return null;
}

$path = $argv[1] ?? '/dev/i2c-1';
$addr = parse_int_arg($argv[2] ?? '0x38');
$intervalSeconds = max(0.1, (float) ($argv[3] ?? '2'));
$samples = max(0, (int) ($argv[4] ?? '0')); // 0 = infinite

echo "Opening AHT20 on {$path} at address 0x" . strtoupper(dechex($addr)) . "...\n";
$bus = i2c_open($path, $addr);
if ($bus === null) {
    fwrite(STDERR, "Failed to open {$path} or set slave address.\n");
    exit(1);
}

// Optional device init/calibration sequence often used by AHT20 drivers.
if (! write_exact($bus, pack('C*', 0xBE, 0x08, 0x00))) {
    fwrite(STDERR, "Warning: init/calibration command failed.\n");
}
usleep(20_000);

// Quick capability query. Not required for readout, but useful diagnostics.
$funcs = i2c_get_funcs($bus);
echo "Opened fd={$bus->fd} path={$bus->path} addr=0x" . strtoupper(dechex($bus->addr)) . " funcs=0x" . strtoupper(dechex($funcs)) . "\n";

// Optional single-byte SMBus quick demo call (not needed by AHT20 itself).
// Kept as a no-op compatibility check.
i2c_smbus_write_quick($bus, SMBusReadWrite::WRITE);

$count = 0;
while ($samples === 0 || $count < $samples) {
    $count++;
    $reading = aht20_read_measurement($bus);

    if ($reading === null) {
        fwrite(STDERR, "[{$count}] Read failed.\n");
    } else {
        $statusHex = strtoupper(str_pad(dechex($reading['status']), 2, '0', STR_PAD_LEFT));
        printf(
            "[%d] Temp: %.2f C | RH: %.2f %% | STATUS: 0x%s\n",
            $count,
            $reading['temperature'],
            $reading['humidity'],
            $statusHex
        );
    }

    usleep((int) ($intervalSeconds * 1_000_000));
}

i2c_close($bus);
echo "Bus closed.\n";
