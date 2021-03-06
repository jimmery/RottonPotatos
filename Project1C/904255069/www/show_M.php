<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'CS143');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Movie Information Page: </h1>";
echo "Search for a Movie:";

$movie = "";
$query = "";
$identifier = -1;
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    if (isset($_GET["movie"]))
        $movie = $_GET["movie"];
    if (isset($_GET["identifier"]))
        $identifier = $_GET["identifier"];
}
?>

    
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="movie" value=""> <br>
    <input type="submit" name="submit" value="Click Me!">
</form>
                               
<?php
if ($identifier < 0 && strlen($movie) > 0) {

    echo "<br>";
    echo "<h2> Matching Movies Found</h2>";
    /*$query = "SELECT * FROM Actor WHERE first = \"" . $actor . "\"" .
      " OR last = \"" . $actor . "\";";*/
    //$query = "SELECT * FROM Movie WHERE title LIKE '%" . $search . "%';";

    $words = explode(" ", $movie);
    $size = count($words);

    $query = "SELECT * FROM Movie WHERE ";
    for($i=0; $i < $size; $i=$i+1)
    {
        $query = $query . "title LIKE '%$words[$i]%'";
        if($i < $size-1)
            $query = $query . " AND ";
        else
            $query = $query . " ORDER BY title;";
    }
    echo "<br>";
    $rs = $db->query($query);

    $attributes_defined = FALSE;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"6\">";
    while ($row = $rs->fetch_assoc())
    {
        if (!$attributes_defined)
        {
            echo "<tr>";
            echo "<th>Title</th>";
            echo "<th>Genre</th>";
            echo "<th>Rating</th>";
            echo "<th>Year</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $title = $row["title"];
        $year = $row["year"];
        $rating = $row["rating"];
        $id = $row["id"];
        
        //get genre info if it exists
        $query = "SELECT genre FROM MovieGenre WHERE mid=" . $id . ";";
        $genre_rs = $db->query($query);
        $genre_row = $genre_rs->fetch_assoc();
        $genre = $genre_row["genre"];
        $genre_rs->free();
        
        $movieURL = "Show_M.php?identifier=" . $id;
        echo "<td><a href=$movieURL>$title</td>";
        echo "<td>$genre</td>";
        echo "<td>$rating</td>";
        echo "<td>$year</td>";
        echo "</tr>";
    }
    echo "</table><br><br>";

    //print 'Total results: ' . $rs->num_rows;
    $rs->free();
}

else if ($identifier > 0) {
    $query = "SELECT * FROM Movie WHERE id=" . $identifier . ";";
    $rs = $db->query($query);
    $row = $rs->fetch_assoc();
    $title = $row["title"];
    $year = $row["year"];
    $rating = $row["rating"];
    $company = $row["company"];
    $rs->free();
    
    //get movie genre if it exists
    $query = "SELECT genre FROM MovieGenre WHERE mid=" . $identifier . ";";
    $genre_rs = $db->query($query);
    $genre_row = $genre_rs->fetch_assoc();
    $genre = $genre_row["genre"];
    $genre_rs->free();
    
    
    echo "<h1>$title</h1>";
    //Movie INFO TABLE SETUP
    $attributes_defined = false;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
    if (!$attributes_defined) {
        echo "<tr>";
        echo "<th>Year</th>";
        echo "<th>Genre</th>";
        echo "<th>Rating</th>";
        echo "<th>Company</th>";
        echo "</tr>";
        $attributes_defined = true;
    }
       
    // Movie INFO DATA
    echo "<tr>";
    echo "<td>$year</td>";
    echo "<td>$genre</td>";
    echo "<td>$rating</td>";
    echo "<td>$company</td>";
    echo "</tr>";

    echo "</table>";
    //echo "<br><br>";
    
    echo "<h2>Actors in \"$title\"</h2>";
        
    $getInfoQuery = "SELECT aid, role FROM MovieActor WHERE mid=" . $identifier . ";";
    $rs = $db->query($getInfoQuery);
    
    $attributes_defined = FALSE;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
    while ($row = $rs->fetch_assoc()) {
        if (!$attributes_defined)
        {
            echo "<tr>";
            echo "<th>Actor</th>";
            echo "<th>Role</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $role = $row["role"];
        //get actor info from aid
        $actorId = $row["aid"];
        $actorInfoQuery = "SELECT first, last FROM Actor WHERE id=" . $actorId . ";";
        $actorResult = $db->query($actorInfoQuery);
        $actorRow = $actorResult->fetch_assoc();
        $first = $actorRow["first"];
        $last = $actorRow["last"];
        $actorName = $first . " " . $last;$actorId;
        $actorURL = "Show_A.php?identifier=" . $actorId;
        //print to table
        echo "<td><a href=$actorURL>$actorName</td>";
        echo "<td>$role</td>";
        echo "</tr>";          
    }
    $rs->free();
    echo "</table><br>";
    
    $url_title = str_replace (" ", "+", $title);
    $add_actor_url = "add_movie_actor_relation.php?title=$url_title";
    
    echo "Are we missing an actor? <a href=$add_actor_url> Add actor here! </a><br><br>";


    /* DIRECTOR TABLE HERE. */
    echo "<h2>Directors of \"$title\"</h2>";
    $getDirectorsQuery = "SELECT * FROM MovieDirector WHERE mid=" . $identifier . ";";
    $rs = $db->query($getDirectorsQuery);
    $attributes_defined = FALSE;
    echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
    while ($row = $rs->fetch_assoc()) {
        echo "<tr>";
        $did = $row["did"];
        $directorNameQuery = "SELECT * FROM Director WHERE id=" . $did . ";";
        $director_rs = $db->query($directorNameQuery);
        $d_row = $director_rs->fetch_assoc();
        $director_rs->free();
        
        $director_first_name = $d_row["first"];
        $director_last_name = $d_row["last"];
        $director_full_name = $director_first_name . " " . $director_last_name;
        
       
        $directorURL = "Show_D.php?identifier=" . $actorId;
        //print to table
//        echo "<td><a href=$actorURL>$actorName</td>";
        echo "<td><a href=$directorURL>$director_full_name</td>";
        echo "</tr>";          
    }
    $rs->free();
    echo "</table><br>";

    $add_director_url = "add_movie_director_relation.php?title=$url_title";
    
    echo "Are we missing a director? <a href=$add_director_url> Add director here! </a><br><br>";

    /* REVIEW TABLE HERE. */
    $review_query = "SELECT AVG(rating) AS avg_rating FROM Review WHERE mid=$identifier;";
    $rs = $db->query($review_query);
    $row = $rs->fetch_assoc();
    $average_rating = $row["avg_rating"];
    if ( $average_rating != NULL ) {
        echo "<h2>User Reviews of '$title'</h2>";
        echo "<b>Average User Rating: </b>$average_rating out of 5<br><br>";
        $rs->free();

        $review_query = "SELECT * FROM Review WHERE mid=$identifier";
        $rs = $db->query($review_query);
        $attributes_defined = FALSE;
        echo "<table border=\"1\" cellspacing=\"2\" cellpadding=\"8\">";
        while ($row = $rs->fetch_assoc()) {
            if (!$attributes_defined)
            {
                echo "<tr>";
                echo "<th>User</th>";
                echo "<th>Time</th>";
                echo "<th>Rating</th>";
                echo "<th>Comment</th>";
                echo "</tr>";
                $attributes_defined = TRUE;
            }
            echo "<tr>";
            $role = $row["role"];
            //get actor info from aid
            $review_name = $row["name"];
            $review_time = $row["time"];
            $review_cmnt = $row["comment"];
            $review_rating = $row["rating"];
            
            echo "<td>$review_name</td>";
            echo "<td>$review_time</td>";
            echo "<td>$review_rating</td>";
            echo "<td>$review_cmnt</td>";
        }
        $rs->free();
        echo "</table><br>";
    }
    else {
        echo "There are no reviews yet. <br>";
    }
    $review_url = "add_comment.php?movieID=$identifier";
    echo "<a href=$review_url>Add a Review? </a><br>";
}

$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>