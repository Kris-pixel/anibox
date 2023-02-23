<?php
require_once('../../connect/db.php');
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

if(!empty($_POST['text']))
{
    $text = $_POST['text'];
    $parentId = $_POST['parentId'];
    $userId = $_SESSION['id'];
    $animeId= $_SESSION['animeId'];
    var_dump($text);
    var_dump($parentId);
    var_dump($userId);
    var_dump($animeId);
    $query = "INSERT INTO `comments` (`text`, `id_user`, `id_parent_comm`, `id_anime`) VALUES ('$text','$userId','$parentId','$animeId'); ";
    $result=mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
}
?>