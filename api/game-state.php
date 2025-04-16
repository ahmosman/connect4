<?php

session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../cors.php';

use App\{Gameplay, Player, Response};

if (isset($_SESSION['player_id'])) {
    try {
        $gameplay = new Gameplay($_SESSION['player_id']);
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
            'opponentStatus' => $opponent->status,
            'gameInfo' => $gameInfo,
            'playerNickname' => $me->nickname ?? '',
            'playerWins' => $me->wins ?? 0,
            'playerColor' => $me->playerColor ?? '',
            'opponentNickname' => $opponent->nickname ?? '',
            'opponentWins' => $opponent->wins ?? 0,
            'opponentColor' => $opponent->opponentColor ?? '',
            'lastPutBall' => $gameplay->getLastPutBall(),
            'winningBalls' => $gameplay->getWinningBalls() ?? [],
            'gameId' => $gameplay->getUniqueGameId(),
        ];

        Response::makeJsonResponse($gameState);
    } catch (Exception $e) {
        Response::makeErrorResponse('Error fetching game state: ' . $e->getMessage());
    }
} else {
    Response::makeSuccessResponse('Not logged in');
}
