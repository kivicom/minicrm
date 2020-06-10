<?php

namespace App\Models;


use Aura\SqlQuery\QueryFactory;
use PDO;

class ApiConfig
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getAPIkey()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('api_sms')
            ->limit('1');
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $key_api = $sth->fetch(PDO::FETCH_ASSOC);
        return $key_api;
    }

    public function updateAPIkey($data)
    {
        $id = $data['id'];
        $update = $this->queryFactory->newUpdate();
        $update
            ->table('api_sms')
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());

        return $sth->execute($update->getBindValues());
    }
}