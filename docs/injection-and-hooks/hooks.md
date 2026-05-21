# Hooks

The package has two terminal-specific hooks:

- `pms\hook\TerminalCommandHook`
- `pms\hook\TerminalLifecycleHook`

## TerminalCommandHook

`TerminalCommandHook` is the command registry and command dispatch entry.

Its container shape is:

```php
commandName => commandClass
```

### Mount

```php
TerminalCommandHook::mount(CommandClass::class);
```

`mount()`:

1. checks that the class exists
2. checks that it extends `TerminalCommandApp`
3. reads the command class default `name` property
4. stores `name => class` only if the command name is not already present

Duplicate names are not overwritten by `mount()`.

### Run

`run()` reads argv, merges `config('command', [])`, validates the command class, and delegates to `Sandbox`.

### Audit

`audit()` returns the static command container mounted through `mount()`. It does not include the `config('command')` merge unless `run()` has already performed that merge in the current process.

## TerminalLifecycleHook

`TerminalLifecycleHook` extends `LifecycleHookApp` from `superpms/basic`.

It predeclares these lifecycle keys:

- `LIFECYCLE_BOOT`
- `LIFECYCLE_BOOTED`
- `LIFECYCLE_SANDBOX_CREATED`
- `LIFECYCLE_SANDBOX_BOOT`
- `LIFECYCLE_SANDBOX_BOOTED`
- `LIFECYCLE_SANDBOX_RAN`
- `LIFECYCLE_SANDBOX_DESTRUCT`

Because it uses the lifecycle hook base class:

- one lifecycle can have multiple callbacks
- callbacks run in mount order
- mounting fails if the lifecycle key is not predeclared
- string callbacks are interpreted as `[ClassName, 'entry']`

## Mounting Lifecycle Callbacks

```php
TerminalLifecycleHook::mount(LIFECYCLE_SANDBOX_BOOTED, function (
    string $name,
    array $argv,
    array $commandList,
    ReflectionClass $class,
    object $command
) {
    // observe or extend command runtime here
});
```

## Hook Responsibility

Use `TerminalCommandHook` to expose commands.

Use `TerminalLifecycleHook` to observe or extend the terminal runtime around command sandbox creation and execution.

Do not use terminal hooks to document or implement business command semantics inside this package.
