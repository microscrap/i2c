<?php

use Microscrap\Bindings\I2C\Bus;
use Microscrap\Bindings\I2C\DataObjects\I2CBus;
use Microscrap\Bindings\I2C\Enums\SMBusReadWrite;

if (! function_exists('i2c_smbus_write_quick')) {
    function i2c_smbus_write_quick(I2CBus $bus, SMBusReadWrite $rw): int
    {
        return Bus::i2cSmbusWriteQuick($bus, $rw);
    }
}

if (! function_exists('i2c_smbus_read_byte')) {
    function i2c_smbus_read_byte(I2CBus $bus): int|false
    {
        return Bus::i2cSmbusReadByte($bus);
    }
}

if (! function_exists('i2c_smbus_write_byte')) {
    function i2c_smbus_write_byte(I2CBus $bus, int $value): int
    {
        return Bus::i2cSmbusWriteByte($bus, $value);
    }
}

if (! function_exists('i2c_smbus_read_byte_data')) {
    function i2c_smbus_read_byte_data(I2CBus $bus, int $reg): int|false
    {
        return Bus::i2cSmbusReadByteData($bus, $reg);
    }
}

if (! function_exists('i2c_smbus_write_byte_data')) {
    function i2c_smbus_write_byte_data(I2CBus $bus, int $reg, int $value): int
    {
        return Bus::i2cSmbusWriteByteData($bus, $reg, $value);
    }
}

if (! function_exists('i2c_smbus_read_word_data')) {
    function i2c_smbus_read_word_data(I2CBus $bus, int $reg): int|false
    {
        return Bus::i2cSmbusReadWordData($bus, $reg);
    }
}

if (! function_exists('i2c_smbus_write_word_data')) {
    function i2c_smbus_write_word_data(I2CBus $bus, int $reg, int $value): int
    {
        return Bus::i2cSmbusWriteWordData($bus, $reg, $value);
    }
}

if (! function_exists('i2c_smbus_read_i2c_block')) {
    function i2c_smbus_read_i2c_block(I2CBus $bus, int $reg, int $len): string|false
    {
        return Bus::i2cSmbusReadI2cBlock($bus, $reg, $len);
    }
}

if (! function_exists('i2c_smbus_write_i2c_block')) {
    function i2c_smbus_write_i2c_block(I2CBus $bus, int $reg, string $data): int
    {
        return Bus::i2cSmbusWriteI2cBlock($bus, $reg, $data);
    }
}
