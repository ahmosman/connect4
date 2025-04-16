<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../cors.php';

use App\Game;
use App\GameState;
use App\Response;

if (!empty($_POST['unique_game_id'])) {
    $uniqueGameId = htmlspecialchars($_POST['unique_game_id'], ENT_QUOTES, 'UTF-8');
    $joinResult = Game::join($uniqueGameId);
    if ($joinResult['response'] == 'success') {
        $_SESSION['player_id'] = $joinResult['playerToJoinId'];
        GameState::update();
        Response::makeSuccessResponse();
    } else
        Response::makeErrorResponse($joinResult['response']);
}