# Input And Output

The terminal sandbox provides command input and output through `TerminalInputInject` and `TerminalOutputInject`.

## CommandInput

`pms\interpreter\terminal\sandbox\CommandInput` implements `pms\inject\TerminalInputInject`.

It reads `$_SERVER['argv']` directly:

1. Drops the PHP script name.
2. Stores the first remaining item as the command name.
3. Drops the command name.
4. Removes empty argv entries.
5. Builds defaults from the command class `validate` definition.
6. Parses remaining argv into positional arguments and named options.

## Arguments

Arguments are declared with:

```php
'name' => [
    'type' => COMMAND_ARGUMENT_TYPE,
    'default' => null,
]
```

The parser assigns non-option argv values to argument keys by declaration order.

```bash
php pms hello Alice
```

If `name` is the first argument declaration, `getArgument('name')` returns `Alice`.

## Options

Options are declared with:

```php
'upper' => [
    'type' => COMMAND_OPTION_TYPE,
    'default' => '0',
]
```

Options are stored internally with a `--` prefix. Both `--upper 1` and `-upper 1` are accepted by the parser because it strips one or two leading dash characters before matching.

```bash
php pms hello Alice --upper 1
```

`getOption('upper')` returns `1`.

Boolean flag-only behavior is not implemented as a separate type. If an option has no following non-option value, its default remains unchanged.

## Accessors

`TerminalInputInject` exposes:

```php
public function getArgument(?string $name = null);
public function getOption(?string $name = null);
```

The concrete `CommandInput` also provides `getParams(): array`, which returns arguments and options merged together. That method is concrete-class functionality and is not part of the interface.

## CommandOutput

`pms\interpreter\terminal\sandbox\CommandOutput` implements `pms\inject\TerminalOutputInject`.

Available methods:

- `write()` / `print()`: echo strings without a line break.
- `writeLn()` / `printLn()`: echo strings followed by `"\r\n"`.
- `writeJsonStr()` / `printJsonStr()`: echo JSON-encoded arrays.
- `writeJsonStrLn()` / `printJsonStrLn()`: echo JSON-encoded arrays with line breaks.
- `writeArray()` / `printArray()`: print scalar key-value lines and nested arrays as JSON.
- `writeArrayBlock()`: print a simple dashed block around array values.
- `end()`: exit the process with an optional string.
- `setColorStr()`: wrap text in an ANSI color sequence.
- `setBoldStr()`: wrap text in an ANSI bold sequence.

## Color Constants

`bin/const.php` defines:

- `TERMINAL_COLOR_RED = 31`
- `TERMINAL_COLOR_GREEN = 32`
- `TERMINAL_COLOR_YELLOW = 33`
- `TERMINAL_COLOR_BLUE = 34`
- `TERMINAL_COLOR_MAGENTA = 35`
- `TERMINAL_COLOR_CYAN = 36`
