<?php
$table = filter_input(INPUT_GET, "table");

// Grab Keys needed appropriate for the specific table
switch($table) {
    case "bowler":      // This has 1 field for primary key
        $primaryKey1 = filter_input(INPUT_GET, "primaryKey1");
        $entry1 = filter_input(INPUT_GET, "entry1");
        break;
    case "competed_in": // These have 2 fields for primary key
    case "scores":
    case "team":
        $primaryKey1 = filter_input(INPUT_GET, "primaryKey1");
        $entry1 = filter_input(INPUT_GET, "entry1");
        $primaryKey2 = filter_input(INPUT_GET, "primaryKey2");
        $entry2 = filter_input(INPUT_GET, "entry2");
        break;
    case "competition": // These have 3 fields for primary key
    case "member_of":
        $primaryKey1 = filter_input(INPUT_GET, "primaryKey1");
        $entry1 = filter_input(INPUT_GET, "entry1");
        $primaryKey2 = filter_input(INPUT_GET, "primaryKey2");
        $entry2 = filter_input(INPUT_GET, "entry2");
        $primaryKey3 = filter_input(INPUT_GET, "primaryKey3");
        $entry3 = filter_input(INPUT_GET, "entry3");
        break;
}

$tableRef = filter_input(INPUT_GET, "tableRef");

$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "bowlingdb";

$con = mysql_connect($dbhost, $dbuser, $dbpassword);

if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbname, $con);

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $fields[] = $record['0'];
}

// Grab and store values in matching iteratively named variables to build sql string
for($i = 0; $i < count($fields); $i++) {
    ${"val$i"} = filter_input(INPUT_GET, "val$i");
}

// String to insert into sql statement to complete it
$setSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($fields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $setSqlStr .= "$fields[$i]='${"val$i"}'";
    } else {
        $setSqlStr .= ", $fields[$i]='${"val$i"}'";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "UPDATE {$table} SET {$setSqlStr} WHERE {$primaryKey}='{$entry}';";

// Create SQL statement appropriate for the table
switch($table) {
    case "bowler":      // This has 1 field for primary key
        $sql = "UPDATE {$table} SET {$setSqlStr} WHERE {$primaryKey1}='{$entry1}'";
        break;
    case "competed_in": // These have 2 fields for primary key
    case "scores":
    case "team":
        $sql = "UPDATE {$table} SET {$setSqlStr} WHERE {$primaryKey1}='{$entry1}' AND {$primaryKey2}='{$entry2}'";
        break;
    case "competition": // These have 3 fields for primary key
    case "member_of":
        $sql = "UPDATE {$table} SET {$setSqlStr} WHERE {$primaryKey1}='{$entry1}' AND {$primaryKey2}='{$entry2}' AND {$primaryKey3}='{$entry3}'";
        break;
}

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    header("refresh:0; url=index.php?mn=$tableRef");
} else {
    $error = mysql_error($con);
    header("refresh:0; url=error.php?error=$error");
}

?>