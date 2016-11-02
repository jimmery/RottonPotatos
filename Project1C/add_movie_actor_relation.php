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

echo "<h1>Add Actor - Movie Relation</h1>";
$title = "";
$first_name = "";
$last_name = "";
$role = "";
$submit = "";
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (empty($_GET["title"]) && strlen($submit) > 0)
        $err_msg = $err_msg . "Title is missing. <br>";
    else {
        $title = $_GET["title"];    
    }
        
    if (empty($_GET["first_name"]) && strlen($submit) > 0)
        $err_msg = $err_msg . "First name is missing. <br>";
    else
        $first_name = $_GET["first_name"];

    if (empty($_GET["last_name"]) && strlen($submit) > 0)
        $err_msg = $err_msg . "Last name is missing. <br>";
    else
        $last_name = $_GET["last_name"];
    
    if (empty($_GET["role"]) && strlen($submit) > 0)
        $err_msg = $err_msg . "Role is missing. <br>";
    else
        $role = $_GET["role"];

    $submit = $_GET["submit"];
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
    <b>Role: </b><input type="text" name="role" value=""> 
        <span class="error">* </span><br>
    <input type="submit" name="submit" value="Submit"> <br>
</form>
<br>
<!--We can change the name search to be similar to what we have in searches.-->
<br>
<?php
echo "<br>";

if (empty($_GET["submit"])) {
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return;
}
if (strlen($err_msg) > 0) {
    echo $err_msg;
    return; 
}

//TODO: SEPARATE INPUT BY ' '

$query_A = "SELECT * FROM Actor WHERE first LIKE '%" . $first_name . "%'" . 
        " AND last LIKE '%" . $last_name . "%' limit 1;";
//echo "<br>";
//echo "actor query = " . $query_A . "<br>";

$actor = $db->query($query_A);

$get_aid = "SELECT id AS aid FROM Actor WHERE last = '" 
            . $last_name
            . "' AND first = '" 
            . $first_name 
            . "' limit 1;";
$result = $db->query($get_aid);
$value = $result->fetch_assoc();
$aid = $value["aid"];
$result->free();
//echo "get actor id query = " . $get_aid . "<br>";
$get_mid = "SELECT id AS mid FROM Movie WHERE title='" 
            . $title
            . "' limit 1;";
//echo "get movie id query = " . $get_mid . "<br>";
$result = $db->query($get_mid);
$value = $result->fetch_assoc();
$mid = $value["mid"];
$result->free();
$insert_AM = "INSERT INTO MovieActor VALUES(" 
        . $mid 
        . "," . $aid 
        . ",'" . $role . "');";
//echo "insert into actor-movie relation query: " . $insert_AM . "<br>";

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
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>
