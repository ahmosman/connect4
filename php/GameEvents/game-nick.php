<?php

use App\Player;

include_once "../App/Player.php";
session_start();
$output = "";
if (!empty($_POST['nickname'])) {
    if (isset($_SESSION['player_id'])) {
        $player = new Player($_SESSION['player_id']);
        $player->setNickname($_POST['nickname']);
        $player->setStatus('WAITING');
        $output = 'success';
    }
} else {
    $output = "Podaj poprawny nick!";
}
echo $output;