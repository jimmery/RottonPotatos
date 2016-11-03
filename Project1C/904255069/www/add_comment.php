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

echo "<h1>Add Comment to Movie</h1>";
$name = "";
$rating = -1;
$movieID = -1;
$submit = "";
$comment = "";

$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
    if (empty( $_GET["movieID"]))
    {
        echo "No movie selected. <br>";
        echo "Please select a movie to add a comment. ";
        return;
    }
    else
    {
        $movieID = $_GET["movieID"];
        $m_name_q = "SELECT title FROM Movie WHERE id=$movieID";
        $result = $db->query($m_name_q);
        $result_col = $result->fetch_assoc();
        $movie_name = $result_col["title"];
        $result->free();
    }

    if ( empty( $_GET["name"] ) && strlen($submit) > 0)
    {
        $err_msg = $err_msg . "Name is missing. <br>";
    }
    else
    {
        $name = $_GET["name"];
        if ( strlen($name) > 20 ) {
            $err_msg = $err_msg . "Name is too long. <br>";
        }
    }

    if ( empty( $_GET["rating"] ) && strlen($submit) > 0)
    {
        $err_msg = $err_msg . "Rating is missing. <br>";
    }
    else
    {
        $rating = $_GET["rating"];
    }

    if (empty($_GET["comment"]) && strlen($submit) > 0)
    {
        // no error message. 
        // potentially just do nothing. 
    }
    else
    {
        $comment = $_GET["comment"];
        if ( strlen($name) > 500 ) {
            $err_msg = $err_msg . "Comment is too long. <br>";
        }
    }

    $submit = $_GET["submit"]; // not sure if needed. 
}

?>

<p><span class="error">* required field.</span></p>
<form method="get" action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php echo "<b>Movie Title: </b>" . $movie_name . "&nbsp&nbsp&nbsp&nbsp";?>
    <?php echo "<b> Movie ID: </b><input type=\"text\" name=\"movieID\" size=8
                        value=\"$movieID\" readonly> <br><br>";?>

    <b> Your Name: </b><input type="text" name="name" size=50 maxlength="20"
                        placeholder="up to 20 characters" value=""> 
                        <span class="error">* </span><br><br>
    <b> Rating: </b><input type="radio" name="rating" value="1"> 1 &nbsp
    <input type="radio" name="rating" value="2"> 2 &nbsp
    <input type="radio" name="rating" value="3"> 3 &nbsp
    <input type="radio" name="rating" value="4"> 4 &nbsp
    <input type="radio" name="rating" value="5"> 5 &nbsp
    <span class="error">* </span><br><br>
    <b> Comment: </b><br><TEXTAREA name="comment" ROWS=8 COLS=50 maxlength="500"
            placeholder="comment up to 500 characters"></TEXTAREA> <br>
    <input type="submit" name="submit" value="Submit Rating"> <br>
</form>

<?php

echo "<br>";
if ( strlen($submit) == 0 ) {
    $return_url = "show_M.php?identifier=$movieID";
    echo "Changed your mind? <a href=$return_url>Back to movie information</a><br>";
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return;
}
if(strlen($err_msg) > 0) {
    echo $err_msg . "<br><br>";
    $return_url = "show_M.php?identifier=$movieID";
    echo "Changed your mind? <a href=$return_url>Back to movie information</a><br>";
    $go_home_url = "index.php";
    echo "<a href=$go_home_url>Go Home. </a><br>";
    return;
}

$time_q = "SELECT NOW() AS CTime;";
$result = $db->query($time_q);
$value = $result->fetch_assoc();
$time = $value["CTime"];
//echo $time;

$rating_q = "INSERT INTO Review VALUES ( '$name', 
                                         '$time', 
                                         $movieID, 
                                         $rating, 
                                         '$comment' );"; 
//echo $rating_q;
$result->free();

$db->query($rating_q);

echo "Rating Inserted!<br>";
$return_url = "show_M.php?identifier=$movieID";
echo "<a href=$return_url>Go back?</a><br>";
$go_home_url = "index.php";
echo "<a href=$go_home_url>Go Home. </a><br>";
?>

</body>
</html>