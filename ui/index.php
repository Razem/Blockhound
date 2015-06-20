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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <title>Blockhound</title>
        <script type="text/javascript">
            $(document).ready(function () {
                var tmp = [];
                var values = [];
                var items = location.search.substr(1).split("&");
                for (var i = 0; i < items.length; i++) {
                    tmp = items[i].split("=");
                    values[i] = parseInt(tmp[1]);
                }
                var prev = values[0] + values[1] + 1;
                if (prev < 0) {
                    prev = 0;
                }
                var next = values[0] - values[1] - 1;
                if (next < 0) {
                    next = 0;
                }
                $("#changePrev").val(prev);
                $("#changeNext").val(next);
                $("#countPrev").val(values[1]);
                $("#countNext").val(values[1]);
            });
        </script>
    </head>

    <body>
        <div id="outside">
            <div id="inside">
                <form action="index.php" method="GET">
                    START: <input type="text" name="start" value="0" /><br />
                    COUNT: <input type="text" name="count" value="20" /><br />
                    <input type="submit" name="submit" value="Submit"/>
                </form>
                <table>
                    <thead>
                    <td>ID</td><td>Date</td><td>Name</td><td>World</td><td>Coordinations</td><td>Action</td><td>Count</td>
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
                <form id="prev" action="index.php" method="GET">
                    <input id="changePrev" type="text" name="start" value="" style="display: none;"/>
                    <input id="countPrev" type="text" name="count" value="" style="display: none;"/>
                    <input type="submit" name="submit" value="Prev"/>
                </form>
                <form id="next" action="index.php" method="GET">
                    <input id="changeNext" type="text" name="start" value="" style="display: none;"/>
                    <input id="countNext" type="text" name="count" value="" style="display: none;"/>
                    <input type="submit" name="submit" value="Next"/>
                </form>
            </div>
        </div>
    </body>

</html>