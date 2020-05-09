<?php
require_once 'C:\xampp\htdocs\TestProject2\Faker-master\Faker-master\src\autoload.php';

// create database connection

$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "testbowling"; 

$con = mysql_connect($dbhost, $dbuser, $dbpassword);

if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbname, $con);


// Create arrays to access to generate random entries
$faker = Faker\Factory::create();

$formatArray = array("4-man Team", "3-man Team", "Doubles", "Solo", "Adult/Youth");

$leagueArray = array("Thurs 9-Pin No Tap", "Saturday Star Rollers", "Don Wagoner Memorial", "Dirty Dozen", "Summer PBA Experience", "8 Buck Chucks", "Lousy Bowlers", "Get Your Gear League", "Traders");

$competitionNameArray = array("League", "Charity Bowl", "Dance With the Pins Tournament", "Spare No Strike Tournament", "Casual Bowl Tournament", "Lord of the Pins Tournament", "Steam Rollers Tournament", "The Bowling Stones Tournament", "Ball Busters Tournament", "Les Miserabowls Tournament", "Twisters Tournament", "Pinny Lane Tournament", "Fort Knocks Tournament", "Thats What Pins are For Tournament", "Dudes and Dudettes Tournament", "Balls Out Tournament", "Split Head Tournament", "Overdrive Bowlers Tournament", "Fingers and Gutters Tournament", "Frickin Ten Pin Tournament", "Spare Despair Tournament", "Here 4 Beer Tournament", "Wicked Pins Tournament", "Will Strike if Provoked Tournament", "Scratchers Tournament", "Gutter Riders Tournament", "Pinzee Lohan Tournament", "Thank God Were Bowling Tournament", "Night Shift Tournament", "Bowl You Over Tournament", "I Cant Believe Its Not Gutter Tournament", "Rock-n-Bowlers Tournament", "Club 300 Tournament", "Brew Crew Tournament", "Bowl Me Over Tournament", "Striking Power Tournament", "Ball-istics Tournament", "Pinny For Your Thoughts Tournament", "Gutter Humiliation Tournament", "The Leftovers Tournament", "Obsessive Combowlsive Tournament", "Pin Pricks Tournament", "Balls of Thunder Tournament", "Incredibowl Hulks Tournament");

$teamNameArray = array("The Incredibowl Hulks", "Bowling Thunder", "Brew Crew", "Elbow Benders", "Livin on a Spare", "Pin Pushers", "Ten In Da Pit", "Split Happens", "Splitz Season", "Pocket Pounders", "Spare Me", "Bi-Coastal Rollers", "Snakes on a Lane", "Bowl Movements", "Glory Bowl", "Gutter Gang", "King Pins", "Lucky Strike", "Holy Rollers", "Phantom Strikers", "Mortal Pins");

/********************/

// Generate 'bowler' data ////////////
$bowlersArray = array();
$b_idArray = array();

for ($i = 0; $i < 60; $i++) {
    $b_id = $faker->numberBetween($min = 0, $max = 9999) . "-" . $faker->unique()->numberBetween($min = 0, $max = 9999);
    array_push($b_idArray, $b_id);  // add to b_id array
}

// fill bowlersArray with valid entries for each field
foreach($b_idArray as $b_id) {
    $firstName = $faker->unique()->firstName;
    $formattedFirstName = str_replace("'", "", $firstName);
    
    $lastName = $faker->unique()->lastName;
    $formattedLastName = str_replace("'", "", $lastName);
    
    $name = $formattedFirstName . " " . $formattedLastName;
    $formattedName = str_replace("'", "", $name);
    
    $email= $formattedFirstName . $formattedLastName . $faker->randomDigit . "@example.com";
    $formattedEmail = str_replace("'", "", $email);
    
    array_push($bowlersArray, array($b_id, $formattedName, $formattedEmail));
}

// INSERT bowlers into database
$table = "bowler";

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $bowlerFields[] = $record['0'];
}

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($bowlerFields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "`$bowlerFields[$i]`";
    } else {
        $fieldsSqlStr .= ", `$bowlerFields[$i]`";
    }
}

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($bowlersArray); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($bowlersArray) - 1) {
        $valuesSqlStr .= "('" . $bowlersArray[$i][0] . "', '" . $bowlersArray[$i][1] . "', '" . $bowlersArray[$i][2] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $bowlersArray[$i][0] . "', '" . $bowlersArray[$i][1] . "', '" . $bowlersArray[$i][2] . "')";
    }
}


// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Bowlers entered successfully!<br>";
} else {
    $error = mysql_error($con);
    header("refresh:0; url=error.php?error=$error");
}

/********************/




// Generate 'team' data //////////////
$teamsArray1 = array();   // Contains full entries for database
$teamsArray2 = array();   // Contains full entries for database
$teamYearArray = array();   // Contains unique combos for primary key
$bowlingCentersArray = array(); // Contains created 'locations' for entries in `competition` and `competed_in` tables

// Create unique combos of Team Name and Year for primary key
for ($i = 0; $i < 150; $i++) {
    $team_name = $faker->randomElement($teamNameArray); // fetch random team name
    $year = rand(2000, 2020);   // generate random year for team
    
    // only add pair to array if combination not already generated
    if (!in_array(array($team_name, $year), $teamYearArray)) {
        array_push($teamYearArray, array($team_name, $year));    // add to team_name/year combo to array
    }
}

// Build Team arrays, too large had to split in two
// Teams1
for ($i = 0; $i < count($teamYearArray) / 2; $i++) {
    
    $league = $faker->randomElement($leagueArray);
    
    $city = $faker->unique()->city; // just used to generate bowling centers
    $formattedCity = str_replace("'", "", $city);   // Remove single quotes to avoid sql error
    $bowling_center = $formattedCity . " Lanes, " . $formattedCity . ", " . $faker->state;
    array_push($bowlingCentersArray, $bowling_center);  // add to bowling_centers array
    
    $coach = $faker->name;
    $formattedCoach = str_replace("'", "", $coach); // Remove single quotes to avoid sql error
    
    $sponsor = $faker->company;
    $formattedSponsor = str_replace("'", "", $sponsor); // Remove single quotes to avoid sql error
    
    // Fill teams array with attributes
    array_push($teamsArray1, array($teamYearArray[$i][0], $teamYearArray[$i][1], $league, $bowling_center, $formattedCoach, $formattedSponsor));
}

// Teams2
for ($i = (count($teamYearArray) / 2) + 1; $i < count($teamYearArray); $i++) {
    
    $league = $faker->randomElement($leagueArray);
    
    $city = $faker->unique()->city; // just used to generate bowling centers
    $formattedCity = str_replace("'", "", $city);   // Remove single quotes to avoid sql error
    $bowling_center = $formattedCity . " Lanes, " . $formattedCity . ", " . $faker->state;
    array_push($bowlingCentersArray, $bowling_center);  // add to bowling_centers array
    
    $coach = $faker->name;
    $formattedCoach = str_replace("'", "", $coach); // Remove single quotes to avoid sql error
    
    $sponsor = $faker->company;
    $formattedSponsor = str_replace("'", "", $sponsor); // Remove single quotes to avoid sql error
    
    // Fill teams array with attributes
    array_push($teamsArray2, array($teamYearArray[$i][0], $teamYearArray[$i][1], $league, $bowling_center, $formattedCoach, $formattedSponsor));
}

// Inserting into Database
$table = "team";

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $teamFields[] = $record['0'];
}

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($teamFields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "`$teamFields[$i]`";
    } else {
        $fieldsSqlStr .= ", `$teamFields[$i]`";
    }
}

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

/* SQL entry too big as one, split teams entries in 2 */

/* * * 1 * * */
// Build sql string for input values
for($i = 0; $i < count($teamsArray1); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($teamsArray1) - 1) {
        $valuesSqlStr .= "('" . $teamsArray1[$i][0] . "', " . $teamsArray1[$i][1] . ", '" . $teamsArray1[$i][2] . "', '" . $teamsArray1[$i][3] . "', '" . $teamsArray1[$i][4] . "', '" . $teamsArray1[$i][5] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $teamsArray1[$i][0] . "', " . $teamsArray1[$i][1] . ", '" . $teamsArray1[$i][2] . "', '" . $teamsArray1[$i][3] . "', '" . $teamsArray1[$i][4] . "', '" . $teamsArray1[$i][5] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Teams 1 entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Team(1): " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

$valuesSqlStr = ""; // Reset values string for Teams(2)

/* * * 2 * * */
// Build sql string for input values
for($i = 0; $i < count($teamsArray2); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($teamsArray2) - 1) {
        $valuesSqlStr .= "('" . $teamsArray2[$i][0] . "', " . $teamsArray2[$i][1] . ", '" . $teamsArray2[$i][2] . "', '" . $teamsArray2[$i][3] . "', '" . $teamsArray2[$i][4] . "', '" . $teamsArray2[$i][5] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $teamsArray2[$i][0] . "', " . $teamsArray2[$i][1] . ", '" . $teamsArray2[$i][2] . "', '" . $teamsArray2[$i][3] . "', '" . $teamsArray2[$i][4] . "', '" . $teamsArray2[$i][5] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Teams 2 entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Team(2): " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

// ENTRIES ADDED TO DATABASE

/********************/

// Generate 'member_of' data //////////////////
$member_ofArray1 = array();
$member_ofArray2 = array();
$member_ofArray3 = array();

$numOfTeams = count($teamYearArray);

/* Split into three arrays for three sql statements due to size */

/* * * 1 * * */
for ($i = 0; $i < 80; $i++) {
    $random = rand(0, $numOfTeams - 1); // Generate random index to access teamYearArray
    
    $b_id = $faker->randomElement($b_idArray);  // Grab a generated b_id
    $team_name = $teamYearArray[$random][0];   // Grab a generated team_name
    $year = $teamYearArray[$random][1];        // Grab a generated team_year
    
    // only add pair to array if combination not already generated
    if (!in_array(array($b_id, $team_name, $year), $member_ofArray1)) {
        array_push($member_ofArray1, array($b_id, $team_name, $year));
    }
}
/* * * 2 * * */
for ($i = 0; $i < 80; $i++) {
    $random = rand(0, $numOfTeams - 1); // Generate random index to access teamYearArray
    
    $b_id = $faker->randomElement($b_idArray);  // Grab a generated b_id
    $team_name = $teamYearArray[$random][0];   // Grab a generated team_name
    $year = $teamYearArray[$random][1];        // Grab a generated team_year
    
    // only add pair to array if combination not already generated
    if (!in_array(array($b_id, $team_name, $year), $member_ofArray1) && !in_array(array($b_id, $team_name, $year), $member_ofArray2)) {
        array_push($member_ofArray2, array($b_id, $team_name, $year));
    }
}
/* * * 3 * * */
for ($i = 0; $i < 80; $i++) {
    $random = rand(0, $numOfTeams - 1); // Generate random index to access teamYearArray
    
    $b_id = $faker->randomElement($b_idArray);  // Grab a generated b_id
    $team_name = $teamYearArray[$random][0];   // Grab a generated team_name
    $year = $teamYearArray[$random][1];        // Grab a generated team_year
    
    // only add pair to array if combination not already generated
    if (!in_array(array($b_id, $team_name, $year), $member_ofArray1) && !in_array(array($b_id, $team_name, $year), $member_ofArray2) && !in_array(array($b_id, $team_name, $year), $member_ofArray3)) {
        array_push($member_ofArray3, array($b_id, $team_name, $year));
    }
}

/* * * Inserting into Database * * */
$table = "member_of";

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $memberFields[] = $record['0'];
}

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input columns
for($i = 0; $i < count($memberFields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "`$memberFields[$i]`";
    } else {
        $fieldsSqlStr .= ", `$memberFields[$i]`";
    }
}

/* * * 1 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($member_ofArray1); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($member_ofArray1) - 1) {
        $valuesSqlStr .= "('" . $member_ofArray1[$i][0] . "', '" . $member_ofArray1[$i][1] . "', '" . $member_ofArray1[$i][2] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $member_ofArray1[$i][0] . "', '" . $member_ofArray1[$i][1] . "', '" . $member_ofArray1[$i][2] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Member_of(1) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Member_of: " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

/* * * 2 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($member_ofArray2); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($member_ofArray2) - 1) {
        $valuesSqlStr .= "('" . $member_ofArray2[$i][0] . "', '" . $member_ofArray2[$i][1] . "', '" . $member_ofArray2[$i][2] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $member_ofArray2[$i][0] . "', '" . $member_ofArray2[$i][1] . "', '" . $member_ofArray2[$i][2] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Member_of(2) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Member_of: " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

/* * * 3 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($member_ofArray3); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($member_ofArray3) - 1) {
        $valuesSqlStr .= "('" . $member_ofArray3[$i][0] . "', '" . $member_ofArray3[$i][1] . "', '" . $member_ofArray3[$i][2] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $member_ofArray3[$i][0] . "', '" . $member_ofArray3[$i][1] . "', '" . $member_ofArray3[$i][2] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Member_of(3) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Member_of: " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}


/********************/

// Generate 'competition' data ////////////////
$compArray1 = array();
$compArray2 = array();
$compArray3 = array();

/* * * 1 * * */
for ($i = 0; $i < 80; $i++) {
    $competition_name = $faker->randomElement($competitionNameArray);   // Use random element from competition name array

    // Generate and format random date in last 20 years
    $dt = $faker->dateTimeBetween($startDate = '-20 years', $endDate = 'now');
    $date = $dt->format("Y-m-d");

    $location = $faker->randomElement($bowlingCentersArray);     // Use random element from bowling_centers array

    $format = $faker->randomElement($formatArray);  // Use random element from format array
    
    // only add to array if combination not already generated
    if (!in_array(array($competition_name, $date, $location, $format), $compArray1)) {
        array_push($compArray1, array($competition_name, $date, $location, $format));
    }
}

/* * * 2 * * */
for ($i = 0; $i < 80; $i++) {
    $competition_name = $faker->randomElement($competitionNameArray);   // Use random element from competition name array

    // Generate and format random date in last 20 years
    $dt = $faker->dateTimeBetween($startDate = '-20 years', $endDate = 'now');
    $date = $dt->format("Y-m-d");

    $location = $faker->randomElement($bowlingCentersArray);     // Use random element from bowling_centers array

    $format = $faker->randomElement($formatArray);  // Use random element from format array
    
    // only add to array if combination not already generated
    if (!in_array(array($competition_name, $date, $location, $format), $compArray1) && !in_array(array($competition_name, $date, $location, $format), $compArray2)) {
        array_push($compArray2, array($competition_name, $date, $location, $format));
    }
}

/* * * 3 * * */
for ($i = 0; $i < 80; $i++) {
    $competition_name = $faker->randomElement($competitionNameArray);   // Use random element from competition name array

    // Generate and format random date in last 20 years
    $dt = $faker->dateTimeBetween($startDate = '-20 years', $endDate = 'now');
    $date = $dt->format("Y-m-d");

    $location = $faker->randomElement($bowlingCentersArray);     // Use random element from bowling_centers array

    $format = $faker->randomElement($formatArray);  // Use random element from format array
    
    // only add to array if combination not already generated
    if (!in_array(array($competition_name, $date, $location, $format), $compArray1) && !in_array(array($competition_name, $date, $location, $format), $compArray2) && !in_array(array($competition_name, $date, $location, $format), $compArray3)) {
        array_push($compArray3, array($competition_name, $date, $location, $format));
    }
}

/* * * Inserting into Database * * */
$table = "competition";

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $compFields[] = $record['0'];
}

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input columns
for($i = 0; $i < count($compFields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "`$compFields[$i]`";
    } else {
        $fieldsSqlStr .= ", `$compFields[$i]`";
    }
}

/* * * 1 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($compArray1); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($compArray1) - 1) {
        $valuesSqlStr .= "('" . $compArray1[$i][0] . "', '" . $compArray1[$i][1] . "', '" . $compArray1[$i][2] . "', '" . $compArray1[$i][3] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $compArray1[$i][0] . "', '" . $compArray1[$i][1] . "', '" . $compArray1[$i][2] . "', '" . $compArray1[$i][3] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Competition(1) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Competition: " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

/* * * 2 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($compArray2); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($compArray2) - 1) {
        $valuesSqlStr .= "('" . $compArray2[$i][0] . "', '" . $compArray2[$i][1] . "', '" . $compArray2[$i][2] . "', '" . $compArray2[$i][3] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $compArray2[$i][0] . "', '" . $compArray2[$i][1] . "', '" . $compArray2[$i][2] . "', '" . $compArray2[$i][3] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Competition(2) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Competition: " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

/* * * 3 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($compArray3); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($compArray3) - 1) {
        $valuesSqlStr .= "('" . $compArray3[$i][0] . "', '" . $compArray3[$i][1] . "', '" . $compArray3[$i][2] . "', '" . $compArray3[$i][3] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $compArray3[$i][0] . "', '" . $compArray3[$i][1] . "', '" . $compArray3[$i][2] . "', '" . $compArray3[$i][3] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Competition(3) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Competition: " . $error . "<br><br>";
    header("refresh:10; url=error.php?error=$error");
}

/********************/

// Generate 'competed_in' data ////////////////
$competedArray1 = array();
$competedArray2 = array();
$competedArray3 = array();

$bidDateCombos = array();

/* * * 1 * * */
for ($i = 0; $i < 80; $i++) {
    // Random numbers used as indices to grab random elements
    $randMember = rand(0, count($member_ofArray1) - 1);
    $randComp = rand(0, count($compArray1) - 1);
    
    $b_id = $member_ofArray1[$randMember][0];          // generated from 'member_of'
    $date = $compArray1[$randComp][1];                 // generated from 'competition'
    $team_name = $member_ofArray1[$randMember][1];     // generated from 'member_of'
    $competition_name = $compArray1[$randComp][0];     // generated from 'competition'
    $location = $compArray1[$randComp][2];             // generated from 'competition'
    $rank = "NULL";
    $winnings = "NULL";
    
    // only add to array if combination not already generated
    if (!in_array(array($b_id, $date), $bidDateCombos)) {
        array_push($competedArray1, array($b_id, $date, $team_name, $competition_name, $location, $rank, $winnings));
    }
    
    array_push($bidDateCombos, array($b_id, $date));
}

/* * * 2 * * */
for ($i = 0; $i < 80; $i++) {
    // Random numbers used as indices to grab random elements
    $randMember = rand(0, count($member_ofArray2) - 1);
    $randComp = rand(0, count($compArray2) - 1);
    
    $b_id = $member_ofArray2[$randMember][0];          // generated from 'member_of'
    $date = $compArray2[$randComp][1];                 // generated from 'competition'
    $team_name = $member_ofArray2[$randMember][1];     // generated from 'member_of'
    $competition_name = $compArray2[$randComp][0];     // generated from 'competition'
    $location = $compArray2[$randComp][2];             // generated from 'competition'
    $rank = "NULL";
    $winnings = "NULL";
    
    // only add to array if combination not already generated
    if (!in_array(array($b_id, $date), $bidDateCombos)) {
        array_push($competedArray2, array($b_id, $date, $team_name, $competition_name, $location, $rank, $winnings));
    }
    
    array_push($bidDateCombos, array($b_id, $date));
}

/* * * 3 * * */
for ($i = 0; $i < 80; $i++) {
    // Random numbers used as indices to grab random elements
    $randMember = rand(0, count($member_ofArray3) - 1);
    $randComp = rand(0, count($compArray3) - 1);
    
    $b_id = $member_ofArray3[$randMember][0];          // generated from 'member_of'
    $date = $compArray3[$randComp][1];                 // generated from 'competition'
    $team_name = $member_ofArray3[$randMember][1];     // generated from 'member_of'
    $competition_name = $compArray3[$randComp][0];     // generated from 'competition'
    $location = $compArray3[$randComp][2];             // generated from 'competition'
    $rank = "NULL";
    $winnings = "NULL";
    
    // only add to array if combination not already generated
    if (!in_array(array($b_id, $date), $bidDateCombos)) {
        array_push($competedArray3, array($b_id, $date, $team_name, $competition_name, $location, $rank, $winnings));
    }
    
    array_push($bidDateCombos, array($b_id, $date));
}

/* * * Inserting into Database * * */
$table = "competed_in";

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $competedFields[] = $record['0'];
}

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input columns
for($i = 0; $i < count($competedFields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "`$competedFields[$i]`";
    } else {
        $fieldsSqlStr .= ", `$competedFields[$i]`";
    }
}

/* * * 1 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($competedArray1); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($competedArray1) - 1) {
        $valuesSqlStr .= "('" . $competedArray1[$i][0] . "', '" . $competedArray1[$i][1] . "', '" . $competedArray1[$i][2] . "', '" . $competedArray1[$i][3] . "', '" . $competedArray1[$i][4] . "', '" . $competedArray1[$i][5] . "', '" . $competedArray1[$i][6] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $competedArray1[$i][0] . "', '" . $competedArray1[$i][1] . "', '" . $competedArray1[$i][2] . "', '" . $competedArray1[$i][3] . "', '" . $competedArray1[$i][4] . "', '" . $competedArray1[$i][5] . "', '" . $competedArray1[$i][6] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Competed In(1) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Competed In (1): " . $error . "<br><br>";
//    header("refresh:20; url=error.php?error=$error");
}

/* * * 2 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($competedArray2); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($competedArray2) - 1) {
        $valuesSqlStr .= "('" . $competedArray2[$i][0] . "', '" . $competedArray2[$i][1] . "', '" . $competedArray2[$i][2] . "', '" . $competedArray2[$i][3] . "', '" . $competedArray2[$i][4] . "', '" . $competedArray2[$i][5] . "', '" . $competedArray2[$i][6] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $competedArray2[$i][0] . "', '" . $competedArray2[$i][1] . "', '" . $competedArray2[$i][2] . "', '" . $competedArray2[$i][3] . "', '" . $competedArray2[$i][4] . "', '" . $competedArray2[$i][5] . "', '" . $competedArray2[$i][6] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Competed In(2) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Competed In (2): " . $error . "<br><br>";
//    header("refresh:20; url=error.php?error=$error");
}

/* * * 3 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($competedArray3); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($competedArray3) - 1) {
        $valuesSqlStr .= "('" . $competedArray3[$i][0] . "', '" . $competedArray3[$i][1] . "', '" . $competedArray3[$i][2] . "', '" . $competedArray3[$i][3] . "', '" . $competedArray3[$i][4] . "', '" . $competedArray3[$i][5] . "', '" . $competedArray3[$i][6] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $competedArray3[$i][0] . "', '" . $competedArray3[$i][1] . "', '" . $competedArray3[$i][2] . "', '" . $competedArray3[$i][3] . "', '" . $competedArray3[$i][4] . "', '" . $competedArray3[$i][5] . "', '" . $competedArray3[$i][6] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Competed In(3) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Competed In (3): " . $error . "<br><br>";
//    header("refresh:20; url=error.php?error=$error");
}


/********************/

// Generate 'scores' data ///////////////

$scoresArray1 = array();
$scoresArray2 = array();
$scoresArray3 = array();
$bidDates = array();

/* * * 1 * * */
for ($i = 0; $i < 80; $i++) {
    $randomCompeted = rand(0, count($competedArray1) - 1);
    
    $b_id = $competedArray1[$randomCompeted][0];     // generated from 'competed_in'
    $date = $competedArray1[$randomCompeted][1];     // generated from 'competed_in' 
    
    $game1 = $faker->numberBetween($min = 100, $max = 300);
    $game2 = $faker->numberBetween($min = 100, $max = 300);
    $game3 = $faker->numberBetween($min = 100, $max = 300);
    
    if(!in_array(array($b_id, $date), $bidDates)) {
        array_push($scoresArray1, array($b_id, $date, $game1, $game2, $game3));
    }
    
    array_push($bidDates, array($b_id, $date));
}

/* * * 2 * * */
for ($i = 0; $i < 80; $i++) {
    $randomCompeted = rand(0, count($competedArray2) - 1);
    
    $b_id = $competedArray2[$randomCompeted][0];     // generated from 'competed_in'
    $date = $competedArray2[$randomCompeted][1];     // generated from 'competed_in' 
    
    $game1 = $faker->numberBetween($min = 100, $max = 300);
    $game2 = $faker->numberBetween($min = 100, $max = 300);
    $game3 = $faker->numberBetween($min = 100, $max = 300);
    
    if(!in_array(array($b_id, $date), $bidDates)) {
        array_push($scoresArray2, array($b_id, $date, $game1, $game2, $game3));
    }
    
    array_push($bidDates, array($b_id, $date));
}

/* * * 3 * * */
for ($i = 0; $i < 80; $i++) {
    $randomCompeted = rand(0, count($competedArray3) - 1);
    
    $b_id = $competedArray3[$randomCompeted][0];     // generated from 'competed_in'
    $date = $competedArray3[$randomCompeted][1];     // generated from 'competed_in' 
    
    $game1 = $faker->numberBetween($min = 100, $max = 300);
    $game2 = $faker->numberBetween($min = 100, $max = 300);
    $game3 = $faker->numberBetween($min = 100, $max = 300);
    
    if(!in_array(array($b_id, $date), $bidDates)) {
        array_push($scoresArray3, array($b_id, $date, $game1, $game2, $game3));
    }
    
    array_push($bidDates, array($b_id, $date));
}

/* * * Inserting into Database * * */
$table = "scores";

$sql = "SHOW COLUMNS FROM $table";
$result1 = mysql_query($sql);

while ($record = mysql_fetch_array($result1)) {
    $scoresFields[] = $record['0'];
}

// String to insert fields into sql statement to complete it
$fieldsSqlStr = "";

// Build sql string for input columns
for($i = 0; $i < count($scoresFields); $i++) {
    if($i == 0) {   // Don't insert starting comma (, ) if it's the first iteration
        $fieldsSqlStr .= "`$scoresFields[$i]`";
    } else {
        $fieldsSqlStr .= ", `$scoresFields[$i]`";
    }
}

/* * * 1 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($scoresArray1); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($scoresArray1) - 1) {
        $valuesSqlStr .= "('" . $scoresArray1[$i][0] . "', '" . $scoresArray1[$i][1] . "', '" . $scoresArray1[$i][2] . "', '" . $scoresArray1[$i][3] . "', '" . $scoresArray1[$i][4] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $scoresArray1[$i][0] . "', '" . $scoresArray1[$i][1] . "', '" . $scoresArray1[$i][2] . "', '" . $scoresArray1[$i][3] . "', '" . $scoresArray1[$i][4] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Scores(1) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Scores (1): " . $error . "<br><br>";
    echo $sql;
//    header("refresh:20; url=error.php?error=$error");
}

/* * * 2 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($scoresArray2); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($scoresArray2) - 1) {
        $valuesSqlStr .= "('" . $scoresArray2[$i][0] . "', '" . $scoresArray2[$i][1] . "', '" . $scoresArray2[$i][2] . "', '" . $scoresArray2[$i][3] . "', '" . $scoresArray2[$i][4] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $scoresArray2[$i][0] . "', '" . $scoresArray2[$i][1] . "', '" . $scoresArray2[$i][2] . "', '" . $scoresArray2[$i][3] . "', '" . $scoresArray2[$i][4] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Scores(2) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Scores (2): " . $error . "<br><br>";
    echo $sql;
//    header("refresh:20; url=error.php?error=$error");
}

/* * * 3 * * */

// String to insert values into sql statement to complete it
$valuesSqlStr = "";

// Build sql string for input values
for($i = 0; $i < count($scoresArray3); $i++) {
    // if not the last value entry add a comma at the end
    if($i != count($scoresArray3) - 1) {
        $valuesSqlStr .= "('" . $scoresArray3[$i][0] . "', '" . $scoresArray3[$i][1] . "', '" . $scoresArray3[$i][2] . "', '" . $scoresArray3[$i][3] . "', '" . $scoresArray3[$i][4] . "'), ";
    } else {
        $valuesSqlStr .= "('" . $scoresArray3[$i][0] . "', '" . $scoresArray3[$i][1] . "', '" . $scoresArray3[$i][2] . "', '" . $scoresArray3[$i][3] . "', '" . $scoresArray3[$i][4] . "')";
    }
}

// need to put in the values from input cells here in sql statement
$sql = "INSERT INTO $table ($fieldsSqlStr) VALUES $valuesSqlStr;";

// Execute sql statement and refresh if successful, error message if unsucessful
if(mysql_query($sql, $con)) {
    echo "Scores(3) entries entered successfully!<br>";
} else {
    $error = mysql_error($con);
    echo "Error in Scores (3): " . $error . "<br><br>";
    echo $sql;
//    header("refresh:20; url=error.php?error=$error");
}



?>