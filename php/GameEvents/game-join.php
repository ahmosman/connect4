<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';

use App\Game;
use App\Response;

if (!empty($_POST['unique_game_id'])) {
    $uniqueGameId = $_POST['unique_game_id'];
    $joinResult = Game::join($uniqueGameId);
    if ($joinResult['response'] == 'success') {
        $_SESSION['player_id'] = $joinResult['playerToJoinId'];
        Response::makeSuccessResponse();
    } else
        Response::makeErrorResponse($joinResult['response']);
}