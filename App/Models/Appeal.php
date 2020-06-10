<?php

namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Appeal
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getAll($table, $data)
    {
        $order_By = isset($data['orderby']) ? $data['orderby'] : 'created_at';
        $direction = isset($data['dir']) ? $data['dir'] : 'DESC';
        $orderBy = "`$table`.`$order_By` $direction";

        if(isset($data['orderby'])){
            $order_By = $data['orderby'];
            $direction = $data['dir'];
            if($data['orderby'] === 'username'){
                $orderBy = "`users`.`$order_By` $direction";
            }
        }



        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*',
            'appeal.id AS a_id'
        ])
            ->from($table)
            ->join('LEFT', 'users', "`users`.id = `{$table}`.`user_id`")
            ->orderBy([$orderBy]);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $appeals = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $appeals;
    }

    public function addAppeal($table, $data)
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }

    public function showAppeal($table, $id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from($table)
            ->where("`id` = {$id}")
            ->limit(1);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $appeal = $sth->fetch(PDO::FETCH_ASSOC);
        return $appeal;
    }

    public function editAppeal($table, $id, $data)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());

        return $sth->execute($update->getBindValues());
    }

    public function deleteAppeal($table, $id)
    {
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($delete->getStatement());

        return $sth->execute($delete->getBindValues());
    }


    public static function RandomString($length) {
        $keys = array_merge(range(0,9), range('a', 'z'));

        $key = "";
        for($i=0; $i < $length; $i++) {
            $key .= $keys[mt_rand(0, count($keys) - 1)];
        }
        return $key;
    }
}