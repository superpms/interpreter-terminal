# Public Classes

This index lists package classes by responsibility.

## App Base Classes

### `pms\app\TerminalCommandApp`

Base class for terminal commands. Provides command metadata fields, final constructor, and injected terminal input/output properties.

### `pms\app\basic\TerminalCommandProcessAppBasic`

Base class for process-aware terminal commands. Adds `taskUUID`, `keepAliveInterval`, process module creation, process start, and heartbeat helpers.

## Interpreter Runtime

### `pms\interpreter\terminal\Interpreter`

Terminal interpreter entry. Runs terminal command dispatch and handles CLI forced interruption from `dd()`.

### `pms\interpreter\terminal\Sandbox`

Container-backed runtime for a single command execution. Creates input/output bindings, instantiates the command, triggers terminal lifecycle events, and calls `entry()`.

## Sandbox IO

### `pms\interpreter\terminal\sandbox\CommandInput`

Concrete parser for terminal argv based on command `validate`.

### `pms\interpreter\terminal\sandbox\CommandOutput`

Concrete output helper for terminal text, JSON, arrays, ANSI color, bold text, and process exit.

## Injection Interfaces

### `pms\inject\TerminalInputInject`

Interface for reading parsed arguments and options.

### `pms\inject\TerminalOutputInject`

Interface for static terminal output helpers.

## Hooks

### `pms\hook\TerminalCommandHook`

Command registry and dispatch hook.

### `pms\hook\TerminalLifecycleHook`

Lifecycle hook container for terminal command runtime events.

## Process Modules

### `pms\module\TerminalProcessModule`

Abstract process state module. Handles service/process addresses, start time, active time, keep-alive interval, heartbeat, and restore.

### `pms\module\TerminalProcessDriverModule`

Abstract process driver base. Implements shared service and process address helpers.

### `pms\contract\TerminalProcessDriverModuleInterface`

Contract for process storage drivers.

## Built-In Command

### `pms\program\vendorInstallHook\VendorInstallHookCommand`

Built-in `vendor:install:hook` command. Applies simple package install actions declared under Composer `extra.pms`.
