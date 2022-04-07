<?php

namespace Action;

class Save extends AbstractAction
{
    private \Core\Mysql $mysql;

    public function __construct(
        \Core\Mysql $mysql
    ) {
        $this->mysql = $mysql;
    }

    public function execute(): ?string
    {

    }
}