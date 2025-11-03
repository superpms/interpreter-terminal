<?php
namespace pms;
use pms\hook\InterpreterHook;

InterpreterHook::mount(
    'terminal',
    interpreter\terminal\Interpreter::class
);
