
<?php

require("..\connect\db.php");

$a = [];
$result = '';
$query = "SELECT title from anime";
$rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
while ($row = mysqli_fetch_assoc($rez)) {
    $a[] = $row['title'];
}

// get the q parameter from URL
$q = $_REQUEST["q"];

$hint = "";

// lookup all hints from array if $q is different from ""
if ($q !== "") {
//   $q = strtolower($q);
  $len=strlen($q);
  foreach($a as $name) {
    // var_dump($name);
    if (stristr($q, substr($name, 0, $len))) {
      if ($hint === "") {
        $hint = "<tr>
        <td>
            <a href='title_info.php?title=$name'>
                <span style='display: block; margin-left: 13px; padding-top: 7px; padding-bottom: 7px;'>$name</span>
            </a>
        </td>
    </tr>";
      } else {
        $hint .= "<tr>
        <td>
            <a href='title_info.php?title=$name'>
                <span style='display: block; margin-left: 13px; padding-top: 7px; padding-bottom: 7px;'>$name</span>
            </a>
        </td>
    </tr>";
      }
    }
  }
}

// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "" : $hint;
?>