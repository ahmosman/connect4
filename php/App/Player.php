<?php

namespace App;
require_once 'Config.php';

use mysqli;

class Player
{
    public int $playerId;
    public int $opponentId;
    public string $nickname;

//kolejność statusów: NONE -> WAITING -> CONFIRMING -> READY -> PLAYER/OPPONENT_MOVE -> WIN/LOSE/DRAW -> REVENGE
    public string $status;
    public array $ballsLocation;
    private mysqli $conn;
    public int $gameId;

    public function __construct(int $playerId = null)
    {
        $config = new Config();
        $this->conn = $config->getConn();

        if (!is_null($playerId)) {
            $playerAssoc = $this->conn->query("SELECT * FROM players
                WHERE player_id = $playerId")->fetch_assoc() or die($this->conn->error);
            $this->playerId = $playerId;
            $this->gameId = $playerAssoc['game_id'];
            $this->opponentId = $playerAssoc['opponent_id'];
            $this->status = $playerAssoc['status'];
            $this->nickname = $playerAssoc['nickname'];
            $this->ballsLocation = json_decode($playerAssoc['balls_location']);
        }
    }

    public static function create()
    {
        $player = new self();
        $player->status = 'NONE';
        $player->nickname = 'NONE';
        $player->ballsLocation = [];
        $jsonBallsLocation = json_encode($player->ballsLocation);
        $player->playerId = $player->conn->query("SHOW TABLE STATUS LIKE 'players'")->fetch_assoc()['Auto_increment'];
        $player->conn->query("INSERT INTO players (player_id, status, balls_location) VALUES
            ($player->playerId ,'$player->status','$jsonBallsLocation')") or die($player->conn->error);
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


    public function setBallsLocation(array $ballsLocation): void
    {
        $jsonBallsLocation = json_encode($ballsLocation);
        $this->conn->query("UPDATE players SET balls_location = '$jsonBallsLocation' where player_id = $this->playerId") or die($this->conn->error);
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

    public function setGameId(int $gameId)
    {
        $this->conn->query("UPDATE players SET game_id = '$gameId' where player_id = $this->playerId") or die($this->conn->error);
        $this->gameId = $gameId;
    }
}
