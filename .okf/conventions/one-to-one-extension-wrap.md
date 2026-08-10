---
type: Convention
title: "1:1 extension wrap"
description: "Helpers delegate to Bus only; Bus uses posix helpers / ioctl / posi_mem_*; no parallel APIs or Fabricate wiring."
resource: src/
tags: [convention, bindings, i2c, posix, posi]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: agents
    resource: AGENTS.md
    title: Agent wrap rules
  - id: readme
    resource: README.md
    title: Package README wrap description
  - id: helpers-bus
    resource: src/Helpers/i2c-bus.php
    title: Helper → Bus delegation
  - id: bus
    resource: src/Bus.php
    title: Bus → posix / ioctl / posi_mem_*
---

# Rule

Match peer bindings packages (`microscrap/posix`, `microscrap/open-gl`, …) in spirit — thin wrap over the native stack:[^agents][^readme]

1. Global helpers use the names callers expect (`i2c_open`, `i2c_rdwr`, `i2c_smbus_read_byte_data`, …).
2. Helpers call **`Microscrap\Bindings\I2C\Bus`** only — never invent parallel APIs or call posix / ext-posi from helper files.[^helpers-bus][^agents]
3. `Bus` is the only layer that uses `posix_*`, `ioctl`, and `posi_mem_*` for this package.[^bus]
4. Keep helper ↔ `Bus` method coverage aligned with README tables; document drift in README / ecosystem docs.[^agents]
5. Kernel `#define` integers live in backed enums — see [Enums for kernel constants](enums-kernel-constants.md).
6. No ServiceProvider, Chassis/Core coupling, or Fabricate remaps in this package.[^agents]
7. Prefer `is_null($var)` over `$var === null`; no class-level constants.[^agents]

# Architecture link

Full call-stack diagram: [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md).

[^agents]: Agent wrap rules
[^readme]: Package README wrap description
[^helpers-bus]: Helper → Bus delegation
[^bus]: Bus → posix / ioctl / posi_mem_*
