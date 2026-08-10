# AGENTS.md — microscrap/i2c

**Always read `.okf/index.md` first** before changing this package. Open only the concepts needed for the task; prefer `status: stable` when present. When you learn a durable package fact, update `.okf/` and append `.okf/log.md`.

## Role

Bindings-only Composer package over **ext-posi** + **microscrap/posix**. Global `i2c_*` helpers + `Bus` facade + enums + `I2CBus`. No ServiceProvider, no Chassis/Core coupling.

## Rules

* Helpers call `Microscrap\Bindings\I2C\Bus` only — do not invent parallel APIs.
* `Bus` uses `posix_open` / `posix_read` / `posix_write` / `posix_close` / `ioctl` / `posi_mem_*` only — no Fabricate remaps.
* Keep helper ↔ `Bus` coverage aligned with README tables; document drift in README / ecosystem docs.
* Enums in `src/Enums/*` are int-backed with **FULLY UPPERCASE** cases.
* Prefer `is_null($var)` over `$var === null`.
* No class-level constants; no ServiceProviders in this package.
* SMBus helpers are emulated over posix read/write — they do **not** issue `I2C_SMBUS` ioctl.

## Quick OKF map

| Need | Concept |
|------|---------|
| Identity / scope | `.okf/orientation/package.md` |
| Docs site | `.okf/orientation/ecosystem-docs.md` |
| Call stack | `.okf/architecture/helpers-bus-posix.md` |
| Enums | `.okf/conventions/enums-kernel-constants.md` |
| Peer stack | `.okf/orientation/pairing-protocol-peers.md` |
| SMBus ≠ ioctl | `.okf/traps/smbus-helpers-not-ioctl.md` |
| Stale `$addr` | `.okf/traps/i2cbus-addr-stale.md` |
| `/dev` perms | `.okf/traps/need-i2c-group-permissions.md` |
| Helper clash | `.okf/traps/function-exists-load-order.md` |
