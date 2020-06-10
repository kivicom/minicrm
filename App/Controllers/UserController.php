<?php

namespace App\Controllers;


use App\Models\User;
use Delight\Auth\Auth;
use League\Plates\Engine;
use SimpleMail;

class UserController
{
    public $templates;
    public $user;
    private $auth;

    public function __construct(Engine $engine, Auth $auth, User $user)
    {
        $this->templates = $engine;
        $this->auth = $auth;
        $this->user = $user;
    }

    public function Login()
    {
        if($_POST){
            if (isset($_POST['remember']) == 1) {
                $rememberDuration = (int) (60 * 60 * 24 * 365.25);
            }
            else {
                $rememberDuration = null;
            }

            try {
                $data = $_POST;
                $this->user->load($data);
                $key = array_keys($data);
                $rules = [
                    'required' => $key,
                    'lengthMin' => [
                        ['password', 6],
                    ]
                ];
                if(!$this->user->Validate($data, $rules)){
                    $this->user->getErrors();
                }
                $this->auth->loginWithUsername($_POST['username'], $_POST['password'], $rememberDuration);
                foreach ($_POST as $key => $item) {
                    if($key != 'password') $_SESSION['user'][$key] = $item;
                }

                $_SESSION['user']['id'] .= $this->auth->getUserId();
                $_SESSION['user']['name'] .= $this->auth->getUsername();

                flash()->success('Вы успешно авторизовались');
                header('Location: /auth');
                die(); 
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                $_SESSION['error']['password'] = 'Неверный пароль';
            }
            catch (\Delight\Auth\UnknownUsernameException $e) {
                flash()->error('Неизвестный пользователь');
                $_SESSION['error']['password'] = 'Неверный пароль';
            }
            catch (\Delight\Auth\EmailNotVerifiedException $e) {
                flash()->error('Вы не подтвердили регистрацию через письмо отправленное на Email');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Слишком много попыток');
            }
        }

        if ($this->auth->isLoggedIn()) {
            header('Location: /calls');
        }else {
            $auth = false;
        }

        echo $this->templates->render('auth', ['auth' => $auth]);
    }

    public function Register()
    {
        if($_POST){
            $data = $_POST;
            $this->user->load($data);
            $key = array_keys($data);
            $rules = [
                'required' => $key,
                'email' => [
                    ['email'],
                ],
                'lengthMin' => [
                    ['password', 6],
                ],
                'equals' => [
                    ['password','password_confirmation'],
                ]
            ];

            if(!$this->user->Validate($data, $rules)){
                $this->user->getErrors();
            }else{
                try { 
                    $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                        $this->SendAfterRegister($_POST['email'], $_POST['username'], $selector, $token);
                    });

                    if($userId){
                        flash()->success('Вы успешно зарегистрировались. Теперь можете авторизоваться.');
                        echo header('Location: /auth');
                        exit();
                    }else{
                        flash()->error('Ошибка решистрации. Пожалуйста, повторите позже.');
                        echo header('Refresh:0');
                        exit();
                    }
                }
                catch (\Delight\Auth\InvalidEmailException $e) {
                    //flash()->error('Invalid email address');
                }
                catch (\Delight\Auth\InvalidPasswordException $e) {
                    //flash()->error('Invalid password');
                }
                catch (\Delight\Auth\UserAlreadyExistsException $e) {
                    flash()->error('User already exists');
                }
                catch (\Delight\Auth\TooManyRequestsException $e) {
                    flash()->error('Слишком много попыток');
                }
            }
            header('Refresh:0');
            die;
        }
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
        }
        echo $this->templates->render('admin/register', ['auth' => $auth]);
    }

    public function SendAfterRegister($email, $username, $selector, $token)
    {
        $subject = 'Информация с сайта http://chaocacaokrd.ru/';
        $message = '<a href="http://chaocacaokrd.ru/verification?selector='. \urlencode($selector) . '&token='.\urlencode($token).'">Подвердить регистрацию</a>';
        SimpleMail::make()
            ->setTo($email, $username)
            ->setFrom('chaocacaokrd@gmail.com', 'Arkadii')
            ->setSubject($subject)
            ->setMessage($message)
            ->setHtml()
            ->send();
    }

    public function verify_email()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
            //$this->auth->confirmEmail('RNB3u3xVs_l0lk6H', '67_ZmPcA5REFtl9_');

            flash()->success('Email address has been verified');
            header('Location: /');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email уже существует');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Слишком много попыток');
        }
    }
	
	public function Logout()
    {
        $this->auth->logOut();
        unset($_SESSION['user']);
        return header('Location: /auth');
    }

}