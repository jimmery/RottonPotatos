<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Add Movie - Director Relation</h1>";

$title = "";
$first_name = "";
$last_name = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = $_GET["title"];
    $first_name = $_GET["first_name"];
    $last_name = $_GET["last_name"];    
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Movie Title: <input type="text" name="title" value=""> <br>
    First Name: <input type="text" name="first_name" value=""> <br>
    Last Name: <input type="text" name="last_name" value=""> <br>
    <input type="submit" name="submit" value="submit"> <br>
</form>
Similarly, we can do the search on name.
<br>
<?php
echo "<br>";

if (strlen($title) != 0) { echo "title: " . $title . "<br>"; }
if (strlen($first_name) != 0) { echo "first name: " . $first_name . "<br>"; }
if (strlen($last_name) != 0) { echo "last name: " .  $last_name . "<br>"; }

//TODO: SEPARATE INPUT BY ' '

$query_A = "SELECT * FROM Director WHERE first LIKE '%" . $first_name . "%'" . 
        " AND last LIKE '%" . $last_name . "%' limit 1;";
echo "<br>";
echo "actor query = " . $query_A . "<br>";

$actor = $db->query($query_A);

$query_M = "SELECT * "
        . "FROM Movie WHERE title LIKE '%" 
        . $title 
        . "%' limit 1;";

echo "movie query = " . $query_M . "<br>";

$get_did = "SELECT id AS did FROM Director WHERE last = '" 
            . $last_name
            . "' AND first = '" 
            . $first_name 
            . "' limit 1;";
$result = $db->query($get_did);
$value = $result->fetch_assoc();
$did = $value["did"];
$result->free();
echo "get actor id query = " . $get_mid . "<br>";
$get_mid = "SELECT id AS mid FROM Movie WHERE title='" 
            . $title
            . "' limit 1;";
echo "get movie id query = " . $get_mid . "<br>";
$result = $db->query($get_mid);
$value = $result->fetch_assoc();
$mid = $value["mid"];
$result->free();
$insert_DM = "INSERT INTO MovieDirector VALUES(" 
        . $mid 
        . "," . $did 
        . ");";
echo "insert into director-movie relation query: " . $insert_DM . "<br>";

$db->query($insert_DM);


// FOR REFERENCE:
//====================================================================
//CREATE TABLE MovieDirector(
//    mid int,
//    did int,
//	FOREIGN KEY mid REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE,
//	FOREIGN KEY did REFERENCES Director(id) ON UPDATE CASCADE ON DELETE CASCADE
// );