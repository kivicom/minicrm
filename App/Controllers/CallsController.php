<?php

namespace App\Controllers;

use App\Models\Calls;
use App\Models\History;
use Delight\Auth\Auth;
use App\Models\User;
use League\Plates\Engine;

class CallsController
{
    public $templates;
    private $auth;
    private $calls;
    private $history;

    public function __construct(Auth $auth, Engine $engine, Calls $calls, User $user, History $history)
    {
        $this->auth = $auth;
        $this->templates = $engine;
        $this->calls = $calls;
		$this->user = $user;
		$this->history = $history;
    }
	
	public function Calls()
	{
		$allcalls = $this->calls->getAll('calls');
		$isInHistory = $this->history->isInHistory('history_of_changes', $allcalls);
		$users = $this->user->getAll();
		
		$role = $this->auth->getRoles();
		if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }
		echo $this->templates->render('calls', ['auth' => $auth, 'allcalls' => $allcalls, 'isInHistory' => $isInHistory, 'users' => $users, 'role' => $role]);
	}
	
	public function iso8601($time=false) {
		if(!$time) $time=time();
		return date("Y-m-d", $time) . 'T' . date("H:i:s.u", $time) .'+03:00';
	}
	
	public function CallsPost()
	{
		$_POST["EventTime"] = $this->iso8601(time()+3600);
		$_POST["AN"] = str_replace('+', '', $_POST["AN"]);
		$_POST["UN"] = $_SESSION['user']['username'];
		$appeals = $this->calls->addCall('calls', $_POST);
		header("Location: /calls");
	}
	
	public function CallsEdit($id)
	{
		$currentCall = $this->calls->getOne('calls', $id);
		$users = $this->user->getAll();
		if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }
		echo $this->templates->render('calls.edit', ['auth' => $auth, 'currentCall' => $currentCall[0], 'users' => $users, 'me' => $this->auth]);
	}
	
	public function CallsEditPost($id)
	{
		$user = $this->user->getOne($_POST['Responsible']);
		$_POST['Responsible'] = $user ['username'];
		$this->calls->editCall('calls', $id, $_POST);
		$this->history->addHistory('history_of_changes', $_POST);
		header("Location: /calls");
	}
	
	public function OldDelete()
	{
		$arrIDs = $this->calls->getOldCalls('calls');
		
		$ids = implode(',', $arrIDs);
		$idInHistory = $this->history->idInHistory('history_of_changes',$ids);
		$idsResult = array_diff($arrIDs, $idInHistory);
		$idsResult = implode(',', $idsResult);
		$this->calls->deleteCalls('calls', $idsResult);
	}
	
	public function CallDelete($id)
	{
		if($this->calls->deleteCalls('calls', $id)){
			$msg = "Обращение № $id успешно удалено";
			echo $msg;
		}
	}

}