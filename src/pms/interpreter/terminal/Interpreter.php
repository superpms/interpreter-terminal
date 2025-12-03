<?php

namespace pms\interpreter\terminal;
use pms\app\InterpreterApp;
use pms\broadcast\SystemErrorBroadcast;
use pms\exception\CliModeForcedInterruptException;
use pms\hook\TerminalCommandHook;
use pms\interpreter\terminal\sandbox\CommandOutput;

class Interpreter extends InterpreterApp
{
    protected static string $name = 'Terminal Interpreter';


    public static function entry(): mixed{
        try{
            SystemErrorBroadcast::listener(function (){
                $err = pms_error();
                if($err !== null){
                    pms_error_clear();
                    throw $err;
                }
            },true);
            return TerminalCommandHook::run();
        }catch (\Throwable $throwable){
            if(!($throwable instanceof CliModeForcedInterruptException)){
                throw $throwable;
            }else{
                exit(CommandOutput::setColorStr(TERMINAL_COLOR_RED, "dd(...) Forced Interrupt!"));
            }
        }
    }

}