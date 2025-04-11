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
        
        // Handle waiting state or opponent disconnect
        if ($me->status == 'WAITING' || $opponent->status == 'DISCONNECTED') {
            $playerStatus = 'WAITING';
        } else {
            // Map the player status to the expected format
            $playerStatus = match ($me->status) {
                'PLAYER_MOVE' => 'PLAYER_MOVE',
                'OPPONENT_MOVE' => 'OPPONENT_MOVE',
                'WIN' => 'WIN',
                'LOSE' => 'LOSE',
                default => 'WAITING', // For CONFIRMING, READY, REVENGE, etc.
            };
        }
        
        // Get board data and dimensions
        $board = $gameplay->getBoard();
        $width = count($board[0]) ?? 7; // Default width if board is empty
        $height = count($board) ?? 6;   // Default height if board is empty
        
        // Game state according to the interface
        $gameState = [
            'board' => $board,
            'width' => $width,
            'height' => $height,
            'isPlayerTurn' => $gameplay->isPlayerTurn(),
            'playerStatus' => $playerStatus,
            'playerNickname' => $me->nickname ?? '',
            'playerWins' => $me->wins ?? 0,
            'playerColor' => $me->playerColor ?? '',
            'opponentNickname' => $opponent->nickname ?? '',
            'opponentWins' => $opponent->wins ?? 0,
            'opponentColor' => $opponent->opponentColor ?? '',
            'lastPutBall' => $gameplay->getLastPutBall(),
            'winningBalls' => $gameplay->getWinningBalls() ?? [],
        ];
        
        Response::makeContentResponse(json_encode($gameState));
    } catch (Exception $e) {
        Response::makeErrorResponse('Error fetching game state: ' . $e->getMessage());
    }
} else {
    Response::makeErrorResponse('Not logged in');
}