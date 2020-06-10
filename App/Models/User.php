<?php

namespace App\Models;

use Aura\SqlQuery\QueryFactory;
use PDO;
use Valitron\Validator;

class User
{
    private $pdo;
    private $queryFactory;

    public $attributes = [
        'username' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    public $rules = [];
    public $errors = [];

    public function __construct(\PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }
	
	public function getPhone($id, $table)
	{
		$select = $this->queryFactory->newSelect();
        $select->cols(['phone'])
            ->from($table)
            ->where("`id` = '{$id}'")
            ->limit(1);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());

        $phone = $sth->fetch(PDO::FETCH_ASSOC);

        return $phone;
	}

    public function load($data){
        foreach($this->attributes as $name => $value){
            if(isset($data[$name])){
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function Validate($data, $rules){
        $v = new Validator($data);
        $v->rules($rules);
        if($v->validate()){
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    public function getErrors()
    {
        foreach ($this->errors as $key => $error) {
            foreach ($error as $item) {
                $errors = $item;
            }
            $_SESSION['error'][$key] = $errors;
        }
    }

    public function getOne($id)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])
            ->from('users')
            ->where("`id` = {$id}")
            ->limit(1);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $user = $sth->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public function getAll()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])->from('users');
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $users = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public function getUpdate($table, $data)
    {
        $update = $this->queryFactory->newUpdate();
        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $data['id']);
        $sth = $this->pdo->prepare($update->getStatement());
        $res = $sth->execute($update->getBindValues());
        return $res;
    }
	
	public function getDelete($table, $data)
    {
        $delete = $this->queryFactory->newDelete();
        $delete->from($table)
        ->where('id = :id')
        ->bindValue('id', $data);
        $sth = $this->pdo->prepare($delete->getStatement());
        return $sth->execute($delete->getBindValues());
    }
}