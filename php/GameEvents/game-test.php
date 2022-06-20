<?php
session_start();

use App\Player;

include_once "../App/Player.php";

if (isset($_POST['test_player_id']) && !empty($_POST['test_player_id'])) {
    $player = new Player($_POST['test_player_id']);
    $_SESSION['player_id'] = $player->playerId;
    echo 'success';
}else{
    echo 'Nie podano id gracza';
}