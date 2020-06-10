<?php

namespace App\Controllers;

use App\Models\Appeal;
use App\Models\Calls;
use Delight\Auth\Auth;
use League\Plates\Engine;

class MainController
{
    public $templates;
    private $auth;
    private $appeal;
    private $gen_key;
    private $calls;

    public function __construct(Auth $auth, Engine $engine, Appeal $appeal, Calls $calls)
    {
        $this->auth = $auth;
        $this->templates = $engine;
        $this->appeal = $appeal;
        $this->calls = $calls;
        $gen_key = $this->appeal::RandomString(12);
        $this->gen_key = $gen_key;
    }
	
	public function cEvent()
	{
		file_put_contents('1.txt', file_get_contents('php://input'));
		
		//file_put_contents('1.txt', file_get_contents('php://input'), FILE_APPEND);
		$inpjsn = file_get_contents('php://input');
		//$inpjsn = file_get_contents('0.txt');
		$inpjsn = json_decode($inpjsn, true);
		$inpjsn['AN'] = '7' . $inpjsn['AN'];
		if(($inpjsn['EventType'] == 0) || ($inpjsn['EventType'] == 2)){
			$appeals = $this->calls->addCall('calls', $inpjsn);
		}
	}

    public function Index()
    {
        $data = '';
        if(isset($_GET)){
            $data = $_GET;
        }
        
		if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }

		$appeals = $this->appeal->getAll('appeal', $data);
		
        echo $this->templates->render('main', ['auth' => $auth, 'appeals' => $appeals, 'gen_key' => $this->gen_key]);
    }
	
}