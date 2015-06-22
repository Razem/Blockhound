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
            $query .= "date_action BETWEEN :dateLow AND :dateHigh AND ";
        }
        if ($world != "") {
            $query .= "world_type=:world AND ";
        }
        if ($coordsRadius != "") {
            if ($x != "") {
                $query .= "pos_x BETWEEN :XLow AND :XHigh AND ";
            }
            if ($y != "") {
                $query .= "pos_y BETWEEN :YLow AND :YHigh AND ";
            }
            if ($z != "") {
                $query .= "pos_z BETWEEN :ZLow AND :ZHigh AND ";
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
        $prepared = $this->sql->prepare($query);
        $prepared->bind_param(":uuid", getUUID($name));
        $prepared->bind_param(":dateLow", date("Y-m-d H:i:s", strtotime($date) - strtotime($timeRadius, $date)));
        $prepared->bind_param(":dateHigh", date("Y-m-d H:i:s", strtotime($date) + strtotime($timeRadius, $date)));
        $prepared->bind_param(":world", intval($world));
        $prepared->bind_param(":x", intval($x));
        $prepared->bind_param(":y", intval($y));
        $prepared->bind_param(":z", intval($z));
        $prepared->bind_param(":XLow", intval($x) - intval($coordsRadius));
        $prepared->bind_param(":XHigh", intval($x) + intval($coordsRadius));
        $prepared->bind_param(":YLow", intval($y) - intval($coordsRadius));
        $prepared->bind_param(":YHigh", intval($y) + intval($coordsRadius));
        $prepared->bind_param(":ZLow", intval($z) - intval($coordsRadius));
        $prepared->bind_param(":ZHigh", intval($z) + intval($coordsRadius));
        $prepared->bind_param(":action", $action + '%');
        $prepared->bind_param(":order", $order);
        $prepared->bind_param(":start", intval($start));
        $prepared->bind_param(":count", intval($count));
        return $prepared;
    }

    // ?start=&name=&date=&X=&Y=&Z=&order=&world=&timeRadius=&coordsRadius=&action=&count=&submit=
    public function getRecords($query) {
        $query->execute();
        $result = $query->get_result();
        $html = "";
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $world_type = intval($row[3]);
            if ($world_type === 1) {
                $world_type = "the End";
            } else if ($world_type === -1) {
                $world_type = "the Nether";
            } else if ($world_type === 0) {
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
