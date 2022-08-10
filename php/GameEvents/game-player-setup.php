<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';

use App\Player;
use App\Response;

if (!empty($_POST['nickname'])) {
    if ($_POST['player_color'] !== $_POST['opponent_color']) {
        if (isset($_SESSION['player_id'])) {
            $player = new Player($_SESSION['player_id']);
            $player->setNickname($_POST['nickname']);
            $player->setPlayerColor($_POST['player_color']);
            $player->setOpponentColor($_POST['opponent_color']);
            $player->setStatus('WAITING');
            Response::makeSuccessResponse();
        }
    } else {
        Response::makeErrorResponse('Kolory graczy muszą być różne!');
    }
} else
    Response::makeErrorResponse('Podaj poprawny nick!');
