---
okf_version: "0.2"
---

# microscrap/i2c Knowledge Bundle

Package knowledge for `microscrap/i2c` (Linux i2c-dev / SMBus-style bindings over **ext-posi** + `microscrap/posix`, v0.7.0).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** This bundle lives at the **package root** only — never under `src/`.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** Document the bindings-only package (helpers + `Bus` facade + enums + `I2CBus`). Do **not** invent ServiceProviders, Chassis/Core coupling, or Fabricate remaps here.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes` so Composer dist packages do not ship this bundle.

# Orientation

* [Package (0.7)](orientation/package.md) - Composer identity, namespace, helpers over Bus / posix / ext-posi.
* [Ecosystem docs](orientation/ecosystem-docs.md) - Published 0.7.x overview and docs site entrypoint.
* [Pair with protocol peers](orientation/pairing-protocol-peers.md) - posix below; uart / gpio / spi siblings; ftdi/mpsse beside; gpio-framework above.

# Architecture

* [Helpers → Bus → posix/posi](architecture/helpers-bus-posix.md) - Call stack: global helpers → `Bus` → posix helpers / ioctl / `posi_mem_*`.

# Conventions

* [1:1 extension wrap](conventions/one-to-one-extension-wrap.md) - Helpers → `Bus` only; Bus uses posix/posi; no invented APIs.
* [Enums for kernel constants](conventions/enums-kernel-constants.md) - `I2COpCode` / `I2CMsgFlag` / `I2CFuncFlag` / SMBus enums; int-backed, FULLY UPPERCASE.

# Traps

* [SMBus helpers ≠ I2C_SMBUS ioctl](traps/smbus-helpers-not-ioctl.md) - Emulated over posix read/write; PEC not automatic.
* [`I2CBus::$addr` stale after set_slave](traps/i2cbus-addr-stale.md) - Readonly DTO does not update after slave-addr ioctls.
* [Need i2c group /dev permissions](traps/need-i2c-group-permissions.md) - `/dev/i2c-N` access requires group or root.
* [`function_exists` load order](traps/function-exists-load-order.md) - First autoload definition of `i2c_*` wins.

# Log

* [Directory update log](log.md)
