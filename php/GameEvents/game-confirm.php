<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';

use App\Player;
use App\Response;


if (isset($_SESSION['player_id'])) {
    $me = new Player($_SESSION['player_id']);
    $me->setStatus('READY');
    Response::makeSuccessResponse();
}