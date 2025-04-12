<?php

session_start();
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../cors.php';

use App\{Gameplay, Player, Response};

if (isset($_SESSION['player_id'])) {
    try {
        $gameplay = new Gameplay($_SESSION['player_id']);
        $me = new Player($_SESSION['player_id']);
        $opponent = new Player($me->opponentId);

        // Aktualizacja statusów zgodnie z game-content.php
        // Włączamy te same zmiany statusów
        if ($me->status == 'WAITING' && $opponent->status == 'WAITING') {
            // Ustawiamy status CONFIRMING, tak jak w game-content.php
            $gameplay->setPlayersStatus('CONFIRMING');
        } elseif ($me->status == 'READY' && $opponent->status == 'READY') {
            // Losowanie ruchu tylko po stronie gracza, który stworzył grę
            if ($me->playerId < $opponent->playerId) {
                $_SESSION['turn'] = mt_rand(0, 1);
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

        // Pobierz odświeżoną instancję gracza po zmianach statusu
        $me = new Player($_SESSION['player_id']);
        $opponent = new Player($me->opponentId);

        // Get board data and dimensions
        $board = $gameplay->getBoard();
        $width = count($board[0] ?? []) ?: 7; // Default width if board is empty
        $height = count($board) ?: 6;   // Default height if board is empty

        // Dodatkowe informacje o rozgrywce
        $gameInfo = "";
        if ($opponent->status == 'DISCONNECTED') {
            $gameInfo = 'Przeciwnik rozłączył się';
        } elseif ($me->status == 'WAITING') {
            $gameInfo = 'Oczekiwanie na przeciwnika';
        } elseif ($me->status == 'CONFIRMING') {
            $gameInfo = $opponent->nickname . ' czeka na Ciebie! Gotowy?';
        } elseif ($me->status == 'READY') {
            $gameInfo = 'Oczekiwanie na potwierdzenie przez przeciwnika';
        } elseif ($me->status == 'REVENGE') {
            $gameInfo = 'Oczekiwanie na potwierdzenie rewanżu';
        } elseif ($me->status == 'WIN') {
            $gameInfo = 'WYGRAŁEŚ';
        } elseif ($me->status == 'LOSE') {
            $gameInfo = 'PRZEGRAŁEŚ';
        } elseif ($me->status == 'PLAYER_MOVE') {
            $gameInfo = 'Twój ruch';
        } elseif ($me->status == 'OPPONENT_MOVE') {
            $gameInfo = 'Ruch przeciwnika';
        }

        // Game state according to the interface
        $gameState = [
            'board' => $board,
            'width' => $width,
            'height' => $height,
            'isPlayerTurn' => $me->status == 'PLAYER_MOVE',
            'playerStatus' => $me->status,
            'gameInfo' => $gameInfo,
            'playerNickname' => $me->nickname ?? '',
            'playerWins' => $me->wins ?? 0,
            'playerColor' => $me->playerColor ?? '',
            'opponentNickname' => $opponent->nickname ?? '',
            'opponentWins' => $opponent->wins ?? 0,
            'opponentColor' => $opponent->opponentColor ?? '',
            'lastPutBall' => $gameplay->getLastPutBall(),
            'winningBalls' => $gameplay->getWinningBalls() ?? [],
        ];

        Response::makeJsonResponse($gameState);
    } catch (Exception $e) {
        Response::makeErrorResponse('Error fetching game state: ' . $e->getMessage());
    }
} else {
    Response::makeErrorResponse('Not logged in');
}
