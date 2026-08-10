---
type: Trap
title: "`function_exists` load order"
description: "Helpers skip definition when the name exists; another autoload file that defined i2c_* first wins."
resource: src/Helpers/
tags: [trap, autoload, helpers, i2c]
generated: { by: "okf-documentation-generator/cursor", at: "2026-08-10T22:04:00Z" }
status: draft
sources:
  - id: readme
    resource: README.md
    title: README function_exists guard note
  - id: helpers-bus
    resource: src/Helpers/i2c-bus.php
    title: function_exists guard pattern
  - id: helpers-smbus
    resource: src/Helpers/i2c-smbus.php
    title: SMBus helpers also guarded
---

# Symptom

An `i2c_*` call runs, but behavior does not match this package’s `Bus` facade (or a subset of helpers is missing / unexpected).

# Cause

Every helper is defined only when the name is free:[^helpers-bus]

```php
if (! function_exists('i2c_open')) {
    function i2c_open(string $path, int $addr = 0x00): ?I2CBus
    {
        return Bus::i2cOpen($path, $addr);
    }
}
```

Under the guard, **the first definition wins**. A prior Composer autoload file, polyfill, or another package that registered the same global name keeps its implementation.[^readme][^helpers-smbus]

Unlike `microscrap/posix` overlapping PHP’s built-in `posix` extension, `i2c_*` names are uncommon in stock PHP — collisions are more likely from another microscrap / project polyfill than from a core extension.

# Mitigation

- Prefer this package as the **canonical** I²C binding when both a polyfill and `microscrap/i2c` are present.
- Control Composer autoload / require order so these helpers register before overlapping definitions.
- Prefer `Microscrap\Bindings\I2C\Bus::*` static calls when you must avoid global-name collisions entirely.

# Related

* [Helpers → Bus → posix/posi](../architecture/helpers-bus-posix.md)
* [1:1 extension wrap](../conventions/one-to-one-extension-wrap.md)

[^readme]: README function_exists guard note
[^helpers-bus]: function_exists guard pattern
[^helpers-smbus]: SMBus helpers also guarded
