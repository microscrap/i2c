<?php

namespace Microscrap\Bindings\I2C\Enums;

enum SMBusSize: int
{
    case QUICK = 0;
    case BYTE = 1;
    case BYTE_DATA = 2;
    case WORD_DATA = 3;
    case PROC_CALL = 4;
    case BLOCK_DATA = 5;
    case I2C_BLOCK_BROKEN = 6;
    case BLOCK_PROC_CALL = 7;
    case I2C_BLOCK_DATA = 8;
}
