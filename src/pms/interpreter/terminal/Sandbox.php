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

    public function run(string $namespace)
    {
        TerminalLifecycleHook::run(LIFECYCLE_SANDBOX_CREATED,
            $this->name,
            $this->argv,
            $this->bootOptions,
            $this->commandList
        );

        $class = $this->getClass($namespace);


        $validate = $class->getProperty('validate')->getDefaultValue();
        $this->put(TerminalInputInject::class, (new CommandInput($validate)));
        $this->put(TerminalOutputInject::class, CommandOutput::class);
        TerminalLifecycleHook::run(LIFECYCLE_SANDBOX_BOOT,
            $this->name,
            $this->argv,
            $this->bootOptions,
            $this->commandList,
            $class
        );


        /**
         * @var $obj TerminalCommandApp
         */
        $obj = $this->invokeClass($class, [
            $this->commandList,
            $this->argv,
            $this->bootOptions
        ]);
        TerminalLifecycleHook::run(LIFECYCLE_SANDBOX_BOOTED,
            $this->name,
            $this->argv,
            $this->bootOptions,
            $this->commandList,
            $class,
            $obj
        );
        $obj->entry();
        TerminalLifecycleHook::run(LIFECYCLE_SANDBOX_RAN,
            $this->name,
            $this->argv,
            $this->bootOptions,
            $this->commandList,
            $class,
            $obj
        );
    }

    public function __destruct()
    {
        TerminalLifecycleHook::run(LIFECYCLE_SANDBOX_DESTRUCT);
    }
}