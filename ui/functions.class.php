<?php

class functions {

    private $sql;

    public function __construct() {
        $config_string = file_get_contents("../config.json");
        $config = json_decode($config_string, TRUE);
        $dbhost = $config["database"]["host"];
        $dbuser = $config["database"]["user"];
        $dbpass = $config["database"]["pass"];
        $dbdb = $config["database"]["db"];
        $this->sql = new mysqli($dbhost, $dbuser, $dbpass, $dbdb, 3306);
    }

    public function __destruct() {
        $this->sql->close();
    }

    public function constructQuery($start, $name, $date, $x, $y, $z, $order, $world, $timeRadius, $coordsRadius, $action, $count) {
        $query = "SELECT * FROM blockhound_actions WHERE ";
        if ($name != "") {
            $query .= "uuid=:uuid AND ";
        }
        if ($date != "" && $timeRadius != "") {
            $query .= "date_action BETWEEN :lowDate AND :highDate AND ";
        }
        if ($world != "") {
            $query .= "world_type=:world AND ";
        }
        if ($coordsRadius != "") {
            if ($x != "") {
                $query .= "pos_x BETWEEN :lowCoord AND :highCoord AND ";
            }
            if ($y != "") {
                $query .= "pos_y BETWEEN :lowCoord AND :highCoord AND ";
            }
            if ($z != "") {
                $query .= "pos_z BETWEEN :lowCoord AND :highCoord AND ";
            }
        } else {
            if ($x != "") {
                $query .= "pos_x=:x AND ";
            }
            if ($y != "") {
                $query .= "pos_y=:y AND ";
            }
            if ($z != "") {
                $query .= "pos_z=:z AND ";
            }
        }
        if ($action != "") {
            $query .= "action_name=:action AND ";
        }
        $query .= "1=1 ";
        if ($order != "") {
            $query .= "ORDER BY id :order ";
        }
        $query .= "LIMIT :start, :count";
    }

    // ?start=&name=&date=&X=&Y=&Z=&order=&world=&timeRadius=&coordsRadius=&action=&count=&submit=
    public function getRecords($query) {
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
