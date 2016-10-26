<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Add Actor Info</h1>";
echo "[first name]...[last name]...[sex]...[dob]...[dod]<br>";
$id = 0;
$first_name = "";
$last_name = "";
$sex = "";
$query = "";
$dob = null;
$dod = null;;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $first_name = $_GET["first"];
    $last_name =  $_GET["last"];
    $sex = $_GET["sex"];
    $dob = $_GET["dob"];
    $dod = $_GET["dod"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="first" value="">
    <input type="text" name="last" value="">
    <input type="text" name="sex" value="">
    <input type="date" name="dob" value="">
    <input type="date" name="dod" value="">
    <input type="submit" name="submit" value="submit">
</form>

<?php
echo "<br>";

if (strlen($first_name) != 0) { echo "first name: " . $first_name . "<br>"; }
if (strlen($last_name) != 0) { echo "last name: " . $last_name . "<br>"; }
if (strlen($sex) != 0) { echo "sex: " . $sex . "<br>"; }
echo "dob: " . $dob . "<br>";
echo "dod: " . $dod . "<br>";

//ensure MaxPersonID table is up to date
$maxActorId = "SELECT MAX(id) AS maxID_A FROM Actor limit 1;";
$result = $db->query($maxActorId);
$value = $result->fetch_assoc();
$m_id_a = $value["maxID_A"];
$result->free();

$maxDirectorId = "SELECT MAX(id) AS maxID_D FROM Director limit 1;";
$result = $db->query($maxDirectorId);
$value = $result->fetch_assoc();
$m_id_d = $value["maxID_D"];
$result->free();

$db->query("INSERT INTO MaxPersonID VALUES(" . $m_id_d . ");");
$db->query("INSERT INTO MaxPersonID VALUES(" . $m_id_a . ");");


//get new id from max value in MaxPersonID + 1
$getNewId = "SELECT MAX(id) AS maxId FROM MaxPersonID limit 1;";
$result = $db->query($getNewId);
$value = $result->fetch_assoc();
$id = $value["maxId"] + 1;

if ($id == null) {
    $id = 1;
    echo "no id's found in MaxPersonID table. assigning id value 1<br>";
}
else {
    echo "fetched new id: " . $id . "<br>";
}

//create query to insert new value to actor table
$query = "INSERT INTO Actor VALUES (" . $id . "," 
        . $last_name . "," . $first_name . ","
        . $sex . "," . $dob . "," . $dod . ");";

echo "query: " . $query;

if (strlen($first_name != 0) && strlen($last_name) != 0) {
//run the query to add the actor to the actor table
$db->query($query);

//add the new actor's id to the maxpersonid table
$db->query("INSERT INTO MaxPersonID VALUES(" . $id . ");");
}

$result->free();
?>

<?php
echo "<h1>Add Director Info</h1>";
echo "[first name]...[last name]...[sex]...[dob]...[dod]<br>";
$id = 0;
$first_name = "";
$last_name = "";
$sex = "";
$query = "";
$dob = null;
$dod = null;;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $first_name = $_GET["first"];
    $last_name =  $_GET["last"];
    $sex = $_GET["sex"];
    $dob = $_GET["dob"];
    $dod = $_GET["dod"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="first" value="">
    <input type="text" name="last" value="">
    <input type="text" name="sex" value="">
    <input type="date" name="dob" value="">
    <input type="date" name="dod" value="">
    <input type="submit" name="submit" value="submit">
</form>

<?php
echo "<br>";

if (strlen($first_name) != 0) { echo "first name: " . $first_name . "<br>"; }
if (strlen($last_name) != 0) { echo "last name: " . $last_name . "<br>"; }
if (strlen($sex) != 0) { echo "sex: " . $sex . "<br>"; }
echo "dob: " . $dob . "<br>";
echo "dod: " . $dod . "<br>";

//get new id from max value in MaxPersonID + 1
$getNewId = "SELECT MAX(id) AS maxId FROM MaxPersonID limit 1;";
$result = $db->query($getNewId);
$value = $result->fetch_assoc();
$id = $value["maxId"] + 1;

if ($id == null) {
    $id = 1;
    echo "no id's found in MaxPersonID table. assigning id value 1<br>";
}
else {
    echo "fetched new id: " . $id . "<br>";
}

//create query to insert new value to actor table
$query = "INSERT INTO Director VALUES (" . $id . "," 
        . $last_name . "," . $first_name . ","
        . $sex . "," . $dob . "," . $dod . ");";

echo "query: " . $query;

if (strlen($first_name != 0) && strlen($last_name) != 0) {
//run the query to add the actor to the actor table
$db->query($query);

//add the new actor's id to the maxpersonid table
$db->query("INSERT INTO MaxPersonID VALUES(" . $id . ");");
}

$result->free();

?>
