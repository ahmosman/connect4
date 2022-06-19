<?php

use App\Gameplay;

require_once "../App/Gameplay.php";
session_start();
if (isset($_SESSION['player_id'])) {
    $gameplay = new Gameplay($_SESSION['player_id']);
    if ($gameplay->isPlayersTurn) {
        $gameplay->putBall($_GET['col']);
        echo "$_GET[col]";
    }
}
