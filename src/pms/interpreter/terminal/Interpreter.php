<?php

namespace pms\interpreter\terminal;
use pms\app\InterpreterApp;
use pms\hook\TerminalCommandHook;

class Interpreter extends InterpreterApp
{
    protected static string $name = 'Terminal Interpreter';


    public static function run(): mixed{
        return TerminalCommandHook::run();
    }

}