<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Add Actor - Movie Relation</h1>";
echo "[movie title]...............[first name]................[last name]..................[role]<br>";
$title = "";
$first_name = "";
$last_name = "";
$role = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = $_GET["title"];
    $first_name = $_GET["first_name"];
    $last_name = $_GET["last_name"];
    $role = $_GET["role"];
    
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="title" value="">
    <input type="text" name="first_name" value="">
    <input type="text" name="last_name" value="">
    <input type="text" name="role" value="">
    <input type="submit" name="submit" value="submit">
</form>

<?php
echo "<br>";

if (strlen($title) != 0) { echo "title: " . $title . "<br>"; }
if (strlen($first_name) != 0) { echo "first name: " . $first_name . "<br>"; }
if (strlen($last_name) != 0) { echo "last name: " .  $last_name . "<br>"; }
if (strlen($role) != 0) { echo "role: " . $role . "<br>"; }

//TODO: SEPARATE INPUT BY ' '

$query_A = "SELECT * FROM Actor WHERE first LIKE '%" . $first_name . "%'" . 
        " AND last LIKE '%" . $last_name . "%' limit 1;";
echo "<br>";
echo "actor query = " . $query_A . "<br>";

$actor = $db->query($query_A);

$query_M = "SELECT * "
        . "FROM Movie WHERE title LIKE '%" 
        . $title 
        . "%' limit 1;";

echo "movie query = " . $query_M . "<br>";

$get_aid = "SELECT id AS aid FROM Actor WHERE last = '" 
            . $last_name
            . "' AND first = '" 
            . $first_name 
            . "' limit 1;";
$result = $db->query($get_aid);
$value = $result->fetch_assoc();
$aid = $value["aid"];
$result->free();
echo "get actor id query = " . $get_aid . "<br>";
$get_mid = "SELECT id AS mid FROM Movie WHERE title='" 
            . $title
            . "' limit 1;";
echo "get movie id query = " . $get_mid . "<br>";
$result = $db->query($get_mid);
$value = $result->fetch_assoc();
$mid = $value["mid"];
$result->free();
$insert_AM = "INSERT INTO MovieActor VALUES(" 
        . $mid 
        . "," . $aid 
        . ",'" . $role . "');";
echo "insert into actor-movie relation query: " . $insert_AM . "<br>";

$db->query($insert_AM);



// FOR REFERENCE:
//====================================================================
// CREATE TABLE MovieActor(
//    mid int,
//    aid int,
//    role varchar(50),
//	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE,
//	FOREIGN KEY (aid) REFERENCES Actor(id) ON UPDATE CASCADE ON DELETE CASCADE
// );