<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
use App\Player;


$output = "";
if (!empty($_POST['nickname'])) {
    if ($_POST['player_color'] !== $_POST['opponent_color']) {
        if (isset($_SESSION['player_id'])) {
            $player = new Player($_SESSION['player_id']);
            $player->setNickname($_POST['nickname']);
            $player->setPlayerColor($_POST['player_color']);
            $player->setOpponentColor($_POST['opponent_color']);
            $player->setStatus('WAITING');
            $output = 'success';
        }
    } else {
        $output = "Kolory graczy muszą być różne!";
    }
} else {
    $output = "Podaj poprawny nick!";
}
echo $output;