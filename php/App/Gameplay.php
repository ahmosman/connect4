<?php

namespace App;
require_once "Game.php";

class Gameplay extends Game
{
    public int $width;
    public int $height;
    public array $board;
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
// $ballsLocation - współrzędne — przykład dla przeciwnika powyżej: [[0,1],[0,3],[1,2],[2,0],[2,3]]

    public function __construct(int $playerId = null)
    {
        parent::__construct($playerId);
        $this->width = explode('x', $this->boardSize)[0];
        $this->height = explode('x', $this->boardSize)[1];
        $this->isPlayersTurn = ($this->player->status === 'PLAYER_MOVE');
//      generowanie tablicy
        $this->board = $this->generateBoard();
    }

    private function generateBoard(): array
    {
        $playerBalls = $this->player->ballsLocation;
        $opponentBalls = $this->opponent->ballsLocation;
        $board = [];
        // ustawianie wszędzie zer
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

    public function putBall(int $col)
    {
        $board = $this->board;
        $height = $this->height;
        $newBallsLocation = [];
        for ($i = 0; $i < $height; $i++) {
            if ($board[$i][$col] != 1 && $board[$i][$col] != 2) {
                $newBallsLocation = [$i, $col];
                break;
            }
        }
        $ballsLocation = $this->player->ballsLocation;
        $ballsLocation[] = $newBallsLocation;
        $this->player->setBallsLocation($ballsLocation);
        $this->player->setStatus('OPPONENT_MOVE');
        $this->opponent->setStatus('PLAYER_MOVE');
//        sprawdzamy wynik po wykonanym ruchu
        $this->resultCheck($newBallsLocation);
    }

    public function resultCheck(array $newBalls): void
    {
//      generujemy na nowo zaktualizowaną tablicę przez putBall
        $board = $this->generateBoard();
        $x = $newBalls[0];
        $y = $newBalls[1];
        for ($i = $x - 3; $i <= $x; $i++)//wygrana pionowa
        {
            if ($this->areCellsSet($board, [[$i, $y], [$i + 1, $y], [$i + 2, $y], [$i + 3, $y]])) {
                if ($board[$i][$y] == 1 && $board[$i + 1][$y] == 1 && $board[$i + 2][$y] == 1 && $board[$i + 3][$y] == 1) {
                    $this->player->setStatus('WIN');
                    $this->opponent->setStatus('LOSE');
                    return;
                }
            }
        }
        for ($i = $y - 3; $i <= $y; $i++)//wygrana pozioma
        {
            if ($this->areCellsSet($board, [[$x, $i], [$x, $i + 1], [$x, $i + 2], [$x, $i + 3]])) {
                if ($board[$x][$i] == 1 && $board[$x][$i + 1] == 1 && $board[$x][$i + 2] == 1 && $board[$x][$i + 3] == 1) {
                    $this->player->setStatus('WIN');
                    $this->opponent->setStatus('LOSE');
                    return;
                }
            }
        }
        for ($i = 0; $i <= 3; $i++)//wygrana na skos (y=-x)
        {
            if ($this->areCellsSet($board, [[$x - $i, $y + $i], [$x - $i + 1, $y + $i - 1], [$x - $i + 2, $y + $i - 2], [$x - $i + 3, $y - $i - 3]])) {
                if ($board[$x - $i][$y + $i] == 1 && $board[$x - $i + 1][$y + $i - 1] == 1 && $board[$x - $i + 2][$y + $i - 2] == 1 && $board[$x - $i + 3][$y - $i - 3] == 1) {
                    $this->player->setStatus('WIN');
                    $this->opponent->setStatus('LOSE');
                    return;
                }
            }
        }
        for ($i = 0; $i <= 3; $i++)//wygrana na skos (y=x)
        {
            if ($this->areCellsSet($board, [[$x - 3 + $i, $y - 3 + $i], [$x - 2 + $i, $y - 2 + $i], [$x - 1 + $i, $y - 1 + $i], [$x + $i, $y + $i]])) {
                if ($board[$x - 3 + $i][$y - 3 + $i] == 1 && $board[$x - 2 + $i][$y - 2 + $i] == 1 && $board[$x - 1 + $i][$y - 1 + $i] == 1 && $board[$x + $i][$y + $i] == 1) {
                    $this->player->setStatus('WIN');
                    $this->opponent->setStatus('LOSE');
                    return;
                }
            }
        }
        $draw = 0;//zmienna licząca ile piłek jest na planszy, jeżeli piłki wypełniły całą planszę to gra kończy się remisem

        for ($i = 0; $i < $this->height; $i++) {
            for ($j = 0; $j < $this->width; $j++) {
                if ($board[$i][$j] == 1 || $board[$i][$j] == 2) {
                    $draw++;
                }
            }
        }
        if ($this->height * $this->width == $draw) {
            $this->player->setStatus('DRAW');
            $this->opponent->setStatus('DRAW');
        }
    }

    private function areCellsSet(array $arr, array $cells): bool
    {
        foreach ($cells as $cell)
            if (!isset($arr[$cell[0]][$cell[1]]))
                return false;
        return true;
    }

    public function displayGameHeader(): string
    {
        return
            "<table class='game-header'>
                <tr class='game-header-nicknames'>
                    <td class='player'>" . $this->player->nickname . "</td>
                    <td class='opponent'>" . $this->opponent->nickname . "</td>
                </tr>
                <tr class='game-header-wins'>
                    <td class='player'>" . $this->player->wins . "</td>
                    <td class='opponent'>" . $this->opponent->wins . "</td>
                </tr>
            </table>
";
    }

    public function displayGameOutput(): string
    {
        $turn = $this->isPlayersTurn ? 'Twój ruch' : 'Ruch przeciwnika';
        $output = "<h1>$turn</h1>";
        $output .= $this->displayBoard();
        return $output;
    }

    public function displayBoard(): string
    {
        $boardHTML = "<div class='board'><table class='board-table'>";
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
        $boardHTML .= "</table></div>";
//        style tablicy z bazy danych
        $boardHTML .= "<style>
            .player-ball {
                background-color:" . $this->player->playerColor . ";
            }
            .opponent-ball {
                background-color: " . $this->player->opponentColor . ";
            }                        
            .game-header-wins .player{
                color:" . $this->player->playerColor . ";
            }                           
            .game-header-wins .opponent{
                color:" . $this->player->opponentColor . ";
            }
        </style>";
        return $boardHTML;
    }
}
