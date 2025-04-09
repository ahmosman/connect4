<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../cors.php';
use App\Player;
use App\Response;

if (isset($_POST['test_player_id']) && !empty($_POST['test_player_id'])) {
    $player = new Player($_POST['test_player_id']);
    $_SESSION['player_id'] = $player->playerId;
    Response::makeSuccessResponse();
} else
    Response::makeErrorResponse('Nie podano ID gracza');