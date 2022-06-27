<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
use App\Gameplay;

if (isset($_SESSION['player_id'])) {
    $gameplay = new Gameplay($_SESSION['player_id']);
    if ($gameplay->isPlayersTurn) {
        $gameplay->putBall($_GET['col']);
        echo "$_GET[col]";
    }
}
