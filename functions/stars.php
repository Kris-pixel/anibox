<?php
require_once "../connect/db.php";
// require_once "functions/func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$loginId = $_SESSION['id'];
$anime = $_SESSION['animeId'];

$newStars =$_GET['starmark'];
if($newStars){
$query = "UPDATE lists SET mark = '$newStars' WHERE id_anime='$anime' AND id_user='$loginId'";
 $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
echo "<script>window.location.href='../title_info.php?title=".$_GET['title']."';</script>";
}

?>