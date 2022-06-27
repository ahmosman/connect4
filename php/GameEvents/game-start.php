<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';

use App\Game;
use App\Player;

$output = "";

$player = Player::create();
$opponent = Player::create();
$player->setOpponentId($opponent->playerId);
$opponent->setOpponentId($player->playerId);
$game = Game::create($player, $opponent);
//$uniqueGameId = 111111;
$_SESSION['player_id'] = $player->playerId;
echo "success";

