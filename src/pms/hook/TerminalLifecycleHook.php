<?php

namespace pms\hook;

use pms\app\LifecycleHookApp;

class TerminalLifecycleHook extends LifecycleHookApp
{

    protected static array $container = [
        LIFECYCLE_BOOT=>[]
    ];



}