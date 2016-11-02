<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Director Information Page: </h1>";
echo "Search For a Director:";

$director = "";
$query = "";
$identifier = -1;
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $director = $_GET["director"];
    if (isset($_GET["identifier"]))
        $identifier = $_GET["identifier"];
}
?>

    
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="director" value=""> <br>
    <input type="submit" name="submit" value="Click Me!">
</form>
                               
<?php
if (empty($_GET["identifier"]) && strlen($director) > 0) {
    echo "<br>";
    
    echo "<h2> Directors with this Name </h2>";
    $words = explode(" ", $director);
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

    $query = "SELECT * FROM Director WHERE ";
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
            echo "<th>Date of Death</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $name = $row["first"] . " " . $row["last"];
        $dob = $row["dob"];
        $dod = $row["dod"];
        $id = $row["id"];
        //echo $name . " " . $id . "<br>";
        $directorURL = "Show_D.php?identifier=" . $id;
        echo "<td>$id</td>";
        echo "<td><a href=$directorURL>$name</td>";
        echo "<td>$dob</td>";
        echo "<td>$dod</td>";
        echo "</tr>";
    }
    echo "</table>";

    print 'Total results: ' . $rs->num_rows . "<br>";
    $rs->free();
} 
else if ($identifier > 0) {
    $query = "SELECT * FROM Director WHERE id=" . $identifier . ";";
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
        
    echo "<h2>Movies $fullName Has Directed</h2>";
    
    $getInfoQuery = "SELECT mid FROM MovieDirector WHERE did=" . $identifier . ";";
    $rs = $db->query($getInfoQuery);
    
    $attributes_defined = FALSE;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
    while ($row = $rs->fetch_assoc()) {
        if (!$attributes_defined)
        {
            echo "<tr>";
            echo "<th>Movie Title</th>";
            echo "<th>Genre</th>";
            echo "<th>Year</th>";
            echo "<th>Rating</th>";
            echo "<th>Company</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        
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
        
        
        $movieURL = "Show_M.php?identifier=" . $movieId;
        
        //print to table
        echo "<td><a href=$movieURL>$movieTitle</td>";
        echo "<td>$movieGenre</td>";
        echo "<td>$movieYear</td>";
        echo "<td>$movieRating</td>";
        echo "<td>$movieCompany</td>";
        echo "</tr>";          
    }
    echo "</table> <br>";

    $add_movie_url = "add_movie_director_relation.php?first_name=$first&last_name=$last";
    echo "Are we missing a movie? <a href=$add_movie_url> Add movie here! </a><br><br>";
}
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>
