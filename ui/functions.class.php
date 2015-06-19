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
        $result = $this->sql->query("SELECT * FROM blockhound_actions ORDER BY id DESC LIMIT ".intval($start).",".intval($count));
        echo $this->sql->error;
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
            $html .= "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$world_type</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td></tr>";
        }
        return $html;
    }

}
