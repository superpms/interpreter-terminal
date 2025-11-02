<?php

namespace pms\hook;

use pms\contract\HookInterface;
use pms\interpreter\terminal\Sandbox;

class TerminalCommandHook implements HookInterface
{

    protected static string $name = 'Terminal Interpreter';

    protected static array $container = [];

    public static function mount(string $commandName, string $commandClass): bool
    {
        if (!static::has($commandName)) {
            static::$container[$commandName] = $commandClass;
            return true;
        }
        return false;
    }

    public static function has(string $commandName): bool
    {
        return isset(static::$container[$commandName]);
    }

    public static function run(\pms\program\boot\Options $bootOptions): mixed
    {
        $argv = $_SERVER['argv'];
        array_shift($argv);
        if (empty($argv)) {
            exit("未输入要执行的命令");
        }
        static::$container = [
            ...static::$container,
            ...config('command', []),
        ];
        $name = $argv[0];
        if (!isset(static::$container[$name])) {
            $interpreterName = static::$name;
            exit("{$interpreterName}: 命令 [{$name}] 不存在");
        }
        return (new Sandbox(static::$container, $name, $argv,$bootOptions))->run();
    }

    public static function audit()
    {
        return static::$container;
    }
}