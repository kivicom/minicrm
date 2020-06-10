<?php

namespace App\Controllers;

use App\Models\History;
use Delight\Auth\Auth;
use App\Models\User;
use League\Plates\Engine;

class HistoryController
{
    public $templates;
    private $auth;
	
	public function __construct(Auth $auth, Engine $engine, User $user, History $history)
    {
        $this->auth = $auth;
        $this->templates = $engine;
        $this->history = $history;
		$this->user = $user;
		if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }
    }
	
	public function getAll()
	{
		$allhistory = $this->history->getAll('history_of_changes');
		$users = $this->user->getAll();
		echo $this->templates->render('history', ['auth' => $this->auth->isLoggedIn(), 'allhistory' => $allhistory, 'users' => $users]);
	}
	
	public function getOne($AN)
	{
		$currentAN = $this->history->getOne('history_of_changes', $AN);
		$users = $this->user->getAll();
		echo $this->templates->render('history.one', ['auth' => $this->auth->isLoggedIn(), 'currentAN' => $currentAN, 'users' => $users, 'me' => $this->auth]);
	}
}