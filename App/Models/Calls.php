<?php

namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;

class Calls
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }
	
	public function getDateCalls($table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols([
            'EventTime'
        ])->from($table)->where("`EventTime`");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $EventTimes = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		/* $EventTime = [];
		foreach ($EventTimes as $item){
			$EventTime[] = date('d.m.Y', strtotime($item['EventTime']));
		} */
		//print_r($EventTime);die();
        return $EventTimes;
    }
	
	public function getOldCalls($table)
    {
		$callsID = [];
        $select = $this->queryFactory->newSelect();
        $select->cols([
            'id', 'EventTime'
        ])->from($table)->where("`EventTime`");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
		
        $calls = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($calls as $item){
			if (strtotime(date('d.m.Y', strtotime($item['EventTime']))) < strtotime("now - 2day")){
				$callsID[] = $item['id'];
			}
		}
        return $callsID;
    }
	
    public function getAll($table)
    {
		if (isset($_GET['published']) && ($_GET['published'] == 'all')){
			$published = 1;
		}else{
			$published = '`published` = 1';
		}
		$select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])
		->from($table)->where("(`EventType` = 0 OR `EventType` = 2)")
		->where($published);
        $sth = $this->pdo->prepare($select->getStatement());
		//print_r($sth);
        $sth->execute($select->getBindValues());
        $calls = $sth->fetchAll(PDO::FETCH_ASSOC);
		
        return $calls;
    }
	
	
	public function addCall($table, $data)
    {
		//print_r($data);
		//$data['AN'] = '7' . $data['AN'] ;
		$data['AN'] = preg_replace("/[^0-9]+/ ", "", $data['AN']);
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
        return $sth->execute($insert->getBindValues());
    }
	
	public function getOne($table, $id)
	{
		$select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where("`id` = {$id}");
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $calls = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $calls;
	}
	
	public function editCall($table, $id, $data)
    {
		unset($data['call_id']);
		if ($data['isDone'] == 1){
			$data['completed'] = date('Y-m-d H:i:s');
		}else{
			$data['completed'] = "";
		}
		
		if ($data['isDone'] == 1){
			$data['published'] = 0;
		}else{
			$data['published'] = 1;
		}
		
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());
		//print_r($data);die();
        return $sth->execute($update->getBindValues());
    }
	
	public function deleteCalls($table, $ids)
	{
		$delete = $this->queryFactory->newDelete();
		$delete
			->from($table)
			->where("`id` IN ($ids)");
		$sth = $this->pdo->prepare($delete->getStatement());
		return $sth->execute($delete->getBindValues());
	}
}