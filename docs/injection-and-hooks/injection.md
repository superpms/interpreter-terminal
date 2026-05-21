# Injection

Terminal command input and output are delivered through the framework `Inject` attribute and the terminal sandbox container.

## Interfaces

This package defines two injection interfaces:

```php
pms\inject\TerminalInputInject
pms\inject\TerminalOutputInject
```

`TerminalInputInject` exposes:

```php
getArgument(?string $name = null);
getOption(?string $name = null);
```

`TerminalOutputInject` exposes the static output helpers implemented by `CommandOutput`.

## Base Command Properties

`TerminalCommandApp` declares:

```php
#[Inject(TerminalInputInject::class)]
protected TerminalInputInject $input;

#[Inject(TerminalOutputInject::class)]
protected TerminalOutputInject $output;
```

The properties are filled after the command object is instantiated by the sandbox.

## Sandbox Bindings

Before command construction, `Sandbox::run()` puts these values into the container:

```php
$this->put(TerminalInputInject::class, new CommandInput($validate));
$this->put(TerminalOutputInject::class, CommandOutput::class);
```

The base container then processes property attributes. When it sees `#[Inject(TerminalInputInject::class)]`, it resolves the preloaded `CommandInput` instance. When it sees `#[Inject(TerminalOutputInject::class)]`, it resolves the preloaded `CommandOutput` class binding.

## Practical Use

Inside a command:

```php
$value = $this->input->getArgument('name');
$this->output::writeLn("name: {$value}");
```

The output property is typed as an interface, but the injected value is the concrete `CommandOutput` class, whose methods are static. Existing package code calls it as `$this->output::writeLn(...)`.

## Extension Boundary

This package owns the terminal-specific bindings for `TerminalInputInject` and `TerminalOutputInject`.

The generic `Inject` attribute, annotation hook, and container reflection logic are owned by `superpms/basic`. If injection itself fails, check that the host runtime initialized the basic package's annotation property hook before the terminal command is executed.
