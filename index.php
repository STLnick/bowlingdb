<?php
$mn = intval(filter_input(INPUT_GET, "mn"));

$cn = intval(filter_input(INPUT_GET, "cn"));

$sort = filter_input(INPUT_GET, "sort");

$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "testbowling";

$con = mysql_connect($dbhost, $dbuser, $dbpassword);

if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbname, $con);

$tblArr = array();
$tblArr[] = "bowler";
$tblArr[] = "competed_in";
$tblArr[] = "competition";
$tblArr[] = "member_of";
$tblArr[] = "scores";
$tblArr[] = "team";

$table_name = $tblArr[$mn];

$sql = "SHOW COLUMNS FROM $table_name";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $fields[] = $record['0'];
}

$optArr = array();
$optArr[] = "Bowler";
$optArr[] = "Competed In";
$optArr[] = "Competition";
$optArr[] = "Member Of";
$optArr[] = "Scores";
$optArr[] = "Team";

$data2dArr = array();

if($sort != 'undefined') {
    $query = "SELECT * FROM  $table_name ORDER BY $fields[$cn] DESC";
} else {
    $query = "SELECT * FROM  $table_name ORDER BY $fields[$cn]";
}


$result2 = mysql_query($query);

while ($line = mysql_fetch_array($result2, MYSQL_ASSOC)) {
    $i = 0;
    foreach ($line as $col_value) {
        $data2dArr[$i][] = $col_value;
        $i++;
    }
}
?>
<html>
    <head>
    	<title>Bowling Database</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bowling.js"></script>
  		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  		<link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&display=swap" rel="stylesheet">
    </head>
    <body onload="setHiddenElements()">
        <div class="container-fluid">
            <div class="container-fluid title" >
                <h1 class="title__text">Bowling Database</h1>
            </div>
            <!-- TABLE BUTTONS SECTION -->
            <!-- Click the button to navigate to regular tables and views -->
            <div class="container-fluid generator text-center">
                <h3>- - Views - -</h3>
                <button type="button" class="btn btn-md btn-danger generatorBtn" onclick="location.href='bigteams.php'">Biggest Teams</button>
                <button type="button" class="btn btn-md btn-danger generatorBtn" onclick="location.href='bigteamsyear.php'">Biggest Teams by Year</button>
                <button type="button" class="btn btn-md btn-danger generatorBtn" onclick="location.href='bowlerview1.php'">Bowlers: Most Matches Played</button>
                <button type="button" class="btn btn-md btn-danger generatorBtn" onclick="location.href='bowlerview2.php'">Bowlers: Top 10 Average Scores</button>
            </div>
            <!-- NAV TABLE -->
            <!-- contains the current table displayed in bold and the other tables as links to display -->
            <table class="table table-bordered text-center">
                <tr class="table_names">
                    <?php
                    for ($i = 0; $i < count($optArr); $i++) {
                        ?>
                        <td class="table_names__cell" style="width: 7em">
                            <?php
                            if ($mn == $i) {
                                ?>
                                <b><?php print $optArr[$i]; ?></b>
                                <?php
                            } else {
                                ?>
                                <a href="index.php?mn=<?php print $i; ?>">
                                    <?php print $optArr[$i]; ?>
                                </a>
                                <?php
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td hidden id="tableTracker"><?php echo $table_name; ?></td>
                </tr>
            </table>
            <hr />
            <!-- CONTENT TABLE -->
            <!-- first row is populated with the fields of the table selected -->
            <table class="table table-bordered table-striped text-center">
                <tr>
                    <?php
                    for ($i = 0; $i < count($fields); $i++) {
                        ?>
                        <th style="width: 8em"><?php print $fields[$i]; ?></th>
                        <?php
                    }
                    ?>
                    <th style="width: 8em"><!-- A blank column to house edit/delete buttons --></th>
                    <td hidden id="keyTracker1"><?php echo $fields[0]; ?></td>
                    <td hidden id="keyTracker2"><?php echo $fields[1]; ?></td>
                    <td hidden id="keyTracker3"><?php echo $fields[2]; ?></td>
                </tr>
                <!-- CONTENT of each row plus the Edit/Delete Buttons in the last cell -->
                <?php
                for ($j = 0; $j < count($data2dArr[0]); $j++) {
                    ?>
                    <tr id="contentRow<?php print $j; ?>">
                        <?php
                        for ($k = 0; $k < count($fields); $k++) {
                            ?>
                            <td><?php print $data2dArr[$k][$j]; ?></td>
                            <?php
                        }
                        ?>
                        <td id="editDeleteCell<?php print $j; ?>">
                            <!-- EDIT BUTTON -->
                            <div class="wrapper">
                                <button type="button" class="btn btn-sm btn_edit" onclick="editBtnClicked(<?php print $j; ?>)" id="editBtn<?php print $j ?>">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                            </div>
                            <!-- DELETE BUTTON -->
                            <div class="wrapper">
                                <button type="button" class="btn btn-sm btn_delete" onclick="deleteBtnClicked(<?php print $j; ?>)" id="deleteBtn<?php print $j ?>">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </div>
                        </td>
                        
                    </tr>
                    <?php
                }
                ?>
                <!-- SORT BUTTONS -->
                <tr>
                    <?php
                    for ($i = 0; $i < count($fields); $i++) {
                        ?>
                    <td>
                        <button type="button" class="btn btn-sm" onclick="sortCurrentField(<?php print $mn; ?>,<?php print $i; ?>)">
                            <span class="glyphicon glyphicon-arrow-up"></span>
                        </button>
                        <button type="button" class="btn btn-sm" onclick="sortCurrentField(<?php print $mn; ?>,<?php print $i; ?>,<?php print 1; ?>)">
                            <span class="glyphicon glyphicon-arrow-down"></span>
                        </button>
                    </td>
                            <?php
                        }
                        ?>
                </tr>
                <!-- INPUT ROW for entering a New Row or Editing an Existing Row -->
                <tr id="inputRow">
                    <?php
                    for($i = 0; $i < count($fields); $i++) {
                    ?>
                        <td>
                            <input type="text" style="width: 7em" class="inputRowCell" id="inputRowCell<?php print $i; ?>">
                        </td>
                    <?php 
                    }
                    ?>
                    <td>
                        <button type="button" id="actionBtn" class="btn" onclick="actionBtnClicked()">New Row</button>
                    </td>
                    <td hidden id="rowTracker"></td>
                </tr>
            </table>
            <!-- FOOTER -->
            <div class="container-fluid footer">
                <h5>Project Contributors</h5>
                <ul class="footer_content">
                    <li>
                        <h6>Allen Hartig</h6>
                        <p>Email: awh346@umsl.edu</p>
                    </li>
                    <li>
                        <h6>Nick Ray</h6>
                        <p>Email: nrrmbc@umsl.edu</p>
                    </li>
                    <li>
                        <h6>Sam Kinsella</h6>
                        <p>Email: skgfd@umsl.edu</p>
                    </li>
                </ul>
            </div>
        </div>
    </body>
</html>
<?php
mysql_close($con);
?>