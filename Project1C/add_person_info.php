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
    <input type="text" name="first" value="first">
    <input type="text" name="last" value="last">
    <input type="text" name="sex" value="sex">
    <input type="date" name="dob" value="birth date">
    <input type="date" name="dod" value="death date">
    <input type="submit" name="submit" value="click dis">
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
$result->free();

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

//run the query to add the actor to the actor table
$db->query($query);
//add the new actor's id to the maxpersonid table
$db->query("INSERT INTO MaxPersonID VALUES(" . $id . ");")
?>

