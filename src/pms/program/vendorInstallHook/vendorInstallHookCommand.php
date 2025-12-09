<?php

namespace pms\program\vendorInstallHook;

use pms\app\TerminalCommandApp;

class vendorInstallHookCommand extends TerminalCommandApp
{
    protected string $name = "vendor:install:hook";
    protected array $validate = [];
    public function entry()
    {
    }
}