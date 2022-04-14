<?php

namespace Controller;

use Core\Connection\Connection;
use Core\DB\Mysql;
use Core\Request\Parser;
use PDOException;

class Save extends AbstractAction
{
    private Mysql $mysql;
    protected string $template = 'SuccessRegister';

    public function __construct(
    ) {
        $this->mysql = new Mysql(new Connection());
    }

    public function execute(): ?bool
    {
        $postParams = Parser::getRequest()->getPostParams();
        try {
            $result = $this->mysql->insert('user', $postParams);
        } catch (PDOException) {
            $this->template = 'DuplicateEntry';
        }
        parent::execute();
        return true;
    }
}