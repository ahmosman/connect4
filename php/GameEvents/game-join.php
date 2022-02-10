<?php

use App\Game;

include_once "../App/Game.php";
session_start();
$uniqueGameId = $_POST['unique_game_id'];
$result = Game::join($uniqueGameId);
if ($result['response'] == 'success') {
    $_SESSION['player_id'] = $result['playerToJoinId'];
}
echo $result['response'];