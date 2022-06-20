<?php
session_start();

use App\Player;

require_once "../App/Player.php";

if (isset($_SESSION['player_id'])) {
    $me = new Player($_SESSION['player_id']);
    $me->setStatus('REVENGE');
    echo 'success';
}