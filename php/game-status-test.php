<?php
include_once "Game.php";
include_once "Player.php";
session_start();
if (isset($_SESSION['unique_game_id']) && isset($_SESSION['player_id'])) {
    $json = ['content' => '', 'scripts' => []];
    $game = new Game($_SESSION['unique_game_id']);
    $me = new Player($_SESSION['player_id']);
//    $game = new Game($_SESSION['unique_game_id']);
//    $me = new Player($_SESSION['player_id']);
    $opponent = new Player($me->opponentId);

    if ($me->status == 'WAITING' && $opponent->status == 'WAITING') {
        $game->setPlayersStatus('CONFIRMING');
    } elseif ($me->status == 'READY' && $opponent->status == 'READY') {
        $game->setPlayersStatus('PLAYING');
    } elseif ($me->status == 'REVENGE' && $opponent->status == 'REVENGE') {
        $game->setPlayersStatus('CONFIRMING');
    }

    if ($me->status == 'WAITING') {
        $json['content'] .=
            '<div class="game-id-div"><h1>Twoja gra: <span>' . $game->getUniqueGameId() . '</span></h1></div>
            <h2>Oczekiwanie na przeciwnika</h2>
            <div class="loader"></div>';

    } elseif ($me->status == 'CONFIRMING') {
        $json['content'] .=
            "<h2>$opponent->nickname czeka na Ciebie!</h2>
             <h2>Gotowy?</h2>";
        $json['content'] .= include '../templates/confirmBtn.php';
        $json['content'] .= include '../templates/backBtn.php';
        $json['scripts'][] = 'game-confirm.js';
    } elseif ($me->status == 'READY') {
        $json['content'] .=
            '<h2>Oczekiwanie na potwierdzenie przez przeciwnika</h2>
            <div class="loader"></div>';
    } elseif ($me->status == 'PLAYING') {
        $json['content'] .=
            '<h2>GRAMY</h2>';
    } elseif ($me->status == 'REVENGE') {
        $json['content'] .=
            '<h2>Oczekiwanie na potwierdzenie rewanżu</h2>
            <div class="loader"></div>';
        $json['content'] .= include '../templates/backBtn.php';

    } elseif ($opponent->status == 'DISCONNECTED') {
        $json['content'] .=
            '<h2>Przeciwnik rozłączył się</h2>'
            . include '../templates/backBtn.php';
    } elseif ($me->status == 'WIN' || $me->status == 'LOSE') {
        $json['content'] .= match ($me->status) {
            'WIN' => '<h2>WYGRAłEś</h2>',
            'LOSE' => '<h2>PRZEGRAłEś</h2>',
        };
        if ($opponent->status == 'REVENGE') {
            $json['content'] .=
                '<div class="game-info-div">Przeciwnik chce rewanżu!</div>';
        }
        $json['content'] .= include '../templates/backBtn.php';
        $json['content'] .= include '../templates/revengeBtn.php';

    }

    echo json_encode($json);
}

//TODO: Dodaj i wyświetlaj licznik wygranych gier