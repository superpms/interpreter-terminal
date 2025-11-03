<?php

namespace pms\contract;

interface TerminalProcessDriverModuleInterface
{
    const space = '';

    /**
     * 创建服务地址
     * @param string $serviceName
     * @return string
     */
    public static function createServiceAddress(string $serviceName): string;

    /**
     * 创建进程地址
     * @param string $serviceName
     * @param string $pid
     * @return string
     */
    public static function createProcessAddress(string $serviceName, string $pid): string;

    /**
     * 获取服务信息
     * @param string $serviceAddress
     * @return array
     */
    public static function getService(string $serviceAddress): array;

    /**
     * 获取所有服务
     * @return false|array
     */
    public static function getServices():false|array;

    /**
     * 获取进程信息
     * @param string $processAddress
     * @return mixed
     */
    public static function getProcess(string $processAddress):mixed;

    /**
     * 激活进程
     * @param string $processAddress
     * @param string $processInfo
     * @param int $keepAliveTime
     * @return bool
     */
    public static function active(string $processAddress,string $processInfo,int $keepAliveTime):bool;

}