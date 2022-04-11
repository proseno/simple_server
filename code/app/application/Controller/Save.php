<?php

namespace Controller;

use Core\Connection\Connection;

class Save extends AbstractAction
{
    private \Core\DB\Mysql $mysql;
    protected string $template = 'SuccessRegister';

    public function __construct(
    ) {
        $this->mysql = new \Core\DB\Mysql(new Connection());
    }

    public function execute(): ?string
    {
        $postParams = \Core\Request\Parser::getRequest()->getPostParams();
        try {
            $result = $this->mysql->insert('user', $postParams);
        } catch (\PDOException) {
            $this->template = 'DuplicateEntry';
        }
        return parent::execute();
    }
}