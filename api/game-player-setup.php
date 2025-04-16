<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../cors.php';

use App\GameState;
use App\Player;
use App\Response;

function isValidHexColor($color)
{
    return preg_match('/^#[a-fA-F0-9]{6}$/', $color);
}

if (empty($_POST['nickname']) || empty($_POST['player_color']) || empty($_POST['opponent_color'])) {
    Response::makeErrorResponse('All fields are required!');
    exit;
}

$nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');
$playerColor = $_POST['player_color'];
$opponentColor = $_POST['opponent_color'];

if (!isValidHexColor($playerColor) || !isValidHexColor($opponentColor)) {
    Response::makeErrorResponse('Invalid color format!');
    exit;
}

if ($playerColor === $opponentColor) {
    Response::makeErrorResponse('Player and opponent colors must be different!');
    exit;
}

if (isset($_SESSION['player_id'])) {
    $player = new Player($_SESSION['player_id']);
    $player->setNickname($nickname);
    $player->setPlayerColor($playerColor);
    $player->setOpponentColor($opponentColor);
    $player->setStatus('WAITING');
    GameState::update();
    Response::makeSuccessResponse();
} else {
    Response::makeErrorResponse('Player not logged in!');
}
