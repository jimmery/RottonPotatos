<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'TEST');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Welcome to Rotten Potatos </h1>";
echo "Search: <br>";

$search = "";
$query = "";
//$words = [];
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $search = $_GET["search"];
    $words = explode(" ", $search);
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="text" name="search"  value="">
    <input type="submit" name="submit" value="Click Me!">
</form>
                              
<?php
echo "<br>";

$add_person = "add_person_info.php";
$add_movie = "add_movie_info.php";
echo "<a href=$add_person>Add Actor or Director</a><br>";
echo "<a href=$add_movie>Add Movie</a><br>";

$size = count($words);
echo "<br>";

if ($size > 0 && strlen($words[0]) > 0)
{
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
    //var_dump($rs);
    $attributes_defined = FALSE;
    while ($row = $rs->fetch_assoc())
    {
        if (!$attributes_defined)
        {
            echo "<h2> Actors with this Name </h2>";
            //echo $query . "<br>";
            echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Date of Birth</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $name = $row["first"] . " " . $row["last"];
        $dob = $row["dob"];
        $actorURL = "Show_A.php?identifier=" . $id;
        echo "<td><a href=$actorURL>$name</td>";;
        echo "<td>$dob</td>";

        echo "</tr>";
    }
    echo "</table>";
    
    //print 'Total results: ' . $rs->num_rows. '<br>';
    $rs->free();

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
    //var_dump($rs);
    $attributes_defined = FALSE;
    while ($row = $rs->fetch_assoc())
    {
        if (!$attributes_defined)
        {
            echo "<h2> Directors with this Name </h2>";
            //echo $query . "<br>";
            echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Date of Birth</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $name = $row["first"] . " " . $row["last"];
        $dob = $row["dob"];
        $directorURL = "Show_D.php?identifier=" . $id;
        echo "<td><a href=$directorURL>$name</td>";;
        echo "<td>$dob</td>";

        echo "</tr>";
    }
    echo "</table>";
    
    //print 'Total results: ' . $rs->num_rows. '<br>';
    $rs->free();
    
    $query = "SELECT * FROM Movie WHERE title LIKE '%" . $search . "%';";

    $query = "SELECT * FROM Movie WHERE ";
    for($i=0; $i < $size; $i=$i+1)
    {
        $query = $query . "title LIKE '%$words[$i]%'";
        if($i < $size-1)
            $query = $query . " AND ";
        else
            $query = $query . " ORDER BY title;";
    }

    $rs = $db->query($query);

    $attributes_defined = FALSE;
    while ($row = $rs->fetch_assoc())
    {
        if (!$attributes_defined)
        {
            echo "<h2> Movies who fit this search </h2>";
            //echo $query . "<br>";
            echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
            echo "<tr>";
            echo "<th>Title</th>";
            echo "<th>Release Year</th>";
            echo "</tr>";
            $attributes_defined = TRUE;
        }
        echo "<tr>";
        $movieURL = "Show_M.php?identifier=" . $id;
        $title = $row["title"];
        echo "<td><a href=$movieURL>$title</td>";
        $year = $row["year"];
        echo "<td>$year</td>";
        
        echo "</tr>";
    }
    echo "</table>";
    
    //print 'Total results: ' . $rs->num_rows;
    $rs->free();
}
// $go_home_url = "index.php";
// echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>
