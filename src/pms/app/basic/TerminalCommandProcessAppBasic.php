<?php

namespace pms\app\basic;

use pms\app\TerminalCommandApp;
use pms\module\TerminalProcessModule;

abstract class TerminalCommandProcessAppBasic extends TerminalCommandApp
{
    /**
     * @var int 保持活动间隔
     */
    protected int $keepAliveInterval = 20;

    /**
     * @var string 任务UUID
     */
    protected string $taskUUID = "";

    /**
     * @var TerminalProcessModule 终端进程模块
     */
    protected TerminalProcessModule $terminalProcess;

    /**
     * 创建进程模块
     * @param string $taskUUID
     * @param int|null $pid
     * @return TerminalProcessModule
     */
    protected function createProcessModule(string $taskUUID, int $pid = null):TerminalProcessModule
    {
        throw new \Exception("未实现createProcessModule方法");
    }

    protected function processStart(int $pid = null): void{
        $this->terminalProcess = $this->createProcessModule($this->taskUUID, $pid);
        $this->terminalProcess->start();
        $this->terminalProcess->setKeepAliveInterval($this->keepAliveInterval);
    }

    protected function heartbeat(): void
    {
        $this->terminalProcess->heartbeat();
    }


}