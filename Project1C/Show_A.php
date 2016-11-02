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
    if (isset($_GET["identifier"]))
        $identifier = $_GET["identifier"];
}
?>

    
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="actor" value=""> <br>
    <input type="submit" name="submit" value="Click Me!">
</form>
                               
<?php
if (empty($_GET["identifier"]) && strlen($actor) > 0) {
    echo "<br>";
    
    echo "<h2> Actors with this Name </h2>";
    $words = explode(" ", $actor);
    $size = count($words);
    // $query = "SELECT * FROM Actor WHERE first LIKE '" . $words[0] . "%'";

    // if ($size == 1)
    // {
    //     $query = $query . " OR last LIKE '" . $words[0] . "%'";
    // }
    // $count = 1;
    // while ($count < $size)
    // {
    //     $query = $query . " AND last LIKE '" . $words[$count] . "%'";
    //     $count = $count + 1;
    // }

    // $query = $query . ";";
    // echo $query . "<br>";
    // $rs = $db->query($query);

    $query = "SELECT * FROM Actor WHERE ";
    for($i=0; $i < $size; $i=$i+1)
    {
        $query = $query . "(first LIKE '%$words[$i]%' OR last LIKE '%$words[$i]%')";
        if($i < $size-1)
            $query = $query . " AND ";
        else
            $query = $query . " ORDER BY last;";
    }

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
        $actorURL = "Show_A.php?identifier=" . $id;
        echo "<td>$id</td>";
        echo "<td><a href=$actorURL>$name</td>";
        echo "<td><a href=$actorURL>$dob</td>";
        echo "</tr>";
    }
    echo "</table>";

    print 'Total results: ' . $rs->num_rows . "<br>";
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
    echo "</table>";
        
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
            echo "<th>Genre</th>";
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
        $movieInfoQuery = "SELECT * FROM Movie WHERE id = " . $movieId . ";";
//        echo "movie id: " . $movieId . "<br>";
        $movieResult = $db->query($movieInfoQuery);
        $movieRow = $movieResult->fetch_assoc();
        $movieTitle = $movieRow["title"];
        $movieYear = $movieRow["year"];
        $movieRating = $movieRow["rating"];
        $movieCompany = $movieRow["company"];
        //get genre if it exists in MovieGenre table
        $genreQuery = "SELECT genre FROM MovieGenre WHERE mid = " . $movieId . ";";
        $genreResult = $db->query($genreQuery);
        $genreRow = $genreResult->fetch_assoc();
        $movieGenre = $genreRow["genre"];
//        echo "genre: " . $movieGenre . "<br>";
        
        
        $movieURL = "http://localhost:1438/~cs143/RottonPotatos/Project1C/Show_M.php?identifier=" . $movieId;
        
        //print to table
        echo "<td>$role</td>";
        echo "<td><a href=$movieURL>$movieTitle</td>";
        echo "<td>$movieGenre</td>";
        echo "<td>$movieYear</td>";
        echo "<td>$movieRating</td>";
        echo "<td>$movieCompany</td>";
        echo "</tr>";          
    }
    echo "</table> <br>";
}
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>
