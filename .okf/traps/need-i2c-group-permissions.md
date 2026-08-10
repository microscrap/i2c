---
type: Trap
title: Need i2c group /dev permissions
description: "i2c_open fails when the process cannot open /dev/i2c-N — typically missing i2c group membership or i2c-dev."
resource: README.md
tags: [trap, linux, permissions, i2c-dev, hardware]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README requirements and i2c group note
  - id: bus
    resource: src/Bus.php
    title: i2cOpen returns null when posix_open or I2C_SLAVE fails
---

# Symptom

`i2c_open('/dev/i2c-1', …)` returns `null`. `ls /dev/i2c-*` may fail, or the device exists but open is denied.

# Cause

Linux exposes adapters as `/dev/i2c-N` via `i2c-dev`. Opening that node requires:

1. The module / device present (`modprobe i2c-dev`, board I²C enabled).[^readme]
2. Permission to open the character device — usually membership in the `i2c` group, or root.[^readme]

`Bus::i2cOpen` returns `null` when `posix_open` fails **or** the subsequent `I2C_SLAVE` ioctl fails (and closes the FD).[^bus]

# Mitigation

- Confirm devices: `ls /dev/i2c-*` and optionally `i2cdetect -y N`.[^readme]
- Add the runtime user to the `i2c` group (or adjust udev rules), then re-login / refresh groups.[^readme]
- On SBC mounts from a laptop, remember local utilities may not execute on the board — run `composer` / probe commands **on the SBC** when Angel directs.

# Related

* [Package (0.7)](../orientation/package.md)
* [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md)

[^readme]: README requirements and i2c group note
[^bus]: i2cOpen returns null when posix_open or I2C_SLAVE fails
