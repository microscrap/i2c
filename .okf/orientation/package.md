---
type: Orientation
title: Package (0.11)
description: "microscrap/i2c 0.11.0 — Linux i2c-dev / SMBus-style PHP helpers over Bus + ext-posi; no ServiceProvider."
resource: .
tags: [orientation, i2c, smbus, microscrap, bindings, 0.11]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package name, namespace, autoload helpers
  - id: readme
    resource: README.md
    title: Package README (0.11.x requirements and surface)
  - id: helpers-bus
    resource: src/Helpers/i2c-bus.php
    title: Bus lifecycle / ioctl helper autoload file
  - id: helpers-smbus
    resource: src/Helpers/i2c-smbus.php
    title: SMBus convenience helper autoload file
  - id: bus
    resource: src/Bus.php
    title: Bus facade class
  - id: agents
    resource: AGENTS.md
    title: Agent rules for this package
---

# What it is

Composer package `microscrap/i2c` at **0.11.0** — PHP helpers, enums, and data objects over [**php-io-extensions/posi**](https://github.com/php-io-extensions/posi) (`ext-posi`). Mirrors the Linux i2c-dev / `<linux/i2c-dev.h>` userspace surface (i2c-tools family).[^readme][^composer]

| Field | Value |
|-------|-------|
| Name | `microscrap/i2c`[^composer] |
| Version | `0.11.0`[^composer] |
| PHP | `~8.4.27\|\|^8.5.12`[^composer] |
| Namespace | `Microscrap\Bindings\I2C\` → `src/`[^composer] |
| Require | `php`, `ext-posi` `^0.11.0`[^composer] |
| Suggest | `scrapyard-io/framework` `^0.11.0`[^composer] |
| Homepage | Ecosystem docs overview (see [Ecosystem docs](ecosystem-docs.md))[^readme] |
| Discovery | **None** — no provider / Chassis registration in this package[^agents] |
| Role | Bindings layer only (global helpers + `Bus` facade + enums + `I2CBus`)[^agents][^readme] |

Autoloads `src/Helpers/i2c-bus.php` and `src/Helpers/i2c-smbus.php` (each helper guarded with `function_exists`).[^composer][^helpers-bus][^helpers-smbus]

# What it is not

- Not `php-io-extensions/posi` — this package calls its functions for I²C device nodes.[^readme]
- Not a ServiceProvider package — no Chassis / Core / Machine coupling; no Fabricate remaps.[^agents]
- Not `scrapyard-io/gpio-framework` — higher GPIO / bus orchestration sits **above** this package (see [Pair with protocol peers](pairing-protocol-peers.md)).
- Not a full kernel `I2C_SMBUS` ioctl client for the convenience helpers — those are emulated over posix read/write (see [SMBus helpers ≠ I2C_SMBUS ioctl](../traps/smbus-helpers-not-ioctl.md)).

# Public surface (summary)

| Layer | Location | Role |
|-------|----------|------|
| Helpers | `src/Helpers/i2c-bus.php`, `i2c-smbus.php` | Global `i2c_*` / `i2c_smbus_*` API |
| Facade | `src/Bus.php` | Static methods; helpers delegate here |
| Data object | `src/DataObjects/I2CBus.php` | `fd`, `path`, `addr` handle |
| Enums | `src/Enums/*` | Linux i2c-dev / SMBus integers as backed enums |
| Lower stack | `ext-posi` | `posix_*`, `ioctl`, `posi_mem_*`, `O_*` constants |

# Related

| Topic | Concept |
|-------|---------|
| Call stack | [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md) |
| Wrap rules | [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md) |
| Enums | [Enums for kernel constants](../conventions/enums-kernel-constants.md) |
| Protocol peers | [Pair with protocol peers](pairing-protocol-peers.md) |
| Docs site | [Ecosystem docs](ecosystem-docs.md) |
| Extension | `php-io-extensions/posi` 0.11 (functions and constants; no `Posi\System` / `Termios` / `Memory` classes) |

[^composer]: Package name, namespace, autoload helpers
[^readme]: Package README (0.11.x requirements and surface)
[^helpers-bus]: Bus lifecycle / ioctl helper autoload file
[^helpers-smbus]: SMBus convenience helper autoload file
[^agents]: Agent rules for this package
