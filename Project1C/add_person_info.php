<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Add Person Info</h1>";
$id = -1;
$type = "";
$first_name = "";
$last_name = "";
$sex = "";
$query = "";
$dob = null;
$dod = null;;

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $type = $_GET["type"];
    $first_name = $_GET["first"];
    $last_name =  $_GET["last"];
    $sex = $_GET["sex"];
    $dob = $_GET["dob"];
    $dod = $_GET["dod"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Type: <input type="radio" name="type" checked="checked" value="actor"> Actor
    <input type="radio" name="type" value="director"> Director <br> <br>
    First Name: <input type="text" name="first" value=""> <br>
    Last Name: <input type="text" name="last" value=""> <br>
    Sex: <input type="radio" name="sex" checked="checked" value="male"> Male <t>
    <input type="radio" name="sex" value="female"> Female <br>
    Date of Birth: <input type="date" name="dob" value=""> <br>
    Date of Death: <input type="date" name="dod" value=""> (leave empty, if not applicable)<br>
    <input type="submit" name="submit" value="submit"> <br>
</form>

<?php
echo "<br>";

if (strlen($type) != 0) { echo "type: " . $type . "<br>"; }
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

// TODO: this above code seems incredibly inefficient but i guess it works.
// and the below thingy. 

//get new id from max value in MaxPersonID + 1
$getNewId = "SELECT MAX(id) AS maxId FROM MaxPersonID limit 1;";
$result = $db->query($getNewId);
$value = $result->fetch_assoc();
$id = $value["maxId"] + 1;
$result->free();

if ($id == -1) {
    $id = 1;
    echo "no id's found in MaxPersonID table. assigning id value 1<br>";
}
else {
    echo "fetched new id: " . $id . "<br>";
}

//create query to insert new value to actor table
if ( $type == "actor" )
{
    $query = "INSERT INTO Actor VALUES (" . $id . ",'" 
        . $last_name . "','" . $first_name . "','"
        . $sex . "','" . $dob . "','" . $dod . "');";
}
else if ( $type == "director" )
{
    $query = "INSERT INTO Director VALUES (" . $id . ",'" 
        . $last_name . "','" . $first_name . "','"
        . $dob . "','" . $dod . "');";
}

echo "query: " . $query;

if ((strlen($first_name) != 0) && (strlen($last_name) != 0)) {
    //run the query to add the actor to the actor table
    $db->query($query);  
    //add the new actor's id to the maxpersonid table
    $db->query("INSERT INTO MaxPersonID VALUES(" . $id . ");");
}

$result->free();
?>
