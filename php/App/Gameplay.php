<?php

namespace App;
require_once "Game.php";

class Gameplay extends Game
{
    private int $width;
    private int $height;
    private array $board;
    public bool $isPlayersTurn;

// przykład tablicy $board
//      |---------------|
//    3 | 0 | 0 | 0 | 1 |
//    2 | 2 | 0 | 1 | 2 |
//    1 | 1 | 1 | 2 | 1 |
//    0 | 1 | 2 | 1 | 2 |
//      |---------------|
//        0   1   2   3
//
//  0 - puste pole
//  1 - gracz (me)
//  2 - przeciwnik
// $ballsLocation - współrzędne - przykład dla przeciwnika powyżej: [[0,1],[0,3],[1,2],[2,0],[2,3]]

    public function __construct(int $playerId = null)
    {
        parent::__construct($playerId);
//        var_dump('my id '.$this->player->playerId);
//        var_dump('opponent id '.$this->opponent->playerId);
//        var_dump($_SESSION);
        $this->width = explode('x', $this->boardSize)[0];
        $this->height = explode('x', $this->boardSize)[1];
        $this->isPlayersTurn = ($this->player->status === 'PLAYER_MOVE');
//        generating board
        $this->board = $this->generateBoard();
//        echo "<pre>";
//        print_r($playerBalls);
//        print_r($board);
//        echo "</pre>";
    }

    //TODO: putBall, resultCheck (Piotrek)
    public function putBall(int $col)
    {
        //wejście: nr kolumny $col
        //wyjście: tablica $newBallsLocation, która jest tablicą $ballsLocation powiększoną o nową współrzędną

        //szukamy nr następnego wiersza do którego zostanie wstawiona kulka
        //[...]
        //można sprawdzić lokację pola kolejnego pola np. w $this->board
//        $newBallsLocation = [];
//
//        wysłanie $newBallsLocation do bazy i zaktualizowanie pola klasy (zobacz kod setBallsLocation)
//        $this->player->setBallsLocation($newBallsLocation);

//        zmiana stausów graczy:
//        $this->player->setStatus('OPPONENT_MOVE');
//        $this->opponent->setStatus('PLAYER_MOVE');

//        sprawdzamy wynik po wykonanym ruchu
//        resultCheck();
    }

    private function resultCheck()
    {
//      generujemy na nowo zaktualizowaną tablicę przez putBall()
//      $updatedBoard = $this->generateBoard();

//      sprawdzamy czy gracz, który właśnie wykonał ruch wygrywa
//        [...]
//      jeśli wygrywa to:
//      $this->player->setStatus('WIN');
//      $this->opponent->setStatus('LOSE');

//      jeśli remis to:
//      $this->setPlayersStatus('DRAW');

//
//       wyczyść piłki przeciwników
//       $this->resetPlayersBallsLocation();

    }

    private function generateBoard(): array
    {
        $playerBalls = $this->player->ballsLocation;
        $opponentBalls = $this->opponent->ballsLocation;
        $board = [];
        for ($h = 0; $h < $this->height; $h++) {
            for ($w = 0; $w < $this->width; $w++)
                $board[$h][$w] = 0;
        }
        foreach ($playerBalls as $pBall)
            $board[$pBall[0]][$pBall[1]] = 1;
        foreach ($opponentBalls as $oBall)
            $board[$oBall[0]][$oBall[1]] = 2;
        return $board;
    }

    private function displayBoard(): string
    {
        $boardHTML = "<table class='board-table'>";
        for ($tr = $this->height - 1; $tr >= 0; $tr--) {
            $boardHTML .= "<tr data-row='$tr'>";
            for ($td = 0; $td < $this->width; $td++) {
                $ballClass = match ($this->board[$tr][$td]) {
                    0 => 'empty-ball',
                    1 => 'player-ball',
                    2 => 'opponent-ball'
                };
                $boardHTML .= "<td data-col='$td' class='$ballClass'></td>";
            }
            $boardHTML .= "</tr>";
        }
        $boardHTML .= "</table>";
        return $boardHTML;
    }

    public function displayOutput(): string
    {
        $turn = $this->isPlayersTurn ? 'Twój ruch' : 'Ruch przeciwnika';
        $output = "<h1>$turn</h1>";
        $output .= $this->displayBoard();
        return $output;
    }
}