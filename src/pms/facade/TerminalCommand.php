<?php

namespace pms\facade;

use pms\core\driver\TerminalCommand\Driver;
use pms\Facade;

/**
 * @see Driver
 * @mixin Driver
 */
class TerminalCommand extends Facade
{
    protected static function getFacadeClass(): string
    {
        return Driver::class;
    }

}