<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\ApiConfig;
use App\Models\SentSMS;
use SMSRU;
use stdClass;

class SmsController
{
	public function __construct(User $user, ApiConfig $apiConfig, SentSMS $SentSMS)
    {
		$this->user = $user;
		$this->apiConfig = $apiConfig;
		$this->SentSMS = $SentSMS;
    }
	
	public function SendSMS($phone, $text)
    {
        $api_key = $this->apiConfig->getAPIkey();

        $smsru = new SMSRU($api_key['key_api']); // Ваш уникальный программный ключ, который можно получить на главной странице

        $data = new stdClass();
        $data->to = $phone;
        $data->text = $text; // Текст сообщения
		//$data->test = 1;
        $sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную
		//$status = $smsru->getStatus($sms->sms_id);
		//print_r($data);
		if($sms->status == 'OK'){
			echo "СМС было отправлено";
			return true;
		}
		if($sms->status == 'ERROR'){
			echo $sms->status_text;
		}
    }
	
	/* 
	* Ниже шаблоны смс для отправки.
	*
	*/
	
	public function PhotoConfirmation($phone)
	{
		$text = 'Для компенсации любезно просим Вас отправить фото подтверждение проблемы на https://api.whatsapp.com/send?phone=79883124400';
		$this->SendSMS($phone, $text);
	}
	
	public function CostRecovery($phone)
	{
		$sum = $_GET['sum'];
		$text = 'Вам начислена компенсация в размере ' . $sum .' руб. Приносим извинения';
		$this->SendSMS($phone, $text);
	}
	
	public function ResponsiblePerson($id)
	{
		$user = $this->user->getPhone($id, 'users');
		$text = 'Текст для ответственного';
		$this->SendSMS($user['phone'], $text);
	}
	
	public function TaskСompletion($phone)
	{
		$text = 'Текст клиенту о завершении задачи';
		$this->SendSMS($phone, $text);
	}
}