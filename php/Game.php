<?php
require_once "Config.php";
require_once "Player.php";

class Game
{
    protected Player $player;
    protected Player $opponent;
    private int $uniqueGameId;
    private int $playerId;
    private mysqli $conn;


    public function __construct(int $uniqueGameId = null)
    {
        $config = new Config();
        $this->conn = $config->getConn();
        if (!is_null($uniqueGameId)) {
            $this->playerId = $this->conn->query("SELECT player_id FROM games WHERE unique_game_id = $uniqueGameId")->fetch_row()[0] or die($this->conn->error);
            $this->player = new Player($this->playerId);
            $this->opponent = new Player($this->player->opponentId);
            $this->uniqueGameId = $uniqueGameId;
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

        //tworzenie nowego rekordu w tabeli games z unikalnym id
        $game->conn->query("INSERT INTO games (unique_game_id) VALUES
            ('$uniqueGameId')") or die($game->conn->error);

        //ustawienie id rozpoczynającego gracza w bazie
        $game->setPlayerId($player->playerId);

        $game->player = $player;
        $game->opponent = $opponent;
        return $game;

    }

    private function setUniqueGameId(int $uniqueGameId): void
    {
        $this->conn->query("UPDATE games SET unique_game_id = $uniqueGameId WHERE unique_game_id = $this->uniqueGameId") or die($this->conn->error);
        $this->uniqueGameId = $uniqueGameId;

    }

    private function setPlayerId(int $playerId): void
    {
        $this->conn->query("UPDATE games SET player_id = $playerId WHERE unique_game_id = $this->uniqueGameId") or die($this->conn->error);
        $this->playerId = $playerId;
    }

    public function getOpponentId(): int
    {
        return $this->opponent->playerId;
    }

//    public function setPlayer(string $playerNo, $playerId): bool
//    {
//        if ($playerNo == 'player1' || $playerNo == 'player2') {
//            if ($this->conn->query("UPDATE games SET $playerNo = $playerId where unique_game_id = $this->uniqueGameId")) {
//                ($playerNo == 'player1') ? $this->player = $this->getPlayerById($playerId) : $this->opponent = $this->getPlayerById($playerId);
//                return true;
//            }
//        }
//        return false;
//
//    }
//
//    private function getPlayerById(string $playerId): Player
//    {
//        $assocQuery = $this->conn->query("SELECT * FROM players
//         WHERE player_id = $playerId")->fetch_assoc();
//        return new Player($assocQuery);
//    }

    public function getUniqueGameId(): string
    {
        return $this->uniqueGameId;
    }

//    public function getPlayerById(string $playerId): Player
//    {
//        return ($this->player1->playerId == $playerId) ? $this->player1 : $this->player2;
//    }

    public function gameExists(): bool
    {
        $gameExistsQuery = $this->conn->query("SELECT * from games where unique_game_id = $this->uniqueGameId") or die($this->conn->error);
        return ($gameExistsQuery->num_rows > 0);
    }

    public function isJoinable(): bool
    {
        if ($this->opponent->status === "NONE") {
            return true;
        }
        return false;
    }

    public function setPlayersStatus(string $status): void
    {
        $this->player->setStatus($status);
        $this->opponent->setStatus($status);
    }
//
//    private function getPlayerByNo(string $playerNo): Player
//    {
//        $assocQuery = $this->conn->query("SELECT * FROM games g
//        JOIN players p on g.$playerNo = p.player_id
//         WHERE unique_game_id = $this->uniqueGameId")->fetch_assoc();
//        return new Player($assocQuery);
//    }

}