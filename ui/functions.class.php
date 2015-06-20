<?php

class functions {

    private $sql, $dbhost, $dbuser, $dbpass, $dbdb;

    public function __construct() {
        $config_string = file_get_contents("../config.json");
        $config = json_decode($config_string, TRUE);
        $this->dbhost = $config["database"]["host"];
        $this->dbuser = $config["database"]["user"];
        $this->dbpass = $config["database"]["pass"];
        $this->dbdb = $config["database"]["db"];
        $this->sql = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb, 3306);
    }

    public function __destruct() {
        $this->sql->close();
    }

    public function getRecords($start, $count) {
        $result = $this->sql->query("SELECT * FROM blockhound_actions ORDER BY id DESC LIMIT " . intval($start) . "," . intval($count));
        $html = "";
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $world_type = intval($row[3]);
            if ($world_type === 1) {
                $world_type = "the End";
            } else if ($world_type === -1) {
                $world_type = "the Nether";
            } else {
                $world_type = "the Overworld";
            }
            $html .= "<tr><td>$row[0]</td><td>$row[1]</td><td>" . $this->getName($row[2]) . "</td><td>$world_type</td><td>$row[4] | $row[5] | $row[6]</td><td>$row[7]</td><td>$row[8]</td></tr>";
        }
        return $html;
    }

    public function getName($uuid) {
        $result = $this->sql->query("SELECT * FROM blockhound_players_names WHERE uuid='" . $uuid . "'");
        $now = date("Y-m-d H:i:s");
        if ($result != null) {
            $row = $result->fetch_array(MYSQLI_NUM);
            if (strtotime($now) - strtotime($row[2]) < 604800) {
                return $row[1];
            } else {
                $this->sql->query("DELETE FROM blockhound_players_names WHERE uuid='" . $uuid . "'");
            }
        }
        $json = file_get_contents("https://sessionserver.mojang.com/session/minecraft/profile/" . $uuid);
        $profile = json_decode($json, true);
        $this->sql->query("INSERT INTO blockhound_players_names(uuid, name, date_cached) VALUES ('" . $uuid . "', '" . $profile["name"] . "', '" . $now . "')");
        return "<span style='color: #99ff99;'> " . $profile["name"] . "</span>";
    }

    public function getUUID($name) {
        $result = $this->sql->query("SELECT * FROM blockhound_players_names WHERE name='" . $name . "'");
        $now = date("Y-m-d H:i:s");
        if ($result != null) {
            $row = $result->fetch_array(MYSQLI_NUM);
            if (strtotime($now) - strtotime($row[2]) < 604800) {
                return $row[0];
            } else {
                $this->sql->query("DELETE FROM blockhound_players_names WHERE name='" . $name . "'");
            }
        }
        $json = file_get_contents("https://api.mojang.com/users/profiles/minecraft/" . $name);
        $profile = json_decode($json, true);
        $this->sql->query("INSERT INTO blockhound_players_names(uuid, name, date_cached) VALUES ('" . $profile["id"] . "', '" . $name . "', '" . $now . "')");
        return $profile["name"];
    }

}
