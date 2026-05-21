# superpms/interpreter-terminal

`superpms/interpreter-terminal` is the PMS terminal interpreter package. It registers the `terminal` interpreter, provides the terminal command registry, builds the command sandbox, injects command input/output helpers, and exposes the base contracts used by terminal process extensions.

This is package-level developer documentation. It describes how to build or extend terminal interpreter capabilities, not any business command behavior.

## Package Role

- Composer package: `superpms/interpreter-terminal`
- PHP requirement: `>=8.2`
- Runtime dependency: `superpms/basic`
- Autoload entry: `bin/autoload.php`
- Interpreter name: `terminal`
- Built-in command: `vendor:install:hook`

The package is loaded through Composer `autoload.files`. `bin/autoload.php` loads terminal constants and then runs `bin/autorun.php`, which mounts:

- `pms\interpreter\terminal\Interpreter` into `pms\hook\InterpreterHook` as `terminal`
- `pms\program\vendorInstallHook\VendorInstallHookCommand` into `pms\hook\TerminalCommandHook`

## Installation

```bash
composer require superpms/interpreter-terminal
```

The package publishes its default command config source through Composer metadata:

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

`resource/config.php` is intentionally empty in this package. It is a project config seed for terminal command mappings; the built-in `vendor:install:hook` command is registered by `bin/autorun.php`, not by that config file.

## Minimal Command Example

```php
<?php

namespace app\command;

use pms\app\TerminalCommandApp;

class HelloCommand extends TerminalCommandApp
{
    protected string $name = 'hello';

    protected string $description = 'Print a greeting.';

    protected array $validate = [
        'name' => [
            'type' => COMMAND_ARGUMENT_TYPE,
            'default' => 'world',
        ],
        'upper' => [
            'type' => COMMAND_OPTION_TYPE,
            'default' => '0',
        ],
    ];

    public function entry(): void
    {
        $name = (string) $this->input->getArgument('name');
        $text = "hello {$name}";

        if ($this->input->getOption('upper') === '1') {
            $text = strtoupper($text);
        }

        $this->output::writeLn($text);
    }
}
```

Register the command either by mounting the class:

```php
pms\hook\TerminalCommandHook::mount(app\command\HelloCommand::class);
```

or by adding it to the host project's `command` config:

```php
return [
    'hello' => app\command\HelloCommand::class,
];
```

Then run:

```bash
php pms hello Codex --upper 1
```

## Runtime Chain

1. Boot resolves the `terminal` interpreter through `InterpreterHook::run('terminal')`.
2. `Interpreter::entry()` prepares terminal error interruption handling and calls `TerminalCommandHook::run()`.
3. `TerminalCommandHook::run()` reads `$_SERVER['argv']`, merges mounted commands with `config('command', [])`, and resolves the command class.
4. `Sandbox::run()` registers terminal lifecycle events, creates `CommandInput` and `CommandOutput`, instantiates the command class, and calls `entry()`.

## Documentation

- [docs/00-index.md](docs/00-index.md): documentation entry and reading map
- [docs/getting-started/installation-and-boot.md](docs/getting-started/installation-and-boot.md): installation, Composer autoload, and Boot mounting
- [docs/getting-started/minimal-command.md](docs/getting-started/minimal-command.md): smallest command class and registration choices
- [docs/runtime/runtime-chain.md](docs/runtime/runtime-chain.md): `Interpreter`, `TerminalCommandHook`, and `Sandbox` execution chain
- [docs/runtime/input-output.md](docs/runtime/input-output.md): `CommandInput` and `CommandOutput`
- [docs/command-contract/command-class.md](docs/command-contract/command-class.md): command class contract
- [docs/command-contract/argv-validate.md](docs/command-contract/argv-validate.md): argv, argument, option, and validate parsing
- [docs/injection-and-hooks/injection.md](docs/injection-and-hooks/injection.md): terminal input/output injection
- [docs/injection-and-hooks/hooks.md](docs/injection-and-hooks/hooks.md): command and lifecycle hooks
- [docs/process-module/process-lifecycle.md](docs/process-module/process-lifecycle.md): process module lifecycle
- [docs/process-module/driver-contract.md](docs/process-module/driver-contract.md): process driver contract
- [docs/vendor-install-hook/vendor-install-hook.md](docs/vendor-install-hook/vendor-install-hook.md): Composer install hook command
- [docs/errors-and-debugging/cli-debugging.md](docs/errors-and-debugging/cli-debugging.md): CLI exceptions, `dd()`, and debugging notes
- [docs/reference/configuration.md](docs/reference/configuration.md): config and constants reference
- [docs/reference/public-classes.md](docs/reference/public-classes.md): public class index
- [docs/reference/extension-points.md](docs/reference/extension-points.md): extension points and package boundaries

## Development Boundary

This package owns terminal interpretation, command registration, sandbox execution, command IO abstractions, terminal lifecycle hooks, and abstract process tracking contracts.

It does not own business command semantics, business API protocols, HTTP request handling, Redis process storage implementation, or concrete service monitoring behavior. Those belong to host projects or other Composer packages that depend on this package.

## License

Apache-2.0. See [LICENSE.txt](LICENSE.txt).
