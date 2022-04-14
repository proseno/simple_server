<?php

namespace Controller;

use Core\Connection\Connection;
use Core\DB\Mysql;
use Core\Request\Parser;

class Check extends AbstractAction
{
    protected string $template = 'SuccessLogin';
    private Mysql $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql(new Connection());
    }

    public function execute(): ?bool
    {
        $postParams = Parser::getRequest()->getPostParams();
        $login = $postParams['login'];
        $result = $this->mysql->select("user", "login = '$login'");
        parent::execute();
        return true;
    }
}