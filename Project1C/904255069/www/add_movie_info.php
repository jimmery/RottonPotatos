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

echo "<h1>Add Movie Info</h1>";
$title = "";
$rating = "";
$company = "";
$genre = "";
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = $_GET["title"];
    if (strlen($title) <= 0)
        $err_msg = $err_msg . "Movie Title is Missing. <br>";
    
    $year = $_GET["year"];
    if (strlen($year) <= 0)
        $err_msg = $err_msg . "Year is Missing. <br>";

    $rating =  $_GET["rating"];
    if (strlen($rating) <= 0)
        $err_msg = $err_msg . "Rating is Missing. <br>";

    $company = $_GET["company"];
    if (strlen($company) <= 0)
        $err_msg = $err_msg . "Company is Missing. <br>";
    
    $genre = $_GET["genre"];
    if (strlen($genre) <= 0)
        $err_msg = $err_msg . "Genre is Missing. <br>";
}
?>

<p><span class="error">* required field.</span></p>
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <b>Movie Title: </b><input type="text" name="title" maxlength="100" value=""> 
        <span class="error">* </span><br>
    <b>Year Released: </b><input type="number" name="year" value=""> 
        <span class="error">* </span><br>
    <b>Rating: </b><input type="text" name="rating" maxlength="10" value=""> 
        <span class="error">* </span><br>
    <b>Company: </b><input type="text" name="company" maxlength="50" value=""> 
        <span class="error">* </span><br>
    <b>Genre: </b><input type="text" name="genre" maxlength="20" value=""> 
        <span class="error">* </span><br>
    <input type="submit" name="submit" value="submit"> <br>
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
    echo $err_msg . "<br>";
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return; 
}

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

//create query to insert new value to movie table
$query = "INSERT INTO Movie VALUES (" . $id . ",'" 
        . $title . "'," . $year . ",'"
        . $rating . "','" . $company . "');";

//echo "query: " . $query . "<br>";

//echo "title: " . $title . "<br>";
//run the query to add the movie to the movie table
$db->query($query);

//add the new actor's id to the maxpersonid table
$db->query("INSERT INTO MaxMovieID VALUES(" . $id . ");");


//add the movie and its genre to the MovieGenre table
$query = "INSERT INTO MovieGenre VALUES (" . $id . ",'" . $genre . "');";
$db->query($query);
//echo "add to MovieGenre query: " . $query . "<br>";

//echo "Movie Added Successfully with ID = $id <br>";

$result->free();
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>