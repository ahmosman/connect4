<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../cors.php';
use App\Player;
use App\Response;


if (isset($_SESSION['player_id'])) {
    $player = new Player($_SESSION['player_id']);
    $opponent = new Player($player->opponentId);

    Response::makeContentResponse(json_encode([
        'player' => $player->status,
        'opponent' => $opponent->status
    ]));
}