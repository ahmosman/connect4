<?php
include_once "Config.php";

class Player
{
    public int $playerId;
    public int $opponentId;
    public string $nickname;
    public string $status;
    public string $ballsLocation;
    private mysqli $conn;

    public function __construct(int $playerId = null)
    {
        $config = new Config();
        $this->conn = $config->getConn();

        if (!is_null($playerId)) {
            $playerAssoc = $this->conn->query("SELECT * FROM players
                WHERE player_id = $playerId")->fetch_assoc() or die($this->conn->error);
            $this->playerId = $playerId;
            $this->opponentId = $playerAssoc['opponent_id'];
            $this->status = $playerAssoc['status'];
            $this->nickname = $playerAssoc['nickname'];
            $this->ballsLocation = $playerAssoc['balls_location'];
        }
    }

    public static function create()
    {
        $player = new self();
        $player->status = 'NONE';
        $player->nickname = 'NONE';
        $player->ballsLocation = '[]';
        $player->playerId = $player->conn->query("SHOW TABLE STATUS LIKE 'players'")->fetch_assoc()['Auto_increment'];
        $player->conn->query("INSERT INTO players (player_id, status, balls_location) VALUES
            ($player->playerId ,'$player->status','$player->ballsLocation')") or die($player->conn->error);
        return $player;

    }

    public function setStatus($status): void
    {
        $this->conn->query("UPDATE players SET status = '$status' where player_id = $this->playerId") or die($this->conn->error);
        $this->status = $status;
    }


    public function setNickname($nickname): void
    {
        $this->conn->query("UPDATE players SET nickname = '$nickname' where player_id = $this->playerId") or die($this->conn->error);
        $this->nickname = $nickname;
    }


    public function setBallsLocation(string $ballsLocation): void
    {
        $this->conn->query("UPDATE players SET balls_location = '[$ballsLocation]' where player_id = $this->playerId") or die($this->conn->error);
        $this->ballsLocation = $ballsLocation;
    }

    public function getOpponentId()
    {
        return $this->opponentId;
    }

    public function setOpponentId($opponentId): void
    {
        $this->conn->query("UPDATE players SET opponent_id = '$opponentId' where player_id = $this->playerId") or die($this->conn->error);
            $this->opponentId = $opponentId;
    }

    public function getUniqueGameId(): int
    {
        $uniqueGameId = $this->conn->query("SELECT unique_game_id FROM games g JOIN players p 
        on g.player_id = p.player_id WHERE p.player_id = $this->playerId or p.opponent_id = $this->playerId")->fetch_row()[0] or die($this->conn->error);


        return $uniqueGameId;

    }

}