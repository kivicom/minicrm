<?php

namespace App\Controllers;


use App\Models\ApiConfig;
use App\Models\Appeal;
use App\Models\Promocode;
use App\Models\User;
use Delight\Auth\Auth;
use League\Plates\Engine;
use SimpleMail;
use SMSRU;
use stdClass;

class AppealController
{
    private $appeal;
    private $templates;
    private $auth;
    private $user;
    private $apiConfig;
    private $promocode;

    public function __construct(User $user, Appeal $appeal, Engine $engine, Auth $auth, ApiConfig $apiConfig, Promocode $promocode)
    {
        $this->appeal = $appeal;
        $this->templates = $engine;
        $this->auth = $auth;
        $this->user = $user;
        $this->apiConfig = $apiConfig;
        $this->promocode = $promocode;
    }

    public function addAppeal()
    {
        if(!empty($_POST)) {
            $expired = $_POST['expired'];
            $date = strtotime("+{$expired} day", time());
            $_POST['expired'] = date('Y-m-d H:i:s', $date);
            $query = $this->appeal->addAppeal('appeal', $_POST);

            if($query){
                flash()->success('Обращение успешно добавлено!');
            }else{
                flash()->error('Ошибка. Обращение не добавлено');
            }
            header('Location: /');
            $this->SendAfterRegister('729100@gmail.com', $_POST, $selector = null, $token = null);
            $this->SendSMS($_POST);
            exit;
        }else {
            if (empty($_POST)) {
                flash()->error('Ошибка. Некоторые поля не заполнены');
            }
            if (empty($_POST['notice'])) {
                flash()->error('Ошибка. Поле причина не заполненно');
            }
            header('Location: /');
        }
    }

    public function manageAppeal($id)
    {
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
            header('Location: /auth');
            exit();
        }

        $appeal = $this->appeal->showAppeal('appeal',$id);

        echo $this->templates->render('item', ['auth' => $auth, 'appeal' => $appeal]);
    }

    public function editAppeal($id){
        if(!empty($_POST)){

            if(is_numeric($_POST['expired'])){
                $expired = $_POST['expired'];
                $date = strtotime("+{$expired} day", time());
                $_POST['expired'] = date('Y-m-d H:i:s', $date);
            }

            if (strtotime($_POST['expired']) > strtotime('NOW')){
                $_POST['appeal_status'] = "Не использован";
            }
            if (strtotime('NOW') > strtotime($_POST['expired'])){
                $_POST['appeal_status'] = "Истек";
            }

            $res = $this->appeal->editAppeal('appeal', $id, $_POST);
            if($res){
                flash()->success('Обращение успешно обновленно!');
            }
            header('Location: /');
            exit();
        }
    }

    public function deleteAppeal($id){
        /*$filename = $this->promocode->getFileName('appeal', $id);
        $this->promocode->deleteFile($filename);*/
        $res = $this->appeal->deleteAppeal('appeal', $id);

        if($res){
            flash()->success('Обращение удалено!');
        }
        header('Location: /');
        exit();
    }

    public function SendAfterRegister($email, $data, $selector, $token)
    {
        $user = $this->user->getOne($data['user_id']);
        $subject = "Создан промокод на сумму {$data['sum_promo']}";
        $body = "Автор создания {$user['username']},<br>\r\n Номер тел. клиента {$data['phone_of_client']},<br>\r\n Сумма {$data['sum_promo']} руб.,<br>\r\n Причина -  {$data['notice']}";
        SimpleMail::make()
            ->setTo($email, $user['username'])
            ->setFrom("info@chaocacaokrd.ru", "Создан промокод на сумму {$data['sum_promo']}")
            ->setSubject($subject)
            ->setMessage($body)
            ->setHtml()
            ->send();
    }

    public function SendSMS($post)
    {
        $api_key = $this->apiConfig->getAPIkey();
        $smsru = new SMSRU($api_key['key_api']); // Ваш уникальный программный ключ, который можно получить на главной странице

        $data = new stdClass();
        $data->to = $post['phone_of_client'];
        $data->text = "Вам доступна скидка в размере '{$post['sum_promo']}' рублей в автомате {$post['num_automat']}.<br>\r\n
Срок действия – до '{$post['expired']}'. <br>\r\nДля активации – пройдите по ссылке ниже, когда будете возле автомата и он будет 
        cвободен. Делайте выбор товара без оплаты, если сумма выбора, меньше промокода.<br>\r\n
        Если больше – можно доплатить картой или наличными<br>\r\n
        <a href='http://chaocacaokrd.ru/promocode/{$post['rand_gen']}'>http://chaocacaokrd.ru/promocode/{$post['rand_gen']}</a>"; // Текст сообщения
// $data->from = ''; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
// $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
// $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
//$data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
// $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему
        $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

        if ($sms->status == "OK") { // Запрос выполнен успешно
            echo "Сообщение отправлено успешно. ";
            echo "ID сообщения: $sms->sms_id. ";
            echo "Ваш новый баланс: $sms->balance";
        } else {
            echo "Сообщение не отправлено. ";
            echo "Код ошибки: $sms->status_code. ";
            echo "Текст ошибки: $sms->status_text.";
        }
    }

    public function SendSMSReplay()
    {
        return $this->SendSMS($_POST);
    }


}