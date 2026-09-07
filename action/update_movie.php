<?php

$movie_id = $_POST["movie_id"];
$title = $_POST["title"];
$release_year = $_POST["release_year"];
$duration_min = $_POST["duration_min"];
$genre_id = $_POST["genre_id"];

include "connect.php";

$sql = "UPDATE `movies` 
SET 
`movie_id`='[value-1]',
`title`='[value-2]',
`release_year`='[value-3]',
`duration_min`='[value-4]',
`genre_id`='[value-5]' WHERE 1 ";

$result = mysqli_query($con, $sql);

if(!$result){
    echo "Error";
}else{
    header("Location: ../manage_menu.php");
    exit;
}