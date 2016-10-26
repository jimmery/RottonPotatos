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

$first_name = "";
$last_name = "";
$sex = "";
$query = "";
$dob = '2001-09-11';
$dod = '2016-04-20';

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

//TODO: get new id from max value in MaxPersonID + 1
$id = 2;

$query = "INSERT INTO Actor VALUES (" . $id . "," 
        . $last_name . "," . $first_name . ","
        . $sex . "," . $dob . "," . $dod . ")";

echo "query: " . $query;

?>

