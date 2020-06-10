<?php

namespace App\Controllers;

use App\Models\Promocode;
use DateTime;
use Delight\Auth\Auth;
use League\Plates\Engine;

class PromocodeController
{
    private $promocode;
    private $auth;
    private $templates;
    private $activate;
    private $msg;

    public function __construct(Promocode $promocode, Engine $engine, Auth $auth)
    {
        $this->promocode = $promocode;
        $this->templates = $engine;
        $this->auth = $auth;

        if ($this->auth->isLoggedIn()) {
            $this->auth = true;
        }else {
            $this->auth = false;
        }
    }

    public function getCheckPromocod()
    {
        $appeal = $this->getPromocode();
        $url = '';

        if ($appeal['appeal_status'] === 'Использован'){

            $this->msg = "Промокод был использован ранее";
            $this->activate = false;
        }elseif (trim($appeal['appeal_status']) === 'Истек'){
            $this->msg = "Срок действия промокода истек.<br>Свяжитесь со службой поддержки. +79883124401";
            $this->activate = false;
        }elseif (trim($appeal['appeal_status']) === 'Не использован'){
            $this->msg = "Убедитесь, что автомат свободен и Вы находитесь возле него! <br>Промокод уникальный и его можно использовать только один раз! <br> Время появления кредита на экране до 2 минут, после зачисления делайте выбор напитка без оплаты";
            $this->activate = true;
            $url = $this->promocode->getUrl($appeal['num_automat']);
        }else{
            $this->msg = "Промокод не найден. При необходимости свяжитесь с технической поддержкой. +79883124401";
            $this->activate = false;
        }

        echo $this->templates->render('promocode',['auth' => $this->auth, 'msg' => $this->msg, 'activate' => $this->activate, 'appeal' => $appeal, 'url' => $url]);
    }

    public function getPromocode()
    {
        $url = $_SERVER['REQUEST_URI'];
        $rand_gen = explode('/', $url);
        $rand_gen = array_pop($rand_gen);
        $rand_gen = $this->promocode->getOne('appeal', $rand_gen);

        return $rand_gen;
    }

    public function createFile()
    {
        $data = $_POST;
        $date = new DateTime( "NOW" );
        $filename =  $date->format( "Ydm_Hisv" );
        $usedDate = $date->format( "Y-m-d H:i:s" );

        if(!$this->promocode->createFile($filename, $data, $usedDate)){
            echo "Не могу произвести запись в файл ($filename)";
            exit;
        }
        echo "<h1>Запрос на начисление кредита принят к исполнению.</h1><p>Ваш запрос принят к исполнению. Подробнее о его состоянии можно узнать <a href='#'>здесь</a></p>";
    }

}