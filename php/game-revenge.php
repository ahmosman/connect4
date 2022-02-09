<?php
require_once "Player.php";
session_start();
if (isset($_SESSION['unique_game_id']) && isset($_SESSION['player_id'])) {
    $me = new Player($_SESSION['player_id']);
    $me->setStatus('REVENGE');
    echo 'success';

}