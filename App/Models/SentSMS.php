<?php

namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class SentSMS
{
	private $pdo;
    private $queryFactory;
	
	public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }
	
	public function addSMS($table, $data)
    {
		$insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
		print_r($data); die();
		return $sth->execute($insert->getBindValues());
    }
}