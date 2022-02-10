<?php

use App\Player;

require_once "../App/Player.php";
session_start();
if (isset($_SESSION['player_id'])) {
    $me = new Player($_SESSION['player_id']);
    $me->setStatus('READY');
    echo 'success';
}