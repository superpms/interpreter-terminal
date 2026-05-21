# Argv And Validate

`CommandInput` parses terminal argv according to a command class's `validate` property.

## Source Data

The parser reads `$_SERVER['argv']`, then removes:

1. the PHP script path
2. the terminal command name

Only the remaining items are parsed as command arguments or options.

For:

```bash
php pms export users --format json
```

the terminal command name is `export`, and the parser receives:

```text
users --format json
```

## Validate Item Types

The package defines two parser type constants:

```php
const COMMAND_OPTION_TYPE = 'option';
const COMMAND_ARGUMENT_TYPE = 'argument';
```

The parser compares `type` case-insensitively.

## Argument Parsing

Arguments are positional. During validation initialization, every item with `type = COMMAND_ARGUMENT_TYPE` is appended to an argument map in declaration order.

```php
protected array $validate = [
    'entity' => [
        'type' => COMMAND_ARGUMENT_TYPE,
    ],
    'id' => [
        'type' => COMMAND_ARGUMENT_TYPE,
        'default' => '0',
    ],
];
```

For:

```bash
php pms inspect user 42
```

the parser assigns:

- `entity = user`
- `id = 42`

If an argument is missing, its configured `default` is used, or `null` if no default exists.

## Option Parsing

Options are named. During validation initialization, every item with `type = COMMAND_OPTION_TYPE` is stored under a `--` prefixed key.

```php
protected array $validate = [
    'format' => [
        'type' => COMMAND_OPTION_TYPE,
        'default' => 'text',
    ],
];
```

For:

```bash
php pms inspect user --format json
```

`getOption('format')` returns `json`.

The parser accepts one or two leading dashes because it removes the first dash and then removes a second dash if present.

## Option Values

An option consumes the next argv item only when the next item exists and does not begin with `-`.

For:

```bash
php pms inspect user --format --verbose 1
```

`format` keeps its default value because the next item starts with `-`.

There is no dedicated boolean flag type. Model flags as options with explicit values or interpret unchanged defaults inside the command.

## Params Merge

After parsing, `CommandInput` builds:

```php
$this->params = array_merge($this->arguments, $this->options);
```

Because options are stored with `--` prefixed keys, the merged params array contains keys such as `--format`.

## Current Limits

- `required` is not enforced by `CommandInput`.
- aliases are not a built-in parser feature.
- short option bundling such as `-abc` is not implemented.
- values that intentionally start with `-` cannot be consumed as option values by the current parser.
