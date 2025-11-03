<?php

namespace pms\module;


use pms\contract\TerminalProcessDriverModuleInterface;

abstract class TerminalProcessDriverModule implements TerminalProcessDriverModuleInterface
{

    /**
     * 创建服务地址
     * @param string $serviceName
     * @return string
     */
    public static function createServiceAddress(string $serviceName): string
    {
        return static::space . $serviceName . ':';
    }

    /**
     * 创建进程地址
     * @param string $serviceName
     * @param string $pid
     * @return string
     */
    public static function createProcessAddress(string $serviceName, string $pid): string
    {
        return static::createServiceAddress($serviceName) . $pid;
    }
}