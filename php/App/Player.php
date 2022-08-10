<?php

namespace App;

use Exception;
use mysqli;

class Player
{
    public int $playerId;
    public int $opponentId;
    public string $nickname;
    public array $ballsLocation;
    public int $gameId;
    public int $wins;
    public string $playerColor;
    public string $opponentColor;
    public string $status;

//kolejność statusów: NONE -> WAITING -> CONFIRMING -> READY -> PLAYER/OPPONENT_MOVE -> WIN/LOSE/DRAW -> REVENGE
    private mysqli $conn;

    public function __construct(int $playerId = null)
    {
        $config = new Config();
        $this->conn = $config->getConn();

        if (!is_null($playerId)) {
            try {
                $stmt = $this->conn->prepare(
                    "SELECT * FROM players WHERE player_id = ?");
                $stmt->bind_param("i", $playerId);
                $stmt->execute();
                $playerAssoc = $stmt->get_result()->fetch_assoc();
                $this->playerId = $playerId;
                $this->gameId = $playerAssoc['game_id'];
                $this->opponentId = $playerAssoc['opponent_id'];
                $this->status = $playerAssoc['status'];
                $this->nickname = $playerAssoc['nickname'];
                $this->ballsLocation = json_decode(
                    $playerAssoc['balls_location']
                );
                $this->wins = $playerAssoc['wins'];
                $this->playerColor = $playerAssoc['player_color'];
                $this->opponentColor = $playerAssoc['opponent_color'];
            } catch (Exception $e) {
                error_log($e->getMessage());
                exit('Wystąpił błąd');
            }
        }
    }

    public static function create()
    {
        $player = new self();
        $player->status = 'NONE';
        $player->nickname = 'NONE';
        $player->ballsLocation = [];
        $player->playerColor = '#ffffff';
        $player->opponentColor = '#000000';
        $jsonBallsLocation = json_encode($player->ballsLocation);
        try {
            $player->playerId = $player->conn->query(
                "SHOW TABLE STATUS LIKE 'players'"
            )->fetch_assoc()['Auto_increment'];
            $stmt = $player->conn->prepare(
                "INSERT INTO players (player_id, status, balls_location, player_color, opponent_color) VALUES
            (?,?,?,?,?)"
            );
            $stmt->bind_param(
                "issss",
                $player->playerId,
                $player->status,
                $jsonBallsLocation,
                $player->playerColor,
                $player->opponentColor
            );
            $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
        return $player;
    }

    public function setStatus(string $status): void
    {
        if ($status === 'WIN') {
            $this->setWins($this->wins + 1);
        }
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET status = ? where player_id = ?"
            );
            $stmt->bind_param("si", $status, $this->playerId);
            $stmt->execute();
            $this->status = $status;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    private function setWins(int $wins)
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET wins = ? where player_id = ?"
            );
            $stmt->bind_param("ii", $wins, $this->playerId);
            $stmt->execute();
            $this->wins = $wins;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    public function setNickname(string $nickname): void
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET nickname = ? where player_id = ?"
            );
            $stmt->bind_param("si", $nickname, $this->playerId);
            $stmt->execute();
            $this->nickname = $nickname;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }


    public function setBallsLocation(array $ballsLocation): void
    {
        $jsonBallsLocation = json_encode($ballsLocation);
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET balls_location = ? where player_id = ?"
            );
            $stmt->bind_param("si", $jsonBallsLocation, $this->playerId);
            $stmt->execute();
            $this->ballsLocation = $ballsLocation;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    public function setOpponentId(int $opponentId): void
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET opponent_id = ? where player_id = ?"
            );
            $stmt->bind_param("ii", $opponentId, $this->playerId);
            $stmt->execute();
            $this->opponentId = $opponentId;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    public function setGameId(int $gameId): void
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET game_id = ? where player_id = ?"
            );
            $stmt->bind_param("ii", $gameId, $this->playerId);
            $stmt->execute();
            $this->gameId = $gameId;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    public function setPlayerColor(string $playerColor): void
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET player_color = ? where player_id = ?"
            );
            $stmt->bind_param("si", $playerColor, $this->playerId);
            $stmt->execute();
            $this->playerColor = $playerColor;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }

    public function setOpponentColor(string $opponentColor): void
    {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE players SET opponent_color = ? where player_id = ?"
            );
            $stmt->bind_param("si", $opponentColor, $this->playerId);
            $stmt->execute();
            $this->opponentColor = $opponentColor;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit('Wystąpił błąd');
        }
    }
}
