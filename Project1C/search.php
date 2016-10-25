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

$search = "";
$query = "";
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $search = $_GET["search"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <TEXTAREA NAME="search" ROWS=1 COLS=30></TEXTAREA> <br>
    <input type="submit" name="submit" value="Click Me!">
</form>
                               
<?php

echo "<br>";
echo "<h2> Actors with this Name </h2>";
/*$query = "SELECT * FROM Actor WHERE first = \"" . $search . "\"" .
  " OR last = \"" . $search . "\";";*/
$query = "SELECT * FROM Actor WHERE first LIKE '%" . $search . "%'" .
       " OR last LIKE '%" . $search . "%';";
echo $query . "<br>";
$rs = $db->query($query);

$attributes_defined = FALSE;
echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
while ($row = $rs->fetch_assoc())
{
    if (!$attributes_defined)
    {
        echo "<tr>";
        echo "<th>Name</th>";
        echo "<th>Date of Birth</th>";
        echo "</tr>";
        $attributes_defined = TRUE;
    }
    echo "<tr>";
    $name = $row["first"] . " " . $row["last"];
    $dob = $row["dob"];
    echo "<td>$name</td>";
    echo "<td>$dob</td>";
    
    echo "</tr>";
}
echo "</table>";

print 'Total results: ' . $rs->num_rows. '<br>';
$rs->free();

echo "<h2> Movies who fit this search </h2>";
$query = "SELECT * FROM Movie WHERE title LIKE '%" . $search . "%';";
echo $query . "<br>";
$rs = $db->query($query);

$attributes_defined = FALSE;
echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
while ($row = $rs->fetch_assoc())
{
    if (!$attributes_defined)
    {
        echo "<tr>";
        echo "<th>Title</th>";
        echo "<th>Release Year</th>";
        echo "</tr>";
        $attributes_defined = TRUE;
    }
    echo "<tr>";
    $title = $row["title"];
    $year = $row["year"];
    echo "<td>$title</td>";
    echo "<td>$year</td>";
    
    echo "</tr>";
}
echo "</table>";

print 'Total results: ' . $rs->num_rows;
$rs->free();

?>
