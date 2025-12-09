<?php

namespace pms\hook;

use pms\app\TerminalCommandApp;
use pms\contract\HookAppInterface;
use pms\interpreter\terminal\Sandbox;
use pms\interpreter\terminal\sandbox\CommandOutput;
use ReflectionClass;

class TerminalCommandHook implements HookAppInterface
{

    protected static string $name = 'Terminal Interpreter';

    protected static array $container = [];

    public static function mount(string $commandClass): bool
    {
        if(class_exists($commandClass)){
            $class = new ReflectionClass($commandClass);
            if(!$class->isSubclassOf(TerminalCommandApp::class)){
                return false;
            }
            if($class->hasProperty('name')){
                $nameProperty = $class->getProperty('name');
                if($nameProperty->hasDefaultValue()){
                    $commandName = $nameProperty->getDefaultValue();
                    if (!static::has($commandName)) {
                        static::$container[$commandName] = $commandClass;
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function has(string $commandName): bool
    {
        return isset(static::$container[$commandName]);
    }

    public static function run(): mixed
    {
        TerminalLifecycleHook::run(LIFECYCLE_BOOT);
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
        $namespace = static::$container[$name];
        if (!class_exists($namespace)) {
            exit(CommandOutput::setColorStr(TERMINAL_COLOR_RED,"Command with class not found: " . $name));
        }
        TerminalLifecycleHook::run(LIFECYCLE_BOOTED,$name,$argv,$namespace);
        return (new Sandbox(static::$container, $name, $argv))->run($namespace);
    }

    public static function audit()
    {
        return static::$container;
    }
}