<?php
namespace pms;
use pms\hook\InterpreterHook;
use pms\hook\TerminalCommandHook;
use pms\program\vendorInstallHook\VendorInstallHookCommand;

InterpreterHook::mount(
    'terminal',
    interpreter\terminal\Interpreter::class
);

TerminalCommandHook::mount(
    VendorInstallHookCommand::class
);
