# Process Driver Contract

`TerminalProcessDriverModuleInterface` defines the storage contract for terminal process state.

## Interface

```php
interface TerminalProcessDriverModuleInterface
{
    const space = '';

    public static function createServiceAddress(string $serviceName): string;
    public static function createProcessAddress(string $serviceName, string $pid): string;
    public static function getService(string $serviceAddress): array;
    public static function getServices(): false|array;
    public static function getProcess(string $processAddress): mixed;
    public static function active(string $processAddress, string $processInfo, int $keepAliveTime): bool;
}
```

## Base Driver

`TerminalProcessDriverModule` implements the address helpers:

```php
public static function createServiceAddress(string $serviceName): string
{
    return static::space . $serviceName . ':';
}

public static function createProcessAddress(string $serviceName, string $pid): string
{
    return static::createServiceAddress($serviceName) . $pid;
}
```

Concrete drivers must still implement service lookup, process lookup, and active writes.

## Address Model

The abstract model has two address levels:

- service address: `space + serviceName + ':'`
- process address: `serviceAddress + pid`

The package does not require a specific storage backend. A Redis implementation can map addresses to keys with TTL, while another driver could write to files, a database, or an in-memory registry.

## Process Module Binding

A concrete process module selects a driver by setting:

```php
protected string $processDriver = MyProcessDriver::class;
```

Then `TerminalProcessModule` uses that driver for address creation and active writes.

## Responsibilities

A driver implementation owns:

- storage namespace prefix through `space`
- service aggregation behavior
- process state restore lookup
- keep-alive / TTL semantics, if the backend supports them

This package owns only the interface and the shared address helper convention.
