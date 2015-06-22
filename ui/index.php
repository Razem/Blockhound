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
        <script type="text/javascript" src="./js.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                main();
            });</script>
    </head>

    <body>
        <div id="outside">
            <div id="inside">
                <form action="index.php" method="GET">
                    <input type="text" name="start" value="0" style ="display: none;" />
                    <table>
                        <tbody>
                            <tr>
                                <td>NAME</td><td>DATE & TIME</td><td>COORDS</td><td>ORDER</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="name" />
                                </td>
                                <td>
                                    <input class="date" type="text" name="date" />
                                </td>
                                <td>
                                    <input class="coord" type="text" name="X" placeholder="X" /><input class="coord" type="text" name="Y" placeholder="Y" /><input class="coord" type="text" name="Z" placeholder="Z" />
                                </td>
                                <td>
                                    <div id="radioset">
                                        <input type="radio" id="radio1" name="order" checked="checked" value="DESC" /><label for="radio1">New</label><input type="radio" id="radio2" name="order" value="ASC" /><label for="radio2">Old</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>WORLD</td><td>TIME RADIUS</td><td>COORDS RADIUS</td><td>ACTION</td>
                            </tr>
                            <tr>
                                <td>
                                    <select id="world" name="world">
                                        <option>Overworld</option>
                                        <option>Nether</option>
                                        <option>End</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="time" type="text" name="timeRadius" />
                                </td>
                                <td>
                                    <input type="text" name="coordsRadius" />
                                </td>
                                <td>
                                    <select id="action" name="action">
                                        <option>mineBlock.*</option>
                                        <option>useItem.*</option>
                                        <option>killEntity.*</option>
                                        <option>deaths</option>
                                        <option>leaveGame</option>
                                        <option>chestOpened</option>
                                        <option>trappedChestTriggered</option>
                                        <option>hopperInspected</option>
                                        <option>dropperInspected</option>
                                        <option>dispenserInspected</option>
                                        <option>furnaceInteraction</option>
                                        <option>brewingstandInteraction</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="text" name="count" value="20" style = "width: 20px;"/>
                    <input class="button" type="submit" name="submit" value="Submit"/>
                </form>
                <table>
                    <thead>
                    <td>ID</td><td>Date</td><td>Name</td><td>World</td><td>Coordinates</td><td>Action</td><td>Count</td>
                    </thead>
                    <tbody id="queries">
                        <?php
                        // ?start=&name=&date=&X=&Y=&Z=&order=&world=&timeRadius=&coordsRadius=&action=&count=&submit=
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
                <button class="button next" onclick="change('prev')">Prev</button>
                <button class="button next" onclick="change('next')">Next</button>
            </div>
        </div>
    </body>

</html>
