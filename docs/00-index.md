# interpreter-terminal Documentation

This directory is the developer documentation for the `superpms/interpreter-terminal` Composer package. It is organized by package module and runtime responsibility so later readers can enter from the question they have, instead of reading every file.

## Read First

- [getting-started/installation-and-boot.md](getting-started/installation-and-boot.md): how Composer loads this package and how the `terminal` interpreter is mounted.
- [runtime/runtime-chain.md](runtime/runtime-chain.md): how a terminal command travels from interpreter entry to command `entry()`.
- [command-contract/command-class.md](command-contract/command-class.md): what a command class must provide.

## By Question

- "How do I install or mount the package?": [getting-started/installation-and-boot.md](getting-started/installation-and-boot.md)
- "How do I write the smallest command?": [getting-started/minimal-command.md](getting-started/minimal-command.md)
- "How is a command found and executed?": [runtime/runtime-chain.md](runtime/runtime-chain.md)
- "How are argv, arguments, and options parsed?": [command-contract/argv-validate.md](command-contract/argv-validate.md)
- "How do `$input` and `$output` appear on a command?": [injection-and-hooks/injection.md](injection-and-hooks/injection.md)
- "Which hooks can I mount around terminal execution?": [injection-and-hooks/hooks.md](injection-and-hooks/hooks.md)
- "How do long-running commands publish process state?": [process-module/process-lifecycle.md](process-module/process-lifecycle.md)
- "How do I implement another process storage driver?": [process-module/driver-contract.md](process-module/driver-contract.md)
- "What does `vendor:install:hook` do?": [vendor-install-hook/vendor-install-hook.md](vendor-install-hook/vendor-install-hook.md)
- "Why does `dd()` behave differently in CLI?": [errors-and-debugging/cli-debugging.md](errors-and-debugging/cli-debugging.md)
- "What are the public classes and constants?": [reference/public-classes.md](reference/public-classes.md) and [reference/configuration.md](reference/configuration.md)
- "What may this package be extended to do?": [reference/extension-points.md](reference/extension-points.md)

## Module Map

- `bin/`: Composer file autoload, package constants, interpreter and built-in command mounting.
- `src/pms/app/`: command base classes.
- `src/pms/interpreter/terminal/`: terminal interpreter entry and sandbox.
- `src/pms/interpreter/terminal/sandbox/`: command input and output implementations.
- `src/pms/inject/`: input/output injection interfaces.
- `src/pms/hook/`: command registry and terminal lifecycle hook.
- `src/pms/module/`: abstract process state module and process driver base.
- `src/pms/contract/`: process driver contract.
- `src/pms/program/vendorInstallHook/`: built-in install hook command.
- `resource/`: config seed declared in Composer metadata.

## Not Covered Here

This documentation intentionally does not describe business commands, business APIs, tenant behavior, HTTP routing, Swoole server behavior, Redis driver implementation details, or project-specific command lists. Those are outside this package boundary.
