<?php

namespace Controller;

use Core\Connection\Connection;

class Check extends AbstractAction
{
    protected string $template = 'SuccessLogin';
    private \Core\DB\Mysql $mysql;

    public function __construct()
    {
        $this->mysql = new \Core\DB\Mysql(new Connection());
    }

    public function execute(): ?string
    {
        $postParams = \Core\Request\Parser::getRequest()->getPostParams();
        $login = $postParams['login'];
        $result = $this->mysql->select("user", "login = '$login'");
        return parent::execute();
    }

}