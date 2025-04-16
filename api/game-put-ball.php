<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../cors.php';
use App\Gameplay;
use App\GameState;
use App\Response;

if (isset($_SESSION['player_id'])) {
    $gameplay = new Gameplay($_SESSION['player_id']);
    if ($gameplay->isPlayerTurn) {
        $gameplay->putBall(htmlspecialchars($_POST['column']));
        GameState::update();
        Response::makeSuccessResponse();
    }else
        Response::makeErrorResponse('');
}
