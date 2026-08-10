---
type: Trap
title: "`I2CBus::$addr` stale after set_slave"
description: "Readonly I2CBus::$addr is the open-time snapshot; i2c_set_slave_addr* updates the kernel only."
resource: src/DataObjects/I2CBus.php
tags: [trap, i2c, dto, slave-addr, rdwr]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README I2CBus $addr note
  - id: dto
    resource: src/DataObjects/I2CBus.php
    title: final readonly I2CBus
  - id: bus
    resource: src/Bus.php
    title: set_slave_addr ioctls; i2cRdwr uses $bus->addr
---

# Symptom

After `i2c_set_slave_addr` or `i2c_set_slave_addr_force`, sequential `i2c_read` / `i2c_write` talk to the new slave, but `i2c_rdwr` still targets the old address — or `$bus->addr` still shows the open-time value.

# Cause

`I2CBus` is a `final readonly` data object. `$addr` is set at `i2c_open` time and never mutated.[^dto][^readme]

`i2c_set_slave_addr*` issues `I2C_SLAVE` / `I2C_SLAVE_FORCE` on the FD (kernel binding changes) but does **not** rebuild the DTO.[^bus]

`i2c_rdwr` packs each `i2c_msg` with `$bus->addr` from the object, so a stale `$addr` sends combined transactions to the wrong slave even when the kernel default slave for plain read/write was updated.[^bus][^readme]

# Mitigation

- Reopen the bus with `i2c_open($path, $newAddr)` when you need a matching `$addr` record.[^readme]
- Or build the `i2c_rdwr` message list carefully and treat `$bus->addr` as documentation of the open-time bind only.
- Prefer one `I2CBus` handle per slave address rather than rebinding mid-flight when using `i2c_rdwr`.

# Related

* [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md)
* [Package (0.7)](../orientation/package.md)

[^readme]: README I2CBus $addr note
[^dto]: final readonly I2CBus
[^bus]: set_slave_addr ioctls; i2cRdwr uses $bus->addr
