<?php
include_once "Game.php";
include_once "Player.php";
session_start();
$output = "";

$player = Player::create();
$opponent = Player::create();
$player->setOpponentId($opponent->playerId);
$opponent->setOpponentId($player->playerId);
$game = Game::create($player, $opponent);
//$uniqueGameId = 111111;

$_SESSION['unique_game_id'] = $game->getUniqueGameId();
$_SESSION['player_id'] = $player->playerId;

