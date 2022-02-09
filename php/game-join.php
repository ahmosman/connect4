<?php
include_once "Game.php";
session_start();
$uniqueGameId = $_POST['unique_game_id'];
if($uniqueGameId > 0)
{
    $game = new Game($uniqueGameId);
    if($game->gameExists())
    {
        if($game->isJoinable())
        {
            echo "success";
            $_SESSION['unique_game_id'] = $uniqueGameId;
            $_SESSION['player_id'] = $game->getOpponentId();
        }else{
            echo "Ktoś inny już dołączył do gry";
        }
    }else{
        echo "Brak gry o podanym ID";
    }
}else{
    echo "Brak gry o podanym ID";
}