<?php

namespace Microscrap\Bindings\I2C\Enums;

enum I2CMsgFlag: int
{
    case M_RD = 0x0001;
    case M_TEN = 0x0010;
    case M_STOP = 0x8000;
    case M_NOSTART = 0x4000;
    case M_REV_DIR_ADDR = 0x2000;
    case M_IGNORE_NAK = 0x1000;
    case M_NO_RD_ACK = 0x0800;
}
