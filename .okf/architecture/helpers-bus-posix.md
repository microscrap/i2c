---
type: Architecture
title: "Helpers → Bus → posix/posi"
description: "Global i2c_* helpers call Bus; Bus uses posix_open/read/write/close, ioctl, and posi_mem_* for I2C_RDWR."
resource: src/Bus.php
tags: [architecture, bindings, i2c, helpers, bus, posix, posi]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: bus
    resource: src/Bus.php
    title: Bus facade with posix / ioctl / posi_mem_* usage
  - id: helpers-bus
    resource: src/Helpers/i2c-bus.php
    title: Bus lifecycle and ioctl helpers
  - id: helpers-smbus
    resource: src/Helpers/i2c-smbus.php
    title: SMBus convenience helpers
  - id: dto
    resource: src/DataObjects/I2CBus.php
    title: I2CBus data object
  - id: composer
    resource: composer.json
    title: Autoload files list
  - id: readme
    resource: README.md
    title: Helper API and facade mapping
  - id: agents
    resource: AGENTS.md
    title: Helpers call Bus only
---

# Call stack

```
app / peers / tests
    │
    ├─ i2c_open / i2c_read / i2c_rdwr / …     # global helpers (exact names)
    │       └─► Bus::*                          # Microscrap\Bindings\I2C\Bus
    │               ├─► posix_open / posix_read / posix_write / posix_close
    │               ├─► ioctl (I2C_* opcodes via I2COpCode)
    │               └─► posi_mem_alloc / write / read / free   # I2C_RDWR only
    │
    └─ Bus::i2cOpen(...)                       # static style (same path)
            └─► (same posix / ioctl / posi_mem_*)
```

Rules:[^agents][^readme][^bus]

1. Helpers call `Microscrap\Bindings\I2C\Bus` only.
2. `Bus` is the only layer that calls `posix_*`, `ioctl`, and `posi_mem_*` for this package’s I²C surface.
3. Helpers never call posix / ext-posi directly.
4. Do not invent a parallel PHP API or reimplement kernel i2c-dev ioctls outside `Bus`.

# Autoload

Composer `autoload.files` registers:[^composer]

- `src/Helpers/i2c-bus.php` — lifecycle, raw I/O, adapter ioctls, `i2c_rdwr`
- `src/Helpers/i2c-smbus.php` — SMBus convenience helpers

Each function is wrapped in `if (! function_exists(...))` so a prior definition wins.[^helpers-bus][^helpers-smbus]

# Helper groups (0.7 surface)

| Group | Examples | Bus / lower path |
|-------|----------|------------------|
| Lifecycle / I/O | `i2c_open`, `i2c_close`, `i2c_read`, `i2c_write` | `posix_open` (`O_RDWR`) + `I2C_SLAVE`; then `posix_read` / `posix_write` / `posix_close`[^bus] |
| Adapter config | `i2c_set_slave_addr`, `i2c_set_ten_bit`, `i2c_set_pec`, `i2c_set_retries`, `i2c_set_timeout`, `i2c_get_funcs` | `ioctl` with `I2COpCode`[^bus][^readme] |
| Combined xfer | `i2c_rdwr` | Pack `i2c_msg` / `i2c_rdwr_ioctl_data` via `posi_mem_*` + `I2C_RDWR` ioctl[^bus] |
| SMBus convenience | `i2c_smbus_*` | Emulated with `posix_read` / `posix_write` — **not** `I2C_SMBUS` ioctl[^readme][^bus] |

# Handle object

`I2CBus` is a `final readonly` data object with `fd`, `path`, and `addr`.[^dto] Helpers pass it by value into `Bus`. After `i2c_set_slave_addr*`, see [`I2CBus::$addr` stale after set_slave](../traps/i2cbus-addr-stale.md).

# Errors / style

- C-style return codes (`null` / `-1` / `false` on failure) — no exceptions from helpers / `Bus` in this package’s wrap.[^readme]
- Prefer `is_null($var)` over `$var === null` in package code.[^agents]

# Related

* [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md)
* [Enums for kernel constants](../conventions/enums-kernel-constants.md)
* [SMBus helpers ≠ I2C_SMBUS ioctl](../traps/smbus-helpers-not-ioctl.md)
* [`function_exists` load order](../traps/function-exists-load-order.md)

[^bus]: Bus facade with posix / ioctl / posi_mem_* usage
[^helpers-bus]: Bus lifecycle and ioctl helpers
[^helpers-smbus]: SMBus convenience helpers
[^dto]: I2CBus data object
[^composer]: Autoload files list
[^readme]: Helper API and facade mapping
[^agents]: Helpers call Bus only
