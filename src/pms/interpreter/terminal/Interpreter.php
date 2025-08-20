<?php

namespace pms\interpreter\terminal;
use pms\app\InterpreterApp;
use pms\hook\TerminalCommandHook;

class Interpreter extends InterpreterApp
{
    protected static string $name = 'Terminal Interpreter';


    public static function run(\pms\program\boot\Options $bootOptions): mixed{
        return TerminalCommandHook::run($bootOptions);
    }

}