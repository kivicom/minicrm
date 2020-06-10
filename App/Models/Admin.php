<?php

namespace App\Models;


use Aura\SqlQuery\QueryFactory;
use PDO;

class Admin
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }
}