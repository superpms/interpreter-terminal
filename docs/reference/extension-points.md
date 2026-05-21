# Extension Points

This package is designed to be extended by other Composer packages and host projects.

## Add A Command

Create a class that extends `TerminalCommandApp`, then register it through one of these routes:

- package startup: `TerminalCommandHook::mount(CommandClass::class)`
- host config: `config('command')`

Use package startup when the command belongs to a reusable Composer package. Use host config when the command belongs to a specific project.

## Add Runtime Behavior Around Commands

Mount callbacks into `TerminalLifecycleHook`:

```php
TerminalLifecycleHook::mount(LIFECYCLE_SANDBOX_BOOTED, MyObserver::class);
```

String callback classes must provide a callable `entry` method because `LifecycleHookApp` converts non-callable strings to `[ClassName, 'entry']`.

## Add A Process Backend

Implement a process driver by extending `TerminalProcessDriverModule` and implementing the remaining methods from `TerminalProcessDriverModuleInterface`.

Then create a process module subclass:

```php
class MyProcessModule extends TerminalProcessModule
{
    protected string $processDriver = MyProcessDriver::class;
}
```

Then create a command base subclass of `TerminalCommandProcessAppBasic` and implement `createProcessModule()`.

## Add Install-Time Package Scaffolding

Declare supported `extra.pms` keys in the package's `composer.json`:

- `dir`
- `config`
- `copy`

The built-in `vendor:install:hook` command will process them when it is run by the host project.

## Boundaries

Do not add business command behavior to this package merely because business commands run through terminal.

The package boundary is:

- terminal interpreter registration
- command registry
- terminal sandbox
- input/output abstractions
- terminal lifecycle hooks
- abstract process tracking contracts
- Composer install hook command

Outside this package:

- concrete business commands
- HTTP interpreter behavior
- Swoole server behavior
- concrete Redis process monitor implementation
- project-specific command config
- business API documentation
