# CLI Debugging

Terminal debugging is shaped by `superpms/basic` error helpers and this package's terminal interpreter.

## Exception Handling

`Interpreter::entry()` catches only one exception type specially:

```php
pms\exception\CliModeForcedInterruptException
```

When the throwable is not that type, the interpreter rethrows it.

When it is that type, the interpreter exits with:

```text
dd(...) Forced Interrupt!
```

The message is colored red through `CommandOutput::setColorStr(TERMINAL_COLOR_RED, ...)`.

## dd In CLI

The global `dd()` helper is defined by `superpms/basic`, not by this package. In console mode, it dumps through Symfony VarDumper and then throws `CliModeForcedInterruptException` instead of calling `die` directly.

This package turns that exception into a terminal-specific forced interruption message.

## System Error Broadcast

Before running commands, the terminal interpreter registers a `SystemErrorBroadcast` listener. The listener reads `pms_error()`, clears it, and throws it if present.

This lets errors stored in the framework context surface through the terminal command chain.

## Command Not Found Cases

`TerminalCommandHook::run()` exits early in these cases:

- no command name: `未输入要执行的命令`
- command name missing from the final command table: `Terminal Interpreter: 命令 [name] 不存在`
- command class mapping exists but class cannot be loaded: red `Command with class not found: name`

Useful checks:

- Did Composer load `bin/autoload.php`?
- Did `bin/autorun.php` mount the command?
- Does the command class extend `TerminalCommandApp`?
- Does the command class have a non-empty default `$name`?
- Is the host `command` config loaded before terminal command dispatch?

## Input Debugging

If arguments or options are missing:

- confirm the `validate` key matches `getArgument()` or `getOption()`
- confirm option values do not start with `-`
- remember that `required` is not enforced by `CommandInput`
- remember that option keys are stored internally with a `--` prefix

## Lifecycle Debugging

If unexpected behavior happens before or after command `entry()`:

- inspect `TerminalLifecycleHook::audit()`
- check mounted callbacks for `LIFECYCLE_BOOTED`
- check mounted callbacks for `LIFECYCLE_SANDBOX_BOOTED`
- check whether a dependent package mounted monitoring or setup logic around the terminal sandbox
