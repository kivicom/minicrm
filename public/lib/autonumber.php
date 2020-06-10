<?php

$autonumber = file_get_contents('./protected/autonumber.txt');
$numbers = explode(PHP_EOL, $autonumber);
$numbers = json_encode($numbers);