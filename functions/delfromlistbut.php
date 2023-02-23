<?php
require_once "../connect/db.php";
require_once "func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$delid=$_GET['id'];

$query = "DELETE FROM lists WHERE id='$delid'";
$rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link));
    if($rez){
        echo "<script>window.location.href='../user_room.php?title=".$_GET['title']."';</script>"; 
    } 
?>