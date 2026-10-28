## 2026-10-28

* **Version**: 0.11.0 with the Venusian 0.11 ecosystem: `version` 0.11.0, `dev-main` → `0.11.x-dev`, `ext-posi` `^0.11.0`, PHP `~8.4.27||^8.5.12`, docs URLs `0.11.x`, `scrapyard-io/framework` `^0.11.0`. README, AGENTS and the bundle relabelled; CI installs `posi` `^0.11` where it has a workflow. No API change. 5 passed on Homebrew PHP 8.4 NTS and ZTS and on the Pi (8.4 ZTS).

## 2026-10-03
* **Update**: ported to 0.10.0 on `ext-posi` ^0.10.0 (C rewrite; global functions, no `Posi\*` classes). `microscrap/posix` dropped; `Bus::i2cOpen` opens with the extension's `O_RDWR` constant; `i2c_rdwr` no longer checks `function_exists('posi_mem_alloc')` and returns `false` only when the ioctl fails. Docs URLs now `0.10.x`; suggest `scrapyard-io/framework` ^0.10.0.

## 2026-09-24
* **Update**: relabeled 0.8.0 → 0.9.0 with `ext-posi` / `microscrap/posix` ^0.9.0. No code change.

## 2026-09-14
* **Update**: relabeled 0.7.0 → 0.8.0 with `ext-posi` / `ext-ftdi` 0.8.0. No code change.

# Log

## 2026-08-10

* **Creation**: Initial OKF v0.2 bundle for `microscrap/i2c` **0.7.0** (gpio microscrap stack promotion) from package sources + `okf/SPEC.md` (GoogleCloudPlatform/knowledge-catalog), format-matched to sibling `microscrap/posix`.
* **Creation**: Orientation — [Package (0.10)](/orientation/package.md), [Ecosystem docs](/orientation/ecosystem-docs.md), [Pair with protocol peers](/orientation/pairing-protocol-peers.md).
* **Creation**: Architecture — [Helpers → Bus → posix/posi](/architecture/helpers-bus-posix.md).
* **Creation**: Conventions — [1:1 extension wrap](/conventions/one-to-one-extension-wrap.md), [Enums for kernel constants](/conventions/enums-kernel-constants.md).
* **Creation**: Traps — [SMBus helpers ≠ I2C_SMBUS ioctl](/traps/smbus-helpers-not-ioctl.md), [`I2CBus::$addr` stale after set_slave](/traps/i2cbus-addr-stale.md), [Need i2c group /dev permissions](/traps/need-i2c-group-permissions.md), [`function_exists` load order](/traps/function-exists-load-order.md).
* **Creation**: Subdirectory indexes under `orientation/`, `architecture/`, `conventions/`, `traps/`; root [index.md](/index.md).
* **Creation**: Root `AGENTS.md` with quick OKF map (posix style).
* **Note**: All concepts left `status: draft` pending human verification.
* **Update**: `composer.json` reconciled to **0.7.0** (`php` `^8.4|^8.5|^8.6`, `ext-posi` / `microscrap/posix` `^0.7.0`, `suggest` gpio-framework, homepage/support, branch-alias).
