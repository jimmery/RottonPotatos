<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
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
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = $_GET["title"];
    if (strlen($title) <= 0)
        $err_msg = $err_msg . "No Title Provided. <br>";
    $first_name = $_GET["first_name"];
    if (strlen($first_name) <= 0)
        $err_msg = $err_msg . "No First Name Provided. <br>";
    $last_name = $_GET["last_name"];    
    if (strlen($last_name) <= 0)
        $err_msg = $err_msg . "No Last Name Provided. <br>";
}
?>

<p><span class="error">* required field.</span></p>
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <b>Movie Title: </b><input type="text" name="title" value="">
        <span class="error">* </span> <br>
    <b>First Name: </b><input type="text" name="first_name" value=""> 
        <span class="error">* </span><br>
    <b>Last Name: </b><input type="text" name="last_name" value="">
        <span class="error">* </span> <br>
    <input type="submit" name="submit" value="Submit"> <br>
</form>
<!--Similarly, we can do the search on name.-->
<br>
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
    echo $err_msg . "<br>";
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return;
}

//if (strlen($title) != 0) { echo "title: " . $title . "<br>"; }
//if (strlen($first_name) != 0) { echo "first name: " . $first_name . "<br>"; }
//if (strlen($last_name) != 0) { echo "last name: " .  $last_name . "<br>"; }

//TODO: SEPARATE INPUT BY ' '

$query_A = "SELECT * FROM Director WHERE first LIKE '%" . $first_name . "%'" . 
        " AND last LIKE '%" . $last_name . "%' limit 1;";
echo "<br>";
//echo "actor query = " . $query_A . "<br>";

$actor = $db->query($query_A);

$query_M = "SELECT * "
        . "FROM Movie WHERE title LIKE '%" 
        . $title 
        . "%' limit 1;";

//echo "movie query = " . $query_M . "<br>";

$get_did = "SELECT id AS did FROM Director WHERE last = '" 
            . $last_name
            . "' AND first = '" 
            . $first_name 
            . "' limit 1;";
$result = $db->query($get_did);
$value = $result->fetch_assoc();
$did = $value["did"];
$result->free();
//echo "get actor id query = " . $get_mid . "<br>";
$get_mid = "SELECT id AS mid FROM Movie WHERE title='" 
            . $title
            . "' limit 1;";
//echo "get movie id query = " . $get_mid . "<br>";
$result = $db->query($get_mid);
$value = $result->fetch_assoc();
$mid = $value["mid"];
$result->free();
$insert_DM = "INSERT INTO MovieDirector VALUES(" 
        . $mid 
        . "," . $did 
        . ");";
//echo "insert into director-movie relation query: " . $insert_DM . "<br>";

$db->query($insert_DM);


// FOR REFERENCE:
//====================================================================
//CREATE TABLE MovieDirector(
//    mid int,
//    did int,
//	FOREIGN KEY mid REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE,
//	FOREIGN KEY did REFERENCES Director(id) ON UPDATE CASCADE ON DELETE CASCADE
// );

$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>