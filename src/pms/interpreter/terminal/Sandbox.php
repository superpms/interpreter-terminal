<?php

namespace pms\interpreter\terminal;

use pms\Container;
use pms\exception\CliModeForcedInterruptException;
use pms\inject\TerminalInputInject;
use pms\inject\TerminalOutputInject;
use pms\contract\AppInterface;
use pms\interpreter\terminal\sandbox\CommandInput;
use pms\interpreter\terminal\sandbox\CommandOutput;

class Sandbox extends Container
{

    public function __construct(
        protected array $command,
        protected string $name ,
        protected array $argv
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
         * @var $obj AppInterface
         */
        $obj = $this->invokeClass($namespace, [
            $this->command,
            $this->argv
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