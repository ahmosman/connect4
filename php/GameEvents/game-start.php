<?php
session_start();

use App\{Player, Game};

include_once "../App/Game.php";
include_once "../App/Player.php";

$output = "";

$player = Player::create();
$opponent = Player::create();
$player->setOpponentId($opponent->playerId);
$opponent->setOpponentId($player->playerId);
$game = Game::create($player, $opponent);
//$uniqueGameId = 111111;
$_SESSION['player_id'] = $player->playerId;
echo "success";

