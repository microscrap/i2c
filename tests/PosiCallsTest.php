<?php

declare(strict_types=1);

use Microscrap\Bindings\I2C\DataObjects\I2CBus;
use Microscrap\Bindings\I2C\Enums\I2CMsgFlag;

it('refuses a device that does not take I2C_SLAVE', function () {
    expect(i2c_open('/dev/null', 0x3C))->toBeNull()
        ->and(i2c_open('/dev/no-such-i2c', 0x3C))->toBeNull();
});

it('reads and writes raw bytes through the bus fd', function () {
    [$read, $write] = fifoPair();
    $writer = new I2CBus($write, '/dev/i2c-1', 0x3C);
    $reader = new I2CBus($read, '/dev/i2c-1', 0x3C);

    expect(i2c_write($writer, "\x00\xAE"))->toBe(2)
        ->and(i2c_read($reader, 8))->toBe("\x00\xAE");
});

it('returns an empty string for a combined transaction with no messages', function () {
    expect(i2c_rdwr(new I2CBus(-1, '/dev/i2c-1', 0x3C), []))->toBe('');
});

it('reports a combined transaction the fd refuses as false with errno', function () {
    $fd = track(posix_open('/dev/null', O_RDWR));
    $bus = new I2CBus($fd, '/dev/null', 0x3C);

    expect(i2c_rdwr($bus, [
        ['flags' => 0, 'data' => "\x0F"],
        ['flags' => I2CMsgFlag::M_RD->value, 'len' => 1],
    ]))->toBeFalse()
        ->and(posi_errno())->toBe(PHP_OS_FAMILY === 'Darwin' ? ENODEV : ENOTTY);
});

it('closes the bus fd', function () {
    [$read] = fifoPair();

    expect(i2c_close(new I2CBus($read, '/dev/i2c-1', 0x3C)))->toBe(0)
        ->and(fcntl($read, F_GETFD, null, $flags))->toBe(-1);
});
