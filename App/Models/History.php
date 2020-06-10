<?php

namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class History
{
	private $pdo;
    private $queryFactory;
	
	public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }
	
	public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where("`modified` != ''");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $calls = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $calls;
    }
	
	public function getIDs($table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols([
            'call_id'
        ])->from($table)->where("`modified` != ''");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $calls = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $calls;
    }
	
	public function getOne($table, $AN)
	{
		$select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where("`AN` = {$AN}");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $history = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $history;
	}
	
	public function addHistory($table, $data)
    {
		$data['modified'] = date('Y-m-d H:i:s');
		if ($data['isDone'] == 1){
			$data['completed'] = date('Y-m-d H:i:s');
		}else{
			$data['completed'] = "";
		}
		
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
		
        return $sth->execute($insert->getBindValues());
    }
	
	public function isInHistory($table, $calls)
	{
		$ANs = [];
		foreach($calls as $call){
			$ANs[] = $call['AN'];
		}
		$ANs = implode(',',$ANs);
		$select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where("`AN` IN ($ANs)");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $calls = $sth->fetchAll(PDO::FETCH_COLUMN, 3);
        return $calls;
	}
	
	public function idInHistory($table, $ids)
	{
		$select = $this->queryFactory->newSelect();
        $select->cols([
            '`call_id`'
        ])->from($table)->where("`call_id` IN ($ids)");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $calls = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $calls;
	}
	
	public function deleteHistory($table, $call_id)
    {
		$delete = $this->queryFactory->newDelete();
        $delete
			->from($table)
			->where('call_id = :call_id')
            ->bindValue('call_id', $call_id);
			
        $sth = $this->pdo->prepare($delete->getStatement());
        return $sth->execute($delete->getBindValues());
    }
}