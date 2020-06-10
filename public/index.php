<?php
if( !session_id() ) @session_start();

require '../vendor/autoload.php';
require_once 'lib/sms.ru.php';

\App\Models\Router::getRouter();
