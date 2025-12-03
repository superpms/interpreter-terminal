<?php

namespace pms\app;

use pms\annotate\Inject;
use pms\contract\AppInterface;
use pms\inject\TerminalInputInject;
use pms\inject\TerminalOutputInject;

abstract class TerminalCommandApp implements AppInterface
{

    /**
     * @var string 命令名称
     */
    protected string $name = "";

    /**
     * 命令描述
     * @var string
     */
    protected string $description = "";

    /**
     * @var array 参数规则验证
     */
    protected array $validate = [];

    /**
     * 命令是否安装
     * @param string $commandName 命令名称
     * @return bool
     */
    private function isInstall(string $commandName): bool
    {
        return array_key_exists($commandName,$this->commandList);
    }

    final public function __construct(protected array $commandList,protected array $argv){}

    #[Inject(TerminalInputInject::class)]
    protected TerminalInputInject $input;

    #[Inject(TerminalOutputInject::class)]
    protected TerminalOutputInject $output;
}