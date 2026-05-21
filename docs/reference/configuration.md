# Configuration Reference

## Composer Autoload

`composer.json` registers:

```json
{
  "autoload": {
    "files": [
      "bin/autoload.php"
    ],
    "psr-4": {
      "pms\\": "src/pms/"
    }
  }
}
```

## Composer Extra

The package declares a config seed:

```json
{
  "extra": {
    "pms": {
      "config": {
        "command": "resource/config.php"
      }
    }
  }
}
```

`resource/config.php` currently returns an empty array.

## Runtime Config Key

`TerminalCommandHook::run()` reads:

```php
config('command', [])
```

Expected shape:

```php
return [
    'command-name' => SomeCommand::class,
];
```

This runtime mapping is merged with commands already mounted through `TerminalCommandHook::mount()`.

## Constants

Defined in `bin/const.php`:

```php
const COMMAND_OPTION_TYPE = 'option';
const COMMAND_ARGUMENT_TYPE = 'argument';
const TERMINAL_COLOR_RED = 31;
const TERMINAL_COLOR_GREEN = 32;
const TERMINAL_COLOR_YELLOW = 33;
const TERMINAL_COLOR_BLUE = 34;
const TERMINAL_COLOR_MAGENTA = 35;
const TERMINAL_COLOR_CYAN = 36;
```

Lifecycle constants used by this package are defined by `superpms/basic`:

- `LIFECYCLE_BOOT`
- `LIFECYCLE_BOOTED`
- `LIFECYCLE_SANDBOX_CREATED`
- `LIFECYCLE_SANDBOX_BOOT`
- `LIFECYCLE_SANDBOX_BOOTED`
- `LIFECYCLE_SANDBOX_RAN`
- `LIFECYCLE_SANDBOX_DESTRUCT`

## PHP Version

The package requires PHP `>=8.2`.
