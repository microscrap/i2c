<?php

use Microscrap\Bindings\I2C\Bus;
use Microscrap\Bindings\I2C\DataObjects\I2CBus;

if (! function_exists('i2c_open')) {
    function i2c_open(string $path, int $addr = 0x00): ?I2CBus
    {
        return Bus::i2cOpen($path, $addr);
    }
}

if (! function_exists('i2c_close')) {
    function i2c_close(I2CBus $bus): int
    {
        return Bus::i2cClose($bus);
    }
}

if (! function_exists('i2c_read')) {
    function i2c_read(I2CBus $bus, int $len): string|false
    {
        return Bus::i2cRead($bus, $len);
    }
}

if (! function_exists('i2c_write')) {
    function i2c_write(I2CBus $bus, string $data): int
    {
        return Bus::i2cWrite($bus, $data);
    }
}

if (! function_exists('i2c_set_slave_addr')) {
    function i2c_set_slave_addr(I2CBus $bus, int $addr): int
    {
        return Bus::i2cSetSlaveAddr($bus, $addr);
    }
}

if (! function_exists('i2c_set_slave_addr_force')) {
    function i2c_set_slave_addr_force(I2CBus $bus, int $addr): int
    {
        return Bus::i2cSetSlaveAddrForce($bus, $addr);
    }
}

if (! function_exists('i2c_set_ten_bit')) {
    function i2c_set_ten_bit(I2CBus $bus, bool $enable): int
    {
        return Bus::i2cSetTenBit($bus, $enable);
    }
}

if (! function_exists('i2c_set_pec')) {
    function i2c_set_pec(I2CBus $bus, bool $enable): int
    {
        return Bus::i2cSetPec($bus, $enable);
    }
}

if (! function_exists('i2c_set_retries')) {
    function i2c_set_retries(I2CBus $bus, int $retries): int
    {
        return Bus::i2cSetRetries($bus, $retries);
    }
}

if (! function_exists('i2c_set_timeout')) {
    function i2c_set_timeout(I2CBus $bus, int $timeout): int
    {
        return Bus::i2cSetTimeout($bus, $timeout);
    }
}

if (! function_exists('i2c_get_funcs')) {
    function i2c_get_funcs(I2CBus $bus): int
    {
        return Bus::i2cGetFuncs($bus);
    }
}

if (! function_exists('i2c_rdwr')) {
    function i2c_rdwr(I2CBus $bus, array $messages): string|false
    {
        return Bus::i2cRdwr($bus, $messages);
    }
}
