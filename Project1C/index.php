<!DOCTYPE HTML>
<html>
<body>

<h1>Welcome to Rotten Potatoes!</h1>

<?php
$movie_actor_url = "add_movie_actor_relation.php";
$movie_director_url = "add_movie_director_relation.php";
$movie_info_url = "add_movie_info.php";
$person_info_url = "add_person_info.php";
$search_url = "search.php";
$show_actor_url = "Show_A.php";
$show_movie_url = "show_M.php";
$show_director_url = "Show_D.php";

echo "<a href=$movie_actor_url>Add a Movie-Actor Relationship</a><br>";
echo "<a href=$movie_director_url>Add a Movie-Director Relationship</a><br>";
echo "<a href=$movie_info_url>Add a new Movie</a><br>";
echo "<a href=$person_info_url>Add a new Actor or Director</a><br>";
echo "<a href=$search_url>Search the Databases</a><br>";
echo "<a href=$show_actor_url>Show Actor Information</a><br>";
echo "<a href=$show_movie_url>Show Movie Information</a><br>";
echo "<a href=$show_director_url>Show Director Information</a><br>";
?>

</body>
</html>
