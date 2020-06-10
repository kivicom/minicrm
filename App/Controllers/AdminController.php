<?php

namespace App\Controllers;


use App\Models\Admin;
use App\Models\ApiConfig;
use App\Models\User;
use Delight\Auth\Auth;
use League\Plates\Engine;

class AdminController
{
    public $templates;
    private $auth;
	private $user;
    private $apiConfig;

    public function __construct(Auth $auth, Admin $objAdmin, Engine $engine, ApiConfig $apiConfig, User $user)
    {
        $this->auth = $auth;
		$this->user = $user;
        $this->db = $objAdmin;
        $this->apiConfig = $apiConfig;
        $this->templates = $engine;
    }

    public function Index()
    {
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }
		$role = $this->auth->getRoles();
		$users = $this->user->getAll();
        echo $this->templates->render('admin/main',['auth' => $auth, 'users' => $users, 'role' => $role]);
    }

    public function cnfApi()
    {
        if($_POST){
            $this->apiConfig->updateAPIkey($_POST);
            header('Location: /admin/cnfapi');
            exit();
        }
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }

        $api_key = $this->apiConfig->getAPIkey();

        echo $this->templates->render('admin/cnfapi',['auth' => $auth, 'api_key' => $api_key]);
    }
	
	public function getProfile($userId)
    {
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }

        $user = $this->user->getOne($userId);
		//print_r($user);
        echo $this->templates->render('admin/profile', ['auth' => $auth, 'user' => $user]);
    }

    public function ProfileUpdate()
    {
        $data = $_POST;
        $this->user->getUpdate('users', $data);
		
        return header('Location: /admin');
    }
	
	/* 
	*
	* If a user is currently logged in, they may change their password.
	*/
	public function ChangeCurentUserPassword()
	{
		try {
			$this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);
			flash()->success('Пароль был успешно изменен');
		}
		catch (\Delight\Auth\NotLoggedInException $e) {
			flash()->error('Not logged in');
			//die('Not logged in');
		}
		catch (\Delight\Auth\InvalidPasswordException $e) {
			flash()->error('Незаполненное поле или не верный пароль');
			//die('Invalid password(s)');
		}
		catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error('Слишком много запросов');
			//die('Too many requests');
		}
		return header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
	
	public function ProfileDelete()
    {
        $data = $_POST;
        $this->user->getDelete('users', $data['id']);
        return header('Location: /admin');
    }
}