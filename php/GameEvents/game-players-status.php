<?php
session_start();

use App\Player;

require_once "../App/Player.php";

if (isset($_SESSION['player_id'])) {
    $player = new Player($_SESSION['player_id']);
    $opponent = new Player($player->opponentId);
    echo "{\"player\": \"$player->status\", \"opponent\": \"$opponent->status\"}";
}