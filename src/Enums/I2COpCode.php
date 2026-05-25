<?php

namespace Microscrap\Bindings\I2C\Enums;

enum I2COpCode: int
{
    case I2C_RETRIES = 0x0701;
    case I2C_TIMEOUT = 0x0702;
    case I2C_SLAVE = 0x0703;
    case I2C_TENBIT = 0x0704;
    case I2C_FUNCS = 0x0705;
    case I2C_SLAVE_FORCE = 0x0706;
    case I2C_RDWR = 0x0707;
    case I2C_PEC = 0x0708;
    case I2C_SMBUS = 0x0720;
}
