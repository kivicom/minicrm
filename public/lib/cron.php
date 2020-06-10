<?php

require_once "../../DB/Connection.php";
$db = \DB\Connection::make();

$query = "UPDATE `appeal` SET `appeal_status` = 'Истек' WHERE `expired` < CURRENT_DATE()";
$query = $db->query($query);
