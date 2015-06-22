<?php

class functions {

    private $pdo;

    public function __construct() {
        $config_string = file_get_contents("../config.json");
        $config = json_decode($config_string, TRUE);
        $dbhost = $config["database"]["host"];
        $dbuser = $config["database"]["user"];
        $dbpass = $config["database"]["pass"];
        $dbdb = $config["database"]["db"];
        try {
            $this->pdo = new PDO("mysql:host=$dbhost;dbname=$dbdb", $dbuser, $dbpass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function __destruct() {
        $this->pdo = null;
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
        $query .= "1=1 ORDER BY id $order LIMIT :start, :count";
        $prepared = $this->pdo->prepare($query);

        if ($name != "") {
            $prepared->bindParam(":uuid", ($this->getUUID($name)));
        }
        if ($date != "" && $timeRadius != "") {
            $prepared->bindParam(":dateLow", (date("Y-m-d H:i:s", (strtotime($date) - strtotime($timeRadius, strtotime($date))))));
            $prepared->bindParam(":dateHigh", (date("Y-m-d H:i:s", (strtotime($date) + strtotime($timeRadius, strtotime($date))))));
        }
        if ($world != "") {
            $prepared->bindParam(":world", (intval($world)), PDO::PARAM_INT);
        }
        if ($coordsRadius != "") {
            if ($x != "") {
                $xLow = intval($x) - intval($coordsRadius);
                $prepared->bindParam(":XLow", ($xLow));
                $xHigh = intval($x) + intval($coordsRadius);
                $prepared->bindParam(":XHigh", ($xHigh));
            }
            if ($y != "") {
                $yLow = intval($y) - intval($coordsRadius);
                $prepared->bindParam(":YLow", ($yLow));
                $yHigh = intval($y) + intval($coordsRadius);
                $prepared->bindParam(":YHigh", ($yHigh));
            }
            if ($z != "") {
                $zLow = intval($z) - intval($coordsRadius);
                $prepared->bindParam(":ZLow", ($zLow));
                $zHigh = intval($z) + intval($coordsRadius);
                $prepared->bindParam(":ZHigh", ($zHigh));
            }
        } else {
            if ($x != "") {
                $prepared->bindParam(":x", (intval($x)));
            }
            if ($x != "") {
                $prepared->bindParam(":y", (intval($y)));
            }
            if ($x != "") {
                $prepared->bindParam(":z", (intval($z)));
            }
        }
        if ($action != "") {
            $action .= "%";
            $prepared->bindParam(":action", ($action));
        }
        $prepared->bindParam(":start", (intval($start)), PDO::PARAM_INT);
        $prepared->bindParam(":count", (intval($count)), PDO::PARAM_INT);
        return $prepared;
    }

    // ?start=&name=&date=&X=&Y=&Z=&order=&world=&timeRadius=&coordsRadius=&action=&count=&submit=
    public function getRecords($query) {
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_NUM);
        $html = "";
        foreach ($result as $row) {
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
        $result = $this->pdo->query("SELECT * FROM blockhound_players_names WHERE uuid='" . $uuid . "'");
        $now = date("Y-m-d H:i:s");
        if ($result != null) {
            $row = $result->fetchAll(PDO::FETCH_NUM)[0];
            if (strtotime($now) - strtotime($row[2]) < 604800) {
                return $row[1];
            } else {
                $this->pdo->query("DELETE FROM blockhound_players_names WHERE uuid='" . $uuid . "'");
            }
        }
        $json = file_get_contents("https://sessionserver.mojang.com/session/minecraft/profile/" . $uuid);
        $profile = json_decode($json, true);
        $this->pdo->query("INSERT INTO blockhound_players_names(uuid, name, date_cached) VALUES ('" . $uuid . "', '" . $profile["name"] . "', '" . $now . "')");
        return "<span style='color: #99ff99;'> " . $profile["name"] . "</span>";
    }

    public function getUUID($name) {
        $result = $this->pdo->query("SELECT * FROM blockhound_players_names WHERE name='" . $name . "'");
        $now = date("Y-m-d H:i:s");
        if ($result != null) {
            $row = $result->fetchAll(PDO::FETCH_NUM)[0];
            if (strtotime($now) - strtotime($row[2]) < 604800) {
                return $row[0];
            } else {
                $this->pdo->query("DELETE FROM blockhound_players_names WHERE name='" . $name . "'");
            }
        }
        $json = file_get_contents("https://api.mojang.com/users/profiles/minecraft/" . $name);
        $profile = json_decode($json, true);
        $this->pdo->query("INSERT INTO blockhound_players_names(uuid, name, date_cached) VALUES ('" . $profile["id"] . "', '" . $name . "', '" . $now . "')");
        return $profile["name"];
    }

}
