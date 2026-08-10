---
type: Orientation
title: Pair with protocol peers
description: "posix below; uart / gpio / spi siblings; ftdi/mpsse beside for USB; gpio-framework above."
resource: .
tags: [orientation, gpio, uart, i2c, spi, ftdi, posix, composition]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Bindings role and posix / ext-posi requirements
  - id: agents
    resource: AGENTS.md
    title: Bindings-only role; no Chassis
  - id: package-orient
    resource: package.md
    title: Package orientation suggest peers
  - id: bus
    resource: src/Bus.php
    title: Bus uses posix_open / ioctl / posi_mem_*
---

# Composition boundary

`microscrap/i2c` is **bindings only** — open `/dev/i2c-N`, bind a slave address, raw read/write, adapter ioctls, emulated SMBus helpers, and `I2C_RDWR` combined transactions. It does not register Chassis providers or orchestrate multi-bus GPIO apps.[^readme][^agents]

| Concern | Package |
|---------|---------|
| POSIX FD / syscall helpers | `microscrap/posix` `^0.7` (**below** — this package depends on it)[^readme][^bus] |
| Native FFI / memory | `ext-posi` `^0.7` (**below**)[^readme] |
| I²C / SMBus device API | `microscrap/i2c` (this package) |
| UART | `microscrap/uart` `^0.7` (sibling) |
| GPIO | `microscrap/gpio` `^0.7` (sibling) |
| SPI | `microscrap/spi` `^0.7` (sibling) |
| USB MPSSE / FTDI | `microscrap/ftdi` / `microscrap/mpsse` (sit **beside** — USB path, not an i2c child) |
| Higher GPIO orchestration | `scrapyard-io/gpio-framework` `^0.7` (**above** the microscrap protocol packages) |

# Typical flow

1. Depend on this package (pulls **microscrap/posix** + requires **ext-posi** `^0.7.0`).
2. Ensure `/dev/i2c-N` exists (`modprobe i2c-dev`) and the process can open it (see [Need i2c group /dev permissions](../traps/need-i2c-group-permissions.md)).
3. Call `i2c_open` / helpers (or `Bus::*`) — do not invent framework providers inside this package.[^agents]
4. Application / `gpio-framework` composes this peer with uart / gpio / spi as needed.

# Caveats

- SMBus convenience helpers are **not** the kernel `I2C_SMBUS` ioctl path — see [SMBus helpers ≠ I2C_SMBUS ioctl](../traps/smbus-helpers-not-ioctl.md).
- `I2CBus::$addr` is snapshot-at-open — see [`I2CBus::$addr` stale after set_slave](../traps/i2cbus-addr-stale.md).

# Related

* [Package (0.7)](package.md)
* [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md)

[^readme]: Bindings role and posix / ext-posi requirements
[^agents]: Bindings-only role; no Chassis
[^bus]: Bus uses posix_open / ioctl / posi_mem_*
