<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Actor Information Page: </h1>";
echo "Search for an Actor:";

$actor = "";
$query = "";
$identifier = -1;
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $actor = $_GET["actor"];
    $identifier = $_GET["identifier"];
}
?>

    
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <TEXTAREA NAME="actor" ROWS=1 COLS=30></TEXTAREA> <br>
    <input type="submit" name="submit" value="Click Me!">
</form>
                               
<?php
if ($identifier == null || $identifier < 0) {

    echo "<br>";
    echo "<h2> Actors with this Name </h2>";
    /*$query = "SELECT * FROM Actor WHERE first = \"" . $actor . "\"" .
      " OR last = \"" . $actor . "\";";*/
    $query = "SELECT * FROM Actor WHERE first LIKE '%" . $actor . "%'" .
           " OR last LIKE '%" . $actor . "%';";
    echo $query . "<br>";
    $rs = $db->query($query);

    $attributes_defined = FALSE;
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
    while ($row = $rs->fetch_assoc())
    {
        if (!$attributes_defined)
        {
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Date of Birth</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $name = $row["first"] . " " . $row["last"];
        $dob = $row["dob"];
        $id = $row["id"];
        $actorURL = "http://localhost:1438/~cs143/RottonPotatos/Project1C/Show_A.php?identifier=" . $id;
        echo "<td>$id</td>";
        echo "<td><a href=$actorURL>$name</td>";
        echo "<td><a href=$actorURL>$dob</td>";
        echo "</tr>";
    }
    echo "</table>";

    print 'Total results: ' . $rs->num_rows;
    $rs->free();
} 
else if ($identifier > 0) {
    $query = "SELECT * FROM Actor WHERE id=" . $identifier . ";";
    $rs = $db->query($query);
    $row = $rs->fetch_assoc();
    $first = $row["first"];
    $last = $row["last"];
    $fullName = $first . " " . $last;
    $sex = $row["sex"];
    $dob = $row["dob"];
    $dod = $row["dod"];
    $rs->free();
    
    echo "<h2>$fullName</h2>";
    //ACTOR INFO TABLE SETUP
    $attributes_defined = false;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
    if (!$attributes_defined) {
        echo "<tr>";
        echo "<th>Sex</th>";
        echo "<th>Date of Birth</th>";
        echo "<th>Date of Death</th>";
        echo "</tr>";
        $attributes_defined = true;
    }


    // ACTOR INFO DATA
    echo "<tr>";
    echo "<td>$sex</td>";
    echo "<td>$dob</td>";
    if ($dod != null) {
        echo "<td></td>";
    } else {
        echo "<td>Still alive or date of death not known</td>";
    }
    echo "</tr>";
        
    echo "<h2>$fullName's Movies and Roles</h2>";
        
    $getInfoQuery = "SELECT mid, role FROM MovieActor WHERE aid=" . $identifier . ";";
    $rs = $db->query($getInfoQuery);
    
    $attributes_defined = FALSE;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
    while ($row = $rs->fetch_assoc()) {
        if (!$attributes_defined)
        {
            echo "<tr>";
            echo "<th>Role</th>";
            echo "<th>Movie Title</th>";
            echo "<th>Year</th>";
            echo "<th>Rating</th>";
            echo "<th>Company</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $role = $row["role"];
        
        //get movie info from mid
        $movieId = $row["mid"];
        $movieInfoQuery = "SELECT * FROM Movie WHERE id=" . $movieId . ";";
        $movieResult = $db->query($movieInfoQuery);
        $movieRow = $movieResult->fetch_assoc();
        $movieTitle = $movieRow["title"];
        $movieYear = $movieRow["year"];
        $movieRating = $movieRow["rating"];
        $movieCompany = $movieRow["company"];
        
        $movieURL = "http://localhost:1438/~cs143/RottonPotatos/Project1C/Show_M.php?identifier=" . $movieId;
        
        //print to table
        echo "<td>$role</td>";
        echo "<td><a href=$movieURL>$movieTitle</td>";
        echo "<td>$movieYear</td>";
        echo "<td>$movieRating</td>";
        echo "<td>$movieCompany</td>";
        echo "</tr>";          
    }
}
?>
