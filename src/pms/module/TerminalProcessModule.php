<?php

namespace pms\module;

use pms\contract\TerminalProcessDriverModuleInterface;
use pms\OptionsAccessCfg;

/**
 * @property int $space 内存空间;
 * @property int $service_name;
 * @property int $service_address 服务内存地址;
 * @property int $keep_alive_interval 保持活动间隔;
 * @property int $pid;
 * @property int $address 进程内存地址;
 * @property int $start_time;
 * @property int $active_time;
 */
abstract class TerminalProcessModule extends OptionsAccessCfg
{

    /**
     * @var TerminalProcessDriverModuleInterface $processDriver
     */
    protected string $processDriver;

    public function __construct(string $serviceName, int $pid = null)
    {
        parent::__construct(false);
        $this->space = $this->processDriver::space;
        $this->service_name = $serviceName;
        $this->service_address = $this->processDriver::createServiceAddress($serviceName);
        $this->keep_alive_interval = 20;
        $this->active_time = 0;
        $this->start_time = 0;
        if ($pid !== null) {
            $this->pid = $pid;
        } else {
            $this->pid = getmypid();
        }
    }

    public function setKeepAliveInterval(int $keepAliveInterval): static
    {
        $this->keep_alive_interval = $keepAliveInterval;
        return $this;
    }

    public function start(): static
    {
        if ($this->start_time === 0) {
            $this->start_time = time();
            $this->address = $this->processDriver::createProcessAddress($this->service_name, $this->pid);
        }
        return $this;
    }

    public function active(): bool
    {
        $this->active_time = time();
        return $this->processDriver::active($this->address, json_encode($this->toArray(), 320),$this->keep_alive_interval);
    }

    public function heartbeat(int $threshold=3): bool
    {
        $currentTime = time();
        $nextTime = $this->active_time + $this->keep_alive_interval - $threshold;
        if ($nextTime > $currentTime) {
            return true;
        }
        return $this->active();
    }

    public static function restore(array $data): static
    {
        $terminalProcess = new static($data['SERVICE_NAME'], $data['PID']);
        foreach ($data as $k => $v){
            $terminalProcess->$k = $v;
        }
        return $terminalProcess;
    }

}