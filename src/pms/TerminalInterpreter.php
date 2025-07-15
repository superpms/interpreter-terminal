<?php

namespace pms;
use pms\app\InterpreterApp;
use pms\facade\TerminalCommand;
use pms\interpreter\terminal\Sandbox;

class TerminalInterpreter extends InterpreterApp
{
    protected static string $name = 'Terminal Interpreter';

    protected static array $command = [];

    public static function run(): mixed{
        $argv = $_SERVER['argv'];
        array_shift($argv);
        if(empty($argv)){
            exit("未输入要执行的命令");
        }
        static::$command = [
            ...static::$command,
            ...TerminalCommand::getAll(),
            ...config('command',[]),
        ];
        $name = $argv[0];
        if(!isset(static::$command[$name])){
            $interpreterName = static::$name;
            exit("{$interpreterName}: 命令 [{$name}] 不存在");
        }
        return (new Sandbox(static::$command,$name,$argv))->run();
    }

}