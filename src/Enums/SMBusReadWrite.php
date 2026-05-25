<?php

namespace Microscrap\Bindings\I2C\Enums;

enum SMBusReadWrite: int
{
    case WRITE = 0;
    case READ = 1;
}
