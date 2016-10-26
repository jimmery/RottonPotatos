<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Add Actor/Director Info</h1>";
echo "[FIRST NAME] [LAST NAME]";

$first_name = "";
$last_name = "";


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $first_name = $_GET["first"];
    $last_name =  $_GET["last"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="first" value="">
    <input type="text" name="last" value="">
    <input type="submit" name="submit" value="click dis">
</form>

<?php
echo "<br>";

if (strlen($first_name) != 0) {
    echo "first name: " . $first_name . "<br>";
}

if (strlen($last_name) != 0) {
    echo "last name: " . $last_name . "<br>";
}
?>