<!DOCTYPE html>
<?php
//error_reporting(E_ALL | E_STRICT);
//ini_set('display_errors', 1);
require_once './functions.class.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="author" content="Martin Brychta, martin@brychta.name, http://brychta.name" />
        <title>[Blockhound]</title>

        <link rel="stylesheet" type="text/css" href="css.css" />
        <link rel="stylesheet" type="text/css" href="./jqueryui/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="./jqueryui/jquery-ui-timepicker-addon.css" />

        <script type="text/javascript" src="./jqueryui/external/jquery/jquery.js"></script>
        <script type="text/javascript" src="./jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="./jqueryui/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var tmp = [];
                var values = [];
                var items = location.search.substr(1).split("&");
                for (var i = 0; i < items.length; i++) {
                    tmp = items[i].split("=");
                    values[i] = parseInt(tmp[1]);
                }
                var prev = values[0] + values[1];
                if (prev < 0) {
                    prev = 0;
                }
                var next = values[0] - values[1];
                if (next < 0) {
                    next = 0;
                }
                $("#changePrev").val(prev);
                $("#changeNext").val(next);
                $("#countPrev").val(values[1]);
                $("#countNext").val(values[1]);

                $(".button").button();
                $("#radioset").buttonset();
                $(".date").datetimepicker({
                    controlType: "select",
                    oneLine: true,
                    dateFormat: "yy-mm-dd",
                    timeFormat: "HH:mm:ss"
                });
            });
        </script>
    </head>

    <body>
        <div id="outside">
            <div id="inside">
                <form action="index.php" method="GET">
                    <table>
                        <thead>
                        <td>START</td><td>COUNT</td><td>NAME</td><td>DATE</td><td>COORD</td><td>RADIUS</td><td>ORDER</td>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="start" value="0" />
                                </td>
                                <td>
                                    <input type="text" name="count" value="20" />
                                </td>
                                <td>
                                    <input type="text" name="name" />
                                </td>
                                <td>
                                    <input class="date" type="text" name="date" />
                                </td>
                                <td>
                                    <input class="coord" type="text" name="X" placeholder="X"><input class="coord" type="text" name="Y" placeholder="Y"><input class="coord" type="text" name="Z" placeholder="Z">
                                </td>
                                <td>
                                    <input type="text" name="radius">
                                </td>
                                <td>
                                    <span id="radioset"><input type="radio" id="radio1" name="order" checked="checked" value="DESC" /><label for="radio1">New</label><input type="radio" id="radio2" name="order" value="ASC" /><label for="radio2">Old</label></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input class="button" type="submit" name="submit" value="Submit"/>
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
                    <input class="button" type="submit" name="submit" value="Prev"/>
                </form>
                <form id="next" action="index.php" method="GET">
                    <input id="changeNext" type="text" name="start" value="" style="display: none;"/>
                    <input id="countNext" type="text" name="count" value="" style="display: none;"/>
                    <input class="button" type="submit" name="submit" value="Next"/>
                </form>
            </div>
        </div>
    </body>

</html>