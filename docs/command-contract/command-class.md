# Command Class Contract

`pms\app\TerminalCommandApp` is the base class for terminal commands.

## Required Shape

A command class must:

- extend `TerminalCommandApp`
- provide an `entry()` instance method through `AppInterface`
- set a command name through the default value of `protected string $name`

The base class defines:

```php
protected string $name = "";
protected string $description = "";
protected array $validate = [];
final public function __construct(protected array $commandList, protected array $argv) {}
```

It also declares injected properties:

```php
#[Inject(TerminalInputInject::class)]
protected TerminalInputInject $input;

#[Inject(TerminalOutputInject::class)]
protected TerminalOutputInject $output;
```

## Command Name

The command name is read by `TerminalCommandHook::mount()` through reflection. It uses the default value of the `name` property.

Registration succeeds only when:

- the class exists
- the class is a subclass of `TerminalCommandApp`
- the class has a `name` property with a default value
- the command name is not already mounted in the static command container

The command class name is not used to infer the CLI command name.

## Description

`protected string $description` is a command metadata field. The current package does not automatically render help output from it, but command packages may use it when building command discovery or help surfaces.

## Validate

`protected array $validate` declares parser-level argument and option defaults. The current parser uses only:

- `type`
- `default`

Other metadata such as `des` or `required` may be used by command packages or help generators, but `CommandInput` does not enforce required validation by itself.

## Entry Method

`entry()` is the execution point. It is invoked by `Sandbox::run()` after the command object has been instantiated and after property injection has run.

Use `entry()` for command behavior:

```php
public function entry(): void
{
    $target = $this->input->getArgument('target');
    $this->output::writeLn("target: {$target}");
}
```

## Constructor Boundary

Do not override the constructor in command classes. The base constructor is final and receives the command list and argv from the sandbox.

## Internal Helper

`TerminalCommandApp` contains a private `isInstall(string $commandName): bool` helper that checks the instance command list. Because it is private, it is not an extension API for child commands.
