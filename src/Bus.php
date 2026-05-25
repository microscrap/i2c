<?php

namespace Microscrap\Bindings\I2C;

use Microscrap\Bindings\I2C\DataObjects\I2CBus;
use Microscrap\Bindings\I2C\Enums\I2CMsgFlag;
use Microscrap\Bindings\I2C\Enums\I2COpCode;
use Microscrap\Bindings\I2C\Enums\SMBusReadWrite;
use Microscrap\Bindings\POSIX\Enums\FileControlFlag;

class Bus
{
    public static function i2cOpen(string $path, int $addr = 0x00): ?I2CBus
    {
        $fd = posix_open($path, FileControlFlag::O_RDWR->value);
        if ($fd < 0) {
            return null;
        }

        if (self::i2cIoctlInt($fd, I2COpCode::I2C_SLAVE, $addr) !== 0) {
            posix_close($fd);
            return null;
        }

        return new I2CBus($fd, $path, $addr);
    }

    public static function i2cClose(I2CBus $bus): int
    {
        return posix_close($bus->fd);
    }

    public static function i2cRead(I2CBus $bus, int $len): string|false
    {
        return posix_read($bus->fd, $len);
    }

    public static function i2cWrite(I2CBus $bus, string $data): int
    {
        return posix_write($bus->fd, $data, strlen($data));
    }

    public static function i2cSetSlaveAddr(I2CBus $bus, int $addr): int
    {
        return self::i2cIoctlInt($bus->fd, I2COpCode::I2C_SLAVE, $addr);
    }

    public static function i2cSetSlaveAddrForce(I2CBus $bus, int $addr): int
    {
        return self::i2cIoctlInt($bus->fd, I2COpCode::I2C_SLAVE_FORCE, $addr);
    }

    public static function i2cSetTenBit(I2CBus $bus, bool $enable): int
    {
        return self::i2cIoctlInt($bus->fd, I2COpCode::I2C_TENBIT, (int) $enable);
    }

    public static function i2cSetPec(I2CBus $bus, bool $enable): int
    {
        return self::i2cIoctlInt($bus->fd, I2COpCode::I2C_PEC, (int) $enable);
    }

    public static function i2cSetRetries(I2CBus $bus, int $retries): int
    {
        return self::i2cIoctlInt($bus->fd, I2COpCode::I2C_RETRIES, $retries);
    }

    public static function i2cSetTimeout(I2CBus $bus, int $timeout): int
    {
        return self::i2cIoctlInt($bus->fd, I2COpCode::I2C_TIMEOUT, $timeout);
    }

    public static function i2cGetFuncs(I2CBus $bus): int
    {
        return self::i2cIoctlGetInt($bus->fd, I2COpCode::I2C_FUNCS);
    }

    public static function i2cSmbusWriteQuick(I2CBus $bus, SMBusReadWrite $rw): int
    {
        if ($rw === SMBusReadWrite::READ) {
            $result = posix_read($bus->fd, 0);
            return $result === false ? -1 : 0;
        }

        return posix_write($bus->fd, '', 0);
    }

    public static function i2cSmbusReadByte(I2CBus $bus): int|false
    {
        $buffer = self::i2cRead($bus, 1);
        if (! is_string($buffer) || strlen($buffer) !== 1) {
            return false;
        }

        return ord($buffer);
    }

    public static function i2cSmbusWriteByte(I2CBus $bus, int $value): int
    {
        return self::i2cWrite($bus, chr($value & 0xFF));
    }

    public static function i2cSmbusReadByteData(I2CBus $bus, int $reg): int|false
    {
        if (self::i2cWrite($bus, chr($reg & 0xFF)) < 0) {
            return false;
        }

        return self::i2cSmbusReadByte($bus);
    }

    public static function i2cSmbusWriteByteData(I2CBus $bus, int $reg, int $value): int
    {
        return self::i2cWrite($bus, chr($reg & 0xFF) . chr($value & 0xFF));
    }

    public static function i2cSmbusReadWordData(I2CBus $bus, int $reg): int|false
    {
        if (self::i2cWrite($bus, chr($reg & 0xFF)) < 0) {
            return false;
        }

        $buffer = self::i2cRead($bus, 2);
        if (! is_string($buffer) || strlen($buffer) !== 2) {
            return false;
        }

        return unpack('v', $buffer)[1];
    }

    public static function i2cSmbusWriteWordData(I2CBus $bus, int $reg, int $value): int
    {
        return self::i2cWrite($bus, pack('Cv', $reg & 0xFF, $value & 0xFFFF));
    }

    public static function i2cSmbusReadI2cBlock(I2CBus $bus, int $reg, int $len): string|false
    {
        if ($len <= 0 || $len > 32) {
            return false;
        }

        if (self::i2cWrite($bus, chr($reg & 0xFF)) < 0) {
            return false;
        }

        $buffer = self::i2cRead($bus, $len);
        if (! is_string($buffer) || strlen($buffer) !== $len) {
            return false;
        }

        return $buffer;
    }

    public static function i2cSmbusWriteI2cBlock(I2CBus $bus, int $reg, string $data): int
    {
        $len = strlen($data);
        if ($len === 0 || $len > 32) {
            return -1;
        }

        return self::i2cWrite($bus, chr($reg & 0xFF) . $data);
    }

    /**
     * Issue an I2C_RDWR combined transaction — one or more messages sent in a
     * single ioctl with repeated-START between them (no STOP in between).
     *
     * Each message in $messages is an array with:
     *   'flags' => int          I2CMsgFlag bitmask (0 for write, M_RD for read)
     *   'data'  => string       Bytes to write (write messages only)
     *   'len'   => int          Number of bytes to read (read messages only)
     *
     * Returns the concatenated bytes from all read messages, '' if there are
     * none, or false if the ioctl fails or ext-posi is unavailable.
     *
     * Struct layouts (64-bit Linux):
     *   i2c_msg             16 bytes: addr(v) flags(v) len(v) pad(xx) buf(Q)
     *   i2c_rdwr_ioctl_data 16 bytes: msgs(Q) nmsgs(V) pad(xxxx)
     */
    public static function i2cRdwr(I2CBus $bus, array $messages): string|false
    {
        $n = count($messages);

        if ($n === 0) {
            return '';
        }

        if (! function_exists('posi_mem_alloc')) {
            return false;
        }

        // Allocate the contiguous i2c_msg array (16 bytes × n).
        $msgsPtr  = posi_mem_alloc($n * 16);
        $dataPtrs = [];

        foreach ($messages as $i => $msg) {
            $flags  = (int) ($msg['flags'] ?? 0);
            $isRead = ($flags & I2CMsgFlag::M_RD->value) !== 0;

            if ($isRead) {
                $len     = (int) ($msg['len'] ?? 0);
                $dataPtr = posi_mem_alloc($len > 0 ? $len : 1);
            } else {
                $data    = (string) ($msg['data'] ?? '');
                $len     = strlen($data);
                $dataPtr = posi_mem_alloc($len > 0 ? $len : 1);
                if ($len > 0) {
                    posi_mem_write($dataPtr, $data);
                }
            }

            $dataPtrs[$i] = ['ptr' => $dataPtr, 'len' => $len, 'read' => $isRead];

            // Write i2c_msg into the msgs array at offset i × 16.
            posi_mem_write($msgsPtr, pack('vvvxxQ', $bus->addr, $flags, $len, $dataPtr), $i * 16);
        }

        // Build i2c_rdwr_ioctl_data and issue the ioctl.
        $rdwrStruct = pack('QVxxxx', $msgsPtr, $n);
        $unused     = null;
        $ret        = ioctl($bus->fd, I2COpCode::I2C_RDWR->value, ['data' => $rdwrStruct], $unused);

        if ($ret < 0) {
            foreach ($dataPtrs as $entry) {
                posi_mem_free($entry['ptr']);
            }
            posi_mem_free($msgsPtr);
            return false;
        }

        $readData = '';
        foreach ($dataPtrs as $entry) {
            if ($entry['read'] && $entry['len'] > 0) {
                $readData .= posi_mem_read($entry['ptr'], $entry['len']);
            }
            posi_mem_free($entry['ptr']);
        }

        posi_mem_free($msgsPtr);
        return $readData;
    }

    private static function i2cIoctlInt(int $fd, I2COpCode $opCode, int $arg): int
    {
        $unused = 0;
        return ioctl($fd, $opCode->value, $arg, $unused);
    }

    private static function i2cIoctlGetInt(int $fd, I2COpCode $opCode): int
    {
        $buffer = str_repeat("\0", 8);
        $ret = ioctl($fd, $opCode->value, ['data' => $buffer], $buffer);
        if ($ret !== 0 || ! is_string($buffer) || strlen($buffer) < 8) {
            return -1;
        }

        return unpack('P', substr($buffer, 0, 8))[1];
    }
}
