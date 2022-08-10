<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';

use App\Game;
use App\Player;
use App\Response;

$output = "";

$player = Player::create();
$opponent = Player::create();
$player->setOpponentId($opponent->playerId);
$opponent->setOpponentId($player->playerId);
$game = Game::create($player, $opponent);
$_SESSION['player_id'] = $player->playerId;
Response::makeSuccessResponse();

