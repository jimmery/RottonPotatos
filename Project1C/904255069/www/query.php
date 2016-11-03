<!DOCTYPE HTML>
<html>
<body>
<?php
$db = new mysqli('localhost', 'cs143', '', 'CS143');

if ($db->connect_errno > 0)
{
    die('Unable to connect to database [' . $db->connect_error . ']');
}

echo "<h1>Input Box</h1>";
echo "Magical box of happiness:";

$cmd = "";
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $query = $_GET["query"];
}
?>

<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <TEXTAREA NAME="query" ROWS=8 COLS=30></TEXTAREA> <br>
    <input type="submit" name="submit" value="Submit">
</form>  

                               
<?php
echo "<br>" . $query . "<br>";
$rs = $db->query($query);

$attributes_defined = FALSE;

echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"2\">";
while ($row = $rs->fetch_assoc())
{
    if (!$attributes_defined)
    {
        echo "<tr>";
        foreach ($row as $attr => $val)
        {
            echo "<th>$attr</th>";
        }
        echo "</tr>";
        $attributes_defined = TRUE;
    }
    echo "<tr>";
    foreach ($row as $val)
    {
        echo "<td>$val</td>";
    }
    //echo "<br>";
}
echo "</table>";

print 'Total results: ' . $rs->num_rows;
$rs->free();
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>
