# Log

## 2026-08-10

* **Creation**: Initial OKF v0.2 bundle for `microscrap/i2c` **0.7.0** (gpio microscrap stack promotion) from package sources + `okf/SPEC.md` (GoogleCloudPlatform/knowledge-catalog), format-matched to sibling `microscrap/posix`.
* **Creation**: Orientation — [Package (0.7)](/orientation/package.md), [Ecosystem docs](/orientation/ecosystem-docs.md), [Pair with protocol peers](/orientation/pairing-protocol-peers.md).
* **Creation**: Architecture — [Helpers → Bus → posix/posi](/architecture/helpers-bus-posix.md).
* **Creation**: Conventions — [1:1 extension wrap](/conventions/one-to-one-extension-wrap.md), [Enums for kernel constants](/conventions/enums-kernel-constants.md).
* **Creation**: Traps — [SMBus helpers ≠ I2C_SMBUS ioctl](/traps/smbus-helpers-not-ioctl.md), [`I2CBus::$addr` stale after set_slave](/traps/i2cbus-addr-stale.md), [Need i2c group /dev permissions](/traps/need-i2c-group-permissions.md), [`function_exists` load order](/traps/function-exists-load-order.md).
* **Creation**: Subdirectory indexes under `orientation/`, `architecture/`, `conventions/`, `traps/`; root [index.md](/index.md).
* **Creation**: Root `AGENTS.md` with quick OKF map (posix style).
* **Note**: All concepts left `status: draft` pending human verification.
* **Update**: `composer.json` reconciled to **0.7.0** (`php` `^8.4|^8.5|^8.6`, `ext-posi` / `microscrap/posix` `^0.7.0`, `suggest` gpio-framework, homepage/support, branch-alias).
