<!DOCTYPE html>
<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
require_once './functions.class.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="author" content="Martin Brychta, martin@brychta.name, http://brychta.name" />
        <link rel="stylesheet" type="text/css" href="css.css" />
        <title>Blockhound</title>
    </head>

    <body>
        <div id="outside">
            <div id="inside">
                <form action="index.php" method="GET">
                    START: <input type="text" name="start" value="0" /><br />
                    COUNT: <input type="text" name="count" value="20" /><br />
                    <input type="submit" name="submit"/>
                </form>
                <table>
                    <thead>
                    <td>id</td><td>date</td><td>uuid</td><td>world type</td><td>X</td><td>Y</td><td>Z</td><td>action name</td><td>action value</td>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET["submit"])) {
                            $start = 0;
                            $count = 0;
                            if (isset($_GET["start"]) && is_string($_GET["start"])) {
                                $start = $_GET["start"];
                            }
                            if (isset($_GET["count"]) && is_string($_GET["count"])) {
                                $count = $_GET["count"];
                            }
                            $f = new functions();
                            echo $f->getRecords($start, $count);
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>

</html>