<?php

namespace pms\interpreter\terminal;

use pms\app\TerminalCommandApp;
use pms\Container;
use pms\exception\CliModeForcedInterruptException;
use pms\hook\TerminalLifecycleHook;
use pms\inject\TerminalInputInject;
use pms\inject\TerminalOutputInject;
use pms\interpreter\terminal\sandbox\CommandInput;
use pms\interpreter\terminal\sandbox\CommandOutput;
use pms\program\boot\Options;

class Sandbox extends Container
{

    public function __construct(
        protected array $commandList,
        protected string $name ,
        protected array $argv,
        protected Options $bootOptions,
    ){

    }

    public function run()
    {
        $namespace = $this->commandList[$this->name];
        if (!class_exists($namespace)) {
            exit(CommandOutput::setColorStr(TERMINAL_COLOR_RED,"Command with class not found: " . $this->name));
        }
        $class = $this->getClass($namespace);
        $validate = $class->getProperty('validate')->getDefaultValue();
        $this->put(TerminalInputInject::class, (new CommandInput($validate)));
        $this->put(TerminalOutputInject::class, CommandOutput::class);
        TerminalLifecycleHook::run(LIFECYCLE_BOOT,
            $this->name,
            $this->argv,
            $class,
            $this->bootOptions,
            $this->commandList
        );
        /**
         * @var $obj TerminalCommandApp
         */
        $obj = $this->invokeClass($class, [
            $this->commandList,
            $this->argv,
            $this->bootOptions
        ]);
        try{
            $obj->entry();
        }catch (\Throwable $e){
            if(!($e instanceof CliModeForcedInterruptException)){
                throw $e;
            }else{
                exit(CommandOutput::setColorStr(TERMINAL_COLOR_RED, "dd(...) Forced Interrupt!"));
            }
        }
    }
}