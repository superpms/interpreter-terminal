<?php

namespace pms\core\driver\TerminalCommand;

class Driver
{

    protected array $command = [];

    public function install(string $commandName, string $commandClass): bool{
        if(!$this->has($commandName)){
            $this->command[$commandName] = $commandClass;
            return true;
        }
        return false;
    }

    public function has(string $commandName): bool{
        return isset($this->command[$commandName]);
    }

    public function getAll(): array
    {
        return $this->command;
    }


}