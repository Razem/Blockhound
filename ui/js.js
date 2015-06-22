var prev, next;
var values = [];  // 0 - start 1 - name, 2 - date, 3/4/5 - coords, 6 - order, 7 - world, 8 - timeRadius, 9 - coordsRadius, 10 - action, 11 - count, (12 - submit origin)
function main() {
    var items = location.search.substr(1).split("&");
    for (var i = 0; i < items.length; i++) {
        var tmp = items[i].split("=");
        values[i] = tmp[1];
        if (values[i] === null) {
            values[i] = "";
        }
    }
    if (values[6] === "DESC") {
        prev = parseInt(values[0]) + parseInt(values[11]);
        next = parseInt(values[0]) - parseInt(values[11]);
    } else if (values[6] === "ASC") {
        prev = parseInt(values[0]) - parseInt(values[11]);
        next = parseInt(values[0]) + parseInt(values[11]);
    }
    if (prev < 0) {
        prev = 0;
    }
    if (next < 0) {
        next = 0;
    }

    $(".button").button();
    $("#radioset").buttonset();
    $("#world").selectmenu();
    $("#action").selectmenu();
    $(".date").datetimepicker({
        controlType: "select",
        oneLine: true,
        dateFormat: "yy-mm-dd",
        timeFormat: "HH:mm:ss"
    });
    $(".time").timepicker({
        controlType: "select",
        oneLine: true,
        timeFormat: "HH:mm:ss"
    });
}

function change(place) { // ?start=&name=&date=&X=&Y=&Z=&order=&world=&timeRadius=&coordsRadius=&action=&count=&submit=
    if (place === "prev") {
        location.search = "?start=" + prev + "&name=" + values[1] + "&date=" + values[2] + "&X=" + values[3] + "&Y=" + values[4] + "&Z=" + values[5] + "&order=" + values[6] + "&world=" + values[7] + "&timeRadius=" + values[8] + "&coordsRadius=" + values[9] + "&action=" + values[10] + "&count=" + values[11] + "&submit=Prev";
    } else if (place === "next") {
        location.search = "?start=" + next + "&name=" + values[1] + "&date=" + values[2] + "&X=" + values[3] + "&Y=" + values[4] + "&Z=" + values[5] + "&order=" + values[6] + "&world=" + values[7] + "&timeRadius=" + values[8] + "&coordsRadius=" + values[9] + "&action=" + values[10] + "&count=" + values[11] + "&submit=Next";
    }
}