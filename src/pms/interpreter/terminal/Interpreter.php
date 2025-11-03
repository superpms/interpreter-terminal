<?php

namespace pms\interpreter\terminal;
use pms\app\InterpreterApp;
use pms\exception\CliModeForcedInterruptException;
use pms\hook\TerminalCommandHook;
use pms\interpreter\terminal\sandbox\CommandOutput;

class Interpreter extends InterpreterApp
{
    protected static string $name = 'Terminal Interpreter';


    public static function run(\pms\program\boot\Options $bootOptions): mixed{
        try{
            return TerminalCommandHook::run($bootOptions);
        }catch (\Throwable $throwable){
            if(!($throwable instanceof CliModeForcedInterruptException)){
                throw $throwable;
            }else{
                exit(CommandOutput::setColorStr(TERMINAL_COLOR_RED, "dd(...) Forced Interrupt!"));
            }
        }
    }

}