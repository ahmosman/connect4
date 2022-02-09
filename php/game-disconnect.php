<?php
require_once "Player.php";
session_start();
if (isset($_SESSION['unique_game_id']) && isset($_SESSION['player_id'])) {
    $me = new Player($_SESSION['player_id']);
    $me->setStatus('DISCONNECTED');
    session_unset();
    session_destroy();
    echo 'success';

}