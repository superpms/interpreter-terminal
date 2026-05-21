# Installation And Boot

`superpms/interpreter-terminal` is installed as a Composer package and becomes active through Composer file autoload.

## Composer Package Facts

`composer.json` declares:

- package name: `superpms/interpreter-terminal`
- PHP requirement: `>=8.2`
- dependency: `superpms/basic`
- PSR-4 namespace: `pms\` mapped to `src/pms/`
- autoload file: `bin/autoload.php`
- config seed metadata: `extra.pms.config.command = resource/config.php`

The config seed is empty in this package. It exists so a host project can receive a `command` config file through the framework's install hook flow. The package's own built-in command registration does not depend on that file.

## Autoload Flow

Composer executes `bin/autoload.php` because it is listed under `autoload.files`.

`bin/autoload.php` does two things:

1. `require_once __DIR__ . "/const.php"`
2. `require_once __DIR__ . "/autorun.php"`

The order matters because `autorun.php` and the runtime classes use constants such as `COMMAND_ARGUMENT_TYPE`, `COMMAND_OPTION_TYPE`, and terminal color constants.

## Autorun Mounting

`bin/autorun.php` performs package integration:

```php
InterpreterHook::mount(
    'terminal',
    interpreter\terminal\Interpreter::class
);

TerminalCommandHook::mount(
    VendorInstallHookCommand::class
);
```

This means the package contributes two startup side effects:

- the `terminal` interpreter is available through `InterpreterHook`
- the `vendor:install:hook` command is available through `TerminalCommandHook`

## Boot Relationship

The package does not define the root Boot class. It relies on `superpms/basic` to resolve interpreters. When a host runtime invokes the `terminal` interpreter, `InterpreterHook::run('terminal')` resolves the class mounted by this package and calls its static `entry()`.

## Install Hook Relationship

Because `vendor:install:hook` is a normal terminal command, a host project can run it after Composer autoload generation:

```bash
php pms vendor:install:hook
```

The command scans `vendor/composer/installed.json` for package `extra.pms` metadata and copies config files, copies declared files, or creates declared directories. See [../vendor-install-hook/vendor-install-hook.md](../vendor-install-hook/vendor-install-hook.md).
