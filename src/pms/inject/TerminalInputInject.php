<?php

namespace pms\inject;

interface TerminalInputInject
{

    public function getArgument(?string $name = null);

    public function getOption(?string $name = null);

}