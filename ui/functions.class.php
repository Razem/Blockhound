<?php

class functions {

    private $sql, $dbhost, $dbuser, $dbpass, $dbdb, $dbport;

    public function __construct() {
        $config = parse_ini_file("config.ini.php");
        $this->dbhost = $config["dbhost"];
        $this->dbuser = $config["dbuser"];
        $this->dbpass = $config["dbpass"];
        $this->dbdb = $config["dbdb"];
        $this->dbport = $config["dbport"];
        $this->sql = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbdb, $this->dbport);
    }

    public function __destruct() {
        $this->sql->close();
    }

    public function getRecords($start, $count) {
        $result = $this->sql->query("SELECT * FROM blockhound_actions ORDER BY id DESC LIMIT $start,$count");
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
