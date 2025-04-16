<?php

namespace App;

class GameState
{
    /**
     * Updates the game state based on the players' statuses.
     */
    public static function update()
    {
        try {
            // Initialize gameplay and player objects
            $gameplay = new Gameplay($_SESSION['player_id']);
            $me = new Player($_SESSION['player_id']);
            $opponent = new Player($me->opponentId);

            // Handle status updates for both players
            if ($me->status === 'WAITING' && $opponent->status === 'WAITING') {
                // Both players are waiting, move to confirming state
                $gameplay->setPlayersStatus('CONFIRMING');
            } elseif ($me->status === 'READY' && $opponent->status === 'READY') {
                // Both players are ready, determine the first turn
                $turn = mt_rand(0, 1);

                if ($turn) {
                    $me->setStatus('PLAYER_MOVE');
                    $opponent->setStatus('OPPONENT_MOVE');
                } else {
                    $me->setStatus('OPPONENT_MOVE');
                    $opponent->setStatus('PLAYER_MOVE');
                }
            } elseif ($me->status === 'REVENGE' && $opponent->status === 'REVENGE') {
                // Both players want a rematch, reset the game state
                $gameplay->setPlayersStatus('CONFIRMING');
                $gameplay->resetPlayersBallsLocation();
            }
        } catch (\Exception $e) {
            // Log the error and terminate the script
            error_log($e->getMessage());
            exit('Error occurred while updating game state');
        }
    }
}
