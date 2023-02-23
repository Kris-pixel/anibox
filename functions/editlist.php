<?php
require_once "../connect/db.php";
require_once "func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$newlist = $_POST['list'];
$newmark = $_POST['mark'];
$newwatch = $_POST['wctd'];

$anime = $_POST['animeId'];
$loginId = $_SESSION['id'];

var_dump($newlist);
var_dump($newmark);var_dump($newwatch );

if($newlist != NULL){
    $query = "UPDATE lists SET list_type_key = '$newlist' WHERE id_anime='$anime' AND id_user='$loginId' ";
    // var_dump($rez);
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
}

if($newmark != NULL){
    $query = "UPDATE lists SET mark = '$newmark' WHERE id_anime='$anime' AND id_user='$loginId' ";
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
        // var_dump($rez);
}
if($newwatch != NULL){
    $query = "UPDATE lists SET watched_amount = '$newwatch' WHERE id_anime='$anime' AND id_user='$loginId' ";
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
         var_dump($rez);
}

  echo "<script>window.location.href='../user_room.php';</script>"; 
?>