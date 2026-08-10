---
type: Convention
title: Enums for kernel constants
description: "I2COpCode, I2CMsgFlag, I2CFuncFlag, SMBusReadWrite, SMBusSize are int-backed with FULLY UPPERCASE cases."
resource: src/Enums/
tags: [convention, enums, i2c, linux, smbus]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: Enums tables and FULLY UPPERCASE rule
  - id: opcode
    resource: src/Enums/I2COpCode.php
    title: I2COpCode enum
  - id: msg-flag
    resource: src/Enums/I2CMsgFlag.php
    title: I2CMsgFlag enum
  - id: func-flag
    resource: src/Enums/I2CFuncFlag.php
    title: I2CFuncFlag enum
  - id: smbus-rw
    resource: src/Enums/SMBusReadWrite.php
    title: SMBusReadWrite enum
  - id: smbus-size
    resource: src/Enums/SMBusSize.php
    title: SMBusSize enum
  - id: agents
    resource: AGENTS.md
    title: Enum case naming rule
---

# Why enums live here

Linux i2c-dev ioctl request numbers, `i2c_msg.flags` bits, `I2C_FUNCS` capability bits, and SMBus size/direction tokens are kernel `#define`s. This package ships int-backed enums so callers avoid raw magic numbers for the usual cases.[^readme]

# Rules

- Use **int-backed** enums under `Microscrap\Bindings\I2C\Enums\`.[^opcode][^msg-flag]
- Case names are **FULLY UPPERCASE** (e.g. `I2COpCode::I2C_SLAVE`, `I2CMsgFlag::M_RD`).[^agents][^readme]
- No class-level constants in `src/` — prefer enums.[^agents]
- Pass `->value` (or a raw `int`) into helpers / `ioctl`; helpers take integers (or the typed `SMBusReadWrite` where the signature requires it).[^readme]

# Enum inventory (0.7.0)

| Enum | Purpose | Cases (summary) |
|------|---------|-----------------|
| `I2COpCode` | i2c-dev ioctl request numbers | `I2C_RETRIES` … `I2C_PEC`, `I2C_SMBUS`[^opcode] |
| `I2CMsgFlag` | `i2c_msg.flags` bitmask | `M_RD`, `M_TEN`, `M_STOP`, `M_NOSTART`, …[^msg-flag] |
| `I2CFuncFlag` | `I2C_FUNCS` capability bits | `I2C_FUNC`, `SMBUS_*`, composite `SMBUS_EMUL`, …[^func-flag] |
| `SMBusReadWrite` | Direction for `i2c_smbus_write_quick` | `WRITE` (= 0), `READ` (= 1)[^smbus-rw] |
| `SMBusSize` | SMBus transaction sizes (kernel `I2C_SMBUS` sizes) | `QUICK` … `I2C_BLOCK_DATA`[^smbus-size] |

`I2COpCode::I2C_SMBUS` and `SMBusSize` document the kernel SMBus ioctl vocabulary; the shipped `i2c_smbus_*` helpers do **not** issue that ioctl — see [SMBus helpers ≠ I2C_SMBUS ioctl](../traps/smbus-helpers-not-ioctl.md).

# Related

* [1:1 extension wrap](one-to-one-extension-wrap.md)
* [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md)

[^readme]: Enums tables and FULLY UPPERCASE rule
[^opcode]: I2COpCode enum
[^msg-flag]: I2CMsgFlag enum
[^func-flag]: I2CFuncFlag enum
[^smbus-rw]: SMBusReadWrite enum
[^smbus-size]: SMBusSize enum
[^agents]: Enum case naming rule
