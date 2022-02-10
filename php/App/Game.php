<?php

namespace App;

use mysqli;

require_once "Config.php";
require_once "Player.php";

class Game
{
    protected Player $player;
    protected Player $opponent;
    protected string $boardSize;
    private int $gameId;
    private int $uniqueGameId;
    private mysqli $conn;


    public function __construct(int $playerId = null)
    {
        $config = new Config();
        $this->conn = $config->getConn();
        if (!is_null($playerId)) {
            $this->player = new Player($playerId);
            $this->opponent = new Player($this->player->opponentId);
            $this->gameId = $this->player->gameId;
            $gameAssoc = $this->conn->query("SELECT * FROM games
                WHERE game_id = $this->gameId")->fetch_assoc() or die($this->conn->error);
            $this->boardSize = $gameAssoc['board_size'] ?? '8x8';
            $this->uniqueGameId = $gameAssoc['unique_game_id'];
        }
    }

    public static function create(Player $player, Player $opponent): self
    {
        $game = new self();


        //generowanie unikalnego id gry
        $uniqueGameId = mt_rand(100000, 999999);
        while (true) {
            $check_unique_query = $game->conn->query("SELECT * FROM games WHERE unique_game_id = $uniqueGameId");
            if ($check_unique_query->num_rows > 0) {
                $uniqueGameId = mt_rand(100000, 999999);
            } else
                break;
        }
        $game->uniqueGameId = $uniqueGameId;

        $gameId = $game->conn->query("SHOW TABLE STATUS LIKE 'games'")->fetch_assoc()['Auto_increment'];

        //tworzenie nowego rekordu w tabeli games z unikalnym id
        $game->conn->query("INSERT INTO games (unique_game_id) VALUES
            ('$uniqueGameId')") or die($game->conn->error);

        $player->setGameId($gameId);
        $opponent->setGameId($gameId);
        $game->player = $player;
        $game->opponent = $opponent;
        return $game;
    }

    public static function join(string $uniqueGameId): array
    {
        $result = ['response' => 'Brak gry o podanym ID', 'playerToJoinId' => null];
        if ($uniqueGameId > 0) {
            $game = new self();
            $gameExistsQuery = $game->conn->query("SELECT * from games where unique_game_id = $uniqueGameId") or die($game->conn->error);
            if ($gameExistsQuery->num_rows > 0) {
                $searchPlayerQuery = $game->conn->query("SELECT player_id, status from games g join players p on g.game_id = p.game_id where unique_game_id = $uniqueGameId") or die($game->conn->error);
                while ($player = $searchPlayerQuery->fetch_assoc()) {
                    if ($player['status'] == 'NONE') {
                        $result['response'] = 'success';
                        $result['playerToJoinId'] = $player['player_id'];
                        return $result;
                    }
                    $result['response'] = 'Ktoś inny już dołączył do gry';
                }
            }
        }
        return $result;
    }

    public function getOpponentId(): int
    {
        return $this->opponent->playerId;
    }

    public function getUniqueGameId(): string
    {
        return $this->uniqueGameId;
    }

    public function setPlayersStatus(string $status): void
    {
        $this->player->setStatus($status);
        $this->opponent->setStatus($status);
    }

    public function resetPlayersBallsLocation()
    {
        $playerId = $this->player->playerId;
        $opponentId = $this->player->opponentId;
        $this->conn->query("UPDATE players SET balls_location = '[]' where player_id in ($playerId, $opponentId)") or die($this->conn->error);
        $this->player->ballsLocation = [];
        $this->opponent->ballsLocation = [];
    }

    private function setUniqueGameId(int $uniqueGameId): void
    {
        $this->conn->query("UPDATE games SET unique_game_id = $uniqueGameId WHERE unique_game_id = $this->uniqueGameId") or die($this->conn->error);
        $this->uniqueGameId = $uniqueGameId;
    }

}