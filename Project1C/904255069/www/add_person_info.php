<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'CS143');

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
$dod = null;
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["type"]))
        $type = $_GET["type"];
    else
        $err_msg = $err_msg . "Select Actor or Director! <br>";

    $first_name = $_GET["first"];
    if (strlen($first_name) <= 0)
        $err_msg = $err_msg . "No First Name given. <br>";

    $last_name =  $_GET["last"];
    if (strlen($last_name) <= 0)
        $err_msg = $err_msg . "No Last Name given. <br>";
  
    $sex = $_GET["sex"];
    $dob = $_GET["dob"];
    $dod = $_GET["dod"];
}
?>

<p><span class="error">* required field.</span></p>
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="radio" name="type" value="actor"> Actor
    <input type="radio" name="type" value="director"> Director 
        <span class="error">* </span><br> <br>
    <b>First Name:</b> <input type="text" name="first" maxlength="20" value=""> 
        <span class="error">* </span><br>
    <b>Last Name:</b> <input type="text" name="last" maxlength="20" value=""> 
        <span class="error">* </span><br>
    <b>Sex:</b> <input type="radio" name="sex" maxlength="6" value="male"> Male 
    <input type="radio" name="sex" value="female"> Female <br>
    <b>Date of Birth:</b> <input type="date" name="dob" value=""> <br>
    <b>Date of Death:</b> <input type="date" name="dod" value=""> (leave empty, if not applicable)<br>
    <input type="submit" name="submit" value="Add Person!"> <br>
</form>

<?php
echo "<br>";

if (empty($_GET["submit"]))
{
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return;
}
if (strlen($err_msg) > 0)
{
    echo $err_msg;
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return;
}

//if (strlen($type) != 0) { echo "type: " . $type . "<br>"; }
//if (strlen($first_name) != 0) { echo "first name: " . $first_name . "<br>"; }
//if (strlen($last_name) != 0) { echo "last name: " . $last_name . "<br>"; }
//if (strlen($sex) != 0) { echo "sex: " . $sex . "<br>"; }
//echo "dob: " . $dob . "<br>";
//echo "dod: " . $dod . "<br>";

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
//    echo "no id's found in MaxPersonID table. assigning id value 1<br>";
}
else {
//    echo "fetched new id: " . $id . "<br>";
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

//echo "query: " . $query;

$db->query($query);
$db->query("INSERT INTO MaxPersonID VALUES(" . $id . ");");
echo "New person added successfully! <br>";

$result->free();
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>

