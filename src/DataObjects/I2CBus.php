<?php

namespace Microscrap\Bindings\I2C\DataObjects;

final readonly class I2CBus
{
    public function __construct(
        public int $fd,
        public string $path,
        public int $addr,
    ) {
    }
}
