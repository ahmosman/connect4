<?php
include_once "Player.php";
session_start();
if(isset($_POST['test_player_id']))
{
    $player = new Player($_POST['test_player_id']);
    $_SESSION['unique_game_id'] = $player->getUniqueGameId();
    $_SESSION['player_id'] = $player->playerId;
    echo 'success';
}