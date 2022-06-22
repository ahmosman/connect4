<?php
session_start();

include_once "../App/Gameplay.php";
include_once "../App/Player.php";

use App\{Gameplay, Player};

if (isset($_SESSION['player_id'])) {
    $output = "";
    $btnOutput = include '../templates/backBtn.php';
    $gameplay = new Gameplay($_SESSION['player_id']);
    $me = new Player($_SESSION['player_id']);
    $opponent = new Player($me->opponentId);
    if ($me->status == 'WAITING' && $opponent->status == 'WAITING') {
        $gameplay->setPlayersStatus('CONFIRMING');
    } elseif ($me->status == 'READY' && $opponent->status == 'READY') {
//        losowanie ruchu tylko po stronie gracza, który stworzył grę
        if ($me->playerId < $opponent->playerId) {
            $_SESSION['turn'] = mt_rand(0,1);
            if ($_SESSION['turn']) {
                $me->setStatus('PLAYER_MOVE');
                $opponent->setStatus('OPPONENT_MOVE');
            } else {
                $me->setStatus('OPPONENT_MOVE');
                $opponent->setStatus('PLAYER_MOVE');
            }
        }
    } elseif ($me->status == 'REVENGE' && $opponent->status == 'REVENGE') {
        $gameplay->setPlayersStatus('CONFIRMING');
        $gameplay->resetPlayersBallsLocation();
    }
    if (in_array($me->status, ['WIN', 'LOSE', 'PLAYER_MOVE', 'OPPONENT_MOVE'])) {
        $output .= $gameplay->displayGameHeader();
    }
    if ($opponent->status == 'DISCONNECTED') {
        $output .=
            '<h2>Przeciwnik rozłączył się</h2>';
    } elseif ($me->status == 'WAITING') {
        $output .=
            '<div class="game-id-div"><h1>Twoja gra: <span>' . $gameplay->getUniqueGameId() . '</span></h1></div>
            <h2>Oczekiwanie na przeciwnika</h2>
            <div class="loader"></div>';
    } elseif ($me->status == 'CONFIRMING') {
        $output .=
            "<h2>$opponent->nickname czeka na Ciebie!</h2>
             <h2>Gotowy?</h2>";
        $btnOutput .= include '../templates/confirmBtn.php';
    } elseif ($me->status == 'READY') {
        $output .=
            '<h2>Oczekiwanie na potwierdzenie przez przeciwnika</h2>
            <div class="loader"></div>';
    } elseif ($me->status == 'PLAYER_MOVE' || $me->status == 'OPPONENT_MOVE') {
        $output .= $gameplay->displayGameOutput();
    } elseif ($me->status == 'REVENGE') {
        $output .=
            '<h2>Oczekiwanie na potwierdzenie rewanżu</h2>
            <div class="loader"></div>';
    } elseif ($me->status == 'WIN' || $me->status == 'LOSE') {
        $output .= '<h1 class="result-heading">';
        $output .= match ($me->status) {
            'WIN' => 'WYGRAŁEŚ',
            'LOSE' => 'PRZEGRAŁEŚ',
        };
        $output .= '</h1>';
        if ($opponent->status == 'REVENGE') {
            $output .=
                '<div class="game-info-div">Przeciwnik chce rewanżu!</div>';
        }
        $output .= $gameplay->displayBoard();
        $btnOutput .= include '../templates/revengeBtn.php';
    }
    $output .= "<div class='btn-div'>$btnOutput</div>";
    echo $output;
}