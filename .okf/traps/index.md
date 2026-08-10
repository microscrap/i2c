# Traps

* [SMBus helpers ≠ I2C_SMBUS ioctl](smbus-helpers-not-ioctl.md) - Emulated over posix read/write; PEC not automatic.
* [`I2CBus::$addr` stale after set_slave](i2cbus-addr-stale.md) - Readonly DTO does not update after slave-addr ioctls.
* [Need i2c group /dev permissions](need-i2c-group-permissions.md) - `/dev/i2c-N` access requires group or root.
* [`function_exists` load order](function-exists-load-order.md) - First autoload definition of `i2c_*` wins.
