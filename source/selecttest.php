<?php
include("database_include.php");
session_start();
doDB();
$sql = "SELECT *, (SELECT count(*) FROM question where tid = test.tid) qnum FROM test WHERE enabled = 0 ORDER BY tid";
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
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
    <?php
        include("favicons.php");
    ?>    
</head>
<body>
<div class="contents">
    <?php
    include("header.php");
    ?>
    <div class="breadcrumbs">
        <a href="mainmenu.php">MENU</a> > SELECT TEST
    </div>
    <div class="sub-contents">

        <div class="enable-test-list" style="width: 80%; padding-top: 30px;">
            <table id="enable-test-table" style="width: 100%">
                <tr>
                    <th width="20px">try</th>
                    <th width="80%">question</th>
                    <th width="20%"># of Q</th>
                </tr>
                <?php
                $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td><a href=\"trytest.php?tid=".$row['tid']."\"><i class=\"fas fa-hourglass-start\" style=\"color:#589a13\"></i></a></td>";
                    echo "<td>".$row['tname']."</td>";
                    echo "<td>".$row['qnum']."</td>";
                    echo "</tr>";
                }
                ?>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>

</html>
<?php
mysqli_close($mysqli);
?>


