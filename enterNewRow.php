<?php
$table = filter_input(INPUT_GET, "table");
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

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($fields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "$fields[$i]";
    } else {
        $fieldsSqlStr .= ", $fields[$i]";
    }
}

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($fields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $valuesSqlStr .= "'${"val$i"}'";
    } else {
        $valuesSqlStr .= ", '${"val$i"}'";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES ($valuesSqlStr)";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    header("refresh:0; url=index.php?mn=$tableRef");
} else {
    $error = mysql_error($con);
    header("refresh:0; url=error.php?error=$error");
}

?>