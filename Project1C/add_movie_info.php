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

echo "<h1>Add Movie Info</h1>";
$title = "";
$rating = "";
$company = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = $_GET["title"];
    $year = $_GET["year"];
    $rating =  $_GET["rating"];
    $company = $_GET["company"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <b>Movie Title: </b><input type="text" name="title" value=""> <br>
    <b>Year Released: </b><input type="number" name="year" value=""> <br>
    <b>Rating: </b><input type="text" name="rating" value=""> <br>
    <b>Company: </b><input type="text" name="company" value=""> <br>
    <input type="submit" name="submit" value="submit"> <br>
</form>

<?php
echo "<br>";

if (strlen($title) != 0) { echo "title " . $title . "<br>"; }
if ($year != null) { echo "year: " . $year . "<br>"; }
if (strlen($rating) != 0) { echo "rating: " . $rating . "<br>"; }
if (strlen($company) != 0) { echo "company: " . $company . "<br>"; };

//ensure MaxPersonID table is up to date
$maxMovieId = "SELECT MAX(id) AS maxID_M FROM Movie limit 1;";
$result = $db->query($maxMovieId);
$value = $result->fetch_assoc();
$m_id_m= $value["maxID_M"];
$result->free();

$db->query("INSERT INTO MaxMovieID VALUES(" . $m_id_m . ");");

//get new id from max value in MaxPersonID + 1
$getNewId = "SELECT MAX(id) AS maxId FROM MaxMovieID limit 1;";
$result = $db->query($getNewId);
$value = $result->fetch_assoc();
$id = $value["maxId"] + 1;
$result->free();

if ($id == null) {
    $id = 1;
    echo "no id's found in MaxMovienID table. assigning id value 1<br>";
}
else {
    echo "fetched new id: " . $id . "<br>";
}

//create query to insert new value to actor table
$query = "INSERT INTO Movie VALUES (" . $id . "," 
        . $title . "," . $year . ","
        . $rating . "," . $company . ");";

echo "query: " . $query . "<br>";

//must at least have a title
if (strlen($title) != 0) {
    echo "title: " . $title . "<br>";
    //run the query to add the actor to the actor table
    $db->query($query);

    //add the new actor's id to the maxpersonid table
    $db->query("INSERT INTO MaxMovieID VALUES(" . $id . ");");
}

$result->free();
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>