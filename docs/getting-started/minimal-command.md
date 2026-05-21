# Minimal Command

A terminal command is a class that extends `pms\app\TerminalCommandApp` and implements `entry()`.

## Command Class

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

## Registration Options

Package code can register a command directly:

```php
pms\hook\TerminalCommandHook::mount(app\command\HelloCommand::class);
```

Host projects can also register commands through `config('command')`:

```php
return [
    'hello' => app\command\HelloCommand::class,
];
```

At runtime, `TerminalCommandHook::run()` merges the already-mounted command container with `config('command', [])`.

## Run Example

```bash
php pms hello Codex --upper 1
```

`CommandInput` sees:

- command name: `hello`
- argument `name`: `Codex`
- option `upper`: `1`

## Important Boundaries

- The public CLI name is the default value of `protected string $name`.
- The class name and file name do not automatically become the command name.
- `validate` describes how this package parses terminal argv. Complex semantic validation still belongs in the command's `entry()` method or in the package that owns that command.
