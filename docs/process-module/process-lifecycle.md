# Process Module Lifecycle

This package provides abstract support for terminal commands that need to publish process state. It does not choose a concrete storage backend.

## Base Classes

- `pms\app\basic\TerminalCommandProcessAppBasic`
- `pms\module\TerminalProcessModule`
- `pms\module\TerminalProcessDriverModule`
- `pms\contract\TerminalProcessDriverModuleInterface`

Concrete packages provide storage-specific subclasses. For example, a Redis package can implement a process driver and a process module that sets `$processDriver` to that driver class.

## TerminalCommandProcessAppBasic

`TerminalCommandProcessAppBasic` extends `TerminalCommandApp` and adds common process tracking helpers:

```php
protected int $keepAliveInterval = 20;
protected string $taskUUID = "";
protected TerminalProcessModule $terminalProcess;
```

It requires subclasses to implement:

```php
protected function createProcessModule(string $taskUUID, ?int $pid = null): TerminalProcessModule
```

### Starting

```php
protected function processStart(?int $pid = null): void
```

`processStart()`:

1. creates a process module for `$this->taskUUID`
2. starts the module
3. sets the module keep-alive interval from `$this->keepAliveInterval`

### Heartbeat

```php
protected function heartbeat(int $threshold = 3): void
```

This delegates to `TerminalProcessModule::heartbeat($threshold)`.

## TerminalProcessModule

`TerminalProcessModule` extends `OptionsAccessCfg`, so stored option keys are uppercased in its serialized array representation.

Constructor inputs:

```php
public function __construct(string $serviceName, int $pid = null)
```

On construction, it initializes:

- `space`: driver `space` constant
- `service_name`: service name passed to the constructor
- `service_address`: driver-created service address
- `keep_alive_interval`: default `20`
- `active_time`: `0`
- `start_time`: `0`
- `pid`: passed pid or current process pid

## Start And Active

`start()` sets:

- `start_time = time()`
- `address = processDriver::createProcessAddress(service_name, pid)`

Only the first `start()` call changes these fields.

`active()` sets:

- `active_time = time()`

Then it calls:

```php
$this->processDriver::active(
    $this->address,
    json_encode($this->toArray(), 320),
    $this->keep_alive_interval
);
```

The driver decides where the serialized state is stored.

## Heartbeat Timing

`heartbeat($threshold)` avoids writing every time it is called. It computes:

```php
$nextTime = $this->active_time + $this->keep_alive_interval - $threshold;
```

If `$nextTime` is still in the future, it returns `true` without writing. Otherwise it calls `active()`.

## Restore

`restore(array $data)` creates a new static module from the stored `SERVICE_NAME` and `PID`, then copies every stored key back onto the object.

Because `OptionsAccessCfg` uppercases keys, stored arrays use uppercase names such as `SERVICE_NAME`, `PID`, and `ACTIVE_TIME`.
