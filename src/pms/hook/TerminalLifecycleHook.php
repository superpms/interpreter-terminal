<?php

namespace pms\hook;

use pms\app\LifecycleHookApp;

class TerminalLifecycleHook extends LifecycleHookApp
{

    protected static array $container = [
        LIFECYCLE_BOOT=>[],
        LIFECYCLE_BOOTED=>[],
        LIFECYCLE_SANDBOX_BOOTED=>[],
        LIFECYCLE_SANDBOX_RAN=>[],
        LIFECYCLE_SANDBOX_DESTRUCT=>[],
    ];



}