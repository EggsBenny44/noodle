<?php
include("database_include.php");
session_start();
doDB();
$sql = "SELECT * FROM submission WHERE uid = ".$_SESSION['uid']." order by sid";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NOODLE</title>
    <link href="https://fonts.googleapis.com/css?family=Julius+Sans+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Homenaje&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300&display=swap" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v5.11.2/js/all.js" data-auto-add-css="false"></script>
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/svg-with-js.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/myStyle.css?1234">
</head>
<body>
<div class="contents">
    <?php
    include("header.php");

    echo $sql;
    ?>

    <div class="breadcrumbs">
        <a href="mainmenu.php">MENU</a> > SUBMISSION HISTORY
    </div>
    <div class="sub-contents">
        <div style="display:flex; flex-flow:row; justify-content: center; padding-top: 30px;width: 100%;">
    <table width="90%">
        <tr>
            <th>submission date</th>
            <th>test name</th>
            <th>total questions</th>
            <th>right answers</th>
            <th>review</th>
        </tr>
<?php
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>".$row['submitAt']."</td>";
    echo "<td>".$row['tname']."</td>";
    echo "<td>".$row['total']."</td>";
    echo "<td>".$row['rans']."</td>";
    echo "<td><a href=\"review.php?sid=".$row['sid']."\"><i class=\"far fa-file-alt\" style=\"color:#589a13\"></i></a></td>";
    echo "</tr>";
}
mysqli_close($mysqli);

?>
    </table>
        </div>
    </div>
</div>
</body>
</html>