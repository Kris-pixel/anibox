<?php
require_once "../connect/db.php";
require_once "func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$do = $_GET['do'];


if($do == 'dlt'){
    $loginId = $_SESSION['id'];
    $anime = $_SESSION['animeId'];
    $query = "DELETE FROM lists WHERE id_anime='$anime' AND id_user='$loginId'";
    $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
        if($rez){
            echo "<script>window.location.href='../title_info.php?title=".$_GET['title']."';</script>"; 
        } 
}else{
    ChangeList($do, $link);
}

function ChangeList($do,$link)
{
    $loginId = $_SESSION['id'];
    $anime = $_SESSION['animeId'];

    $row = getInfoDB(
        "SELECT list_type_key FROM lists WHERE id_anime='$anime' AND id_user='$loginId'",
        $link);
    if($row){
        $query = "UPDATE lists SET list_type_key = '$do' WHERE id_anime='$anime' AND id_user='$loginId' ";
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
        if($rez){
            echo "<script>window.location.href='../title_info.php?title=".$_GET['title']."';</script>"; 
        } 
    }else{
        $query = "INSERT INTO lists (id_anime, id_user, list_type_key) VALUES ('$anime', '$loginId', '$do'); ";
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
        if($rez){
            echo "<script>window.location.href='../title_info.php?title=".$_GET['title']."';</script>"; 
        } 
    }
}
?>