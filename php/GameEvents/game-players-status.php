<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
use App\Player;


if (isset($_SESSION['player_id'])) {
    $player = new Player($_SESSION['player_id']);
    $opponent = new Player($player->opponentId);
    echo "{\"player\": \"$player->status\", \"opponent\": \"$opponent->status\"}";
}