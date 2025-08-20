<?php

namespace pms\interpreter\terminal;

use pms\app\TerminalCommandApp;
use pms\Container;
use pms\exception\CliModeForcedInterruptException;
use pms\inject\TerminalInputInject;
use pms\inject\TerminalOutputInject;
use pms\interpreter\terminal\sandbox\CommandInput;
use pms\interpreter\terminal\sandbox\CommandOutput;
use pms\program\boot\Options;

class Sandbox extends Container
{

    public function __construct(
        protected array $command,
        protected string $name ,
        protected array $argv,
        protected Options $bootOptions,
    ){

    }

    public function run()
    {
        $namespace = $this->command[$this->name];
        $class = $this->getClass($namespace);
        $validate = $class->getProperty('validate')->getDefaultValue();
        $this->put(TerminalInputInject::class, (new CommandInput($validate)));
        $this->put(TerminalOutputInject::class, CommandOutput::class);
        /**
         * @var $obj TerminalCommandApp
         */
        $obj = $this->invokeClass($namespace, [
            $this->command,
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