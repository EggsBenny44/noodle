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
    <link rel="stylesheet" type="text/css" href="./css/myStyle.css?1234"></head>
    <?php
        include("favicons.php");
    ?>    
<body>
<div class="contents">
    <?php
    include("database_include.php");
    session_start();
    doDB();
    include("header.php");
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sid = filter_input(INPUT_GET, 'sid');
        $sql = "SELECT * from submission WHERE sid = $sid ";
        $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        $tid = "";
        $tname = "";
        $rans = 0;
        $total = 0;
        $sdate = "";
        while ($row = mysqli_fetch_array($result)) {
            $tid = $row['tid'];
            $tname = $row['tname'];
            $rans = $row['rans'];
            $total = $row['total'];
            $sdate = $row['submitAt'];
        }

    ?>
    <div class="breadcrumbs">
        <a href="mainmenu.php">MENU</a> > <a href="history.php">SUBMISSION HISTORY</a> > REVIEW TEST
    </div>
    <div class="sub-contents">

        <div style="display: flex; flex-flow: column;position: relative;width:90%"">
        <h2 style="border-bottom: 2px solid #336699;width: 100%;"><?php echo $tname."(".$sdate.")"; ?> </h2>
        <div>
            <p id="grade"><?php echo "You got correct answers ".$rans." in ".$total." questions."; ?></p>
            <input id="tid" name="tid" type="hidden" value="<?php echo $tid; ?>">
            <input id="qid" name="qid" type="hidden">
            <?php
            $qcnt = 1;
            $sql = "SELECT * from result WHERE sid = $sid ORDER BY qid ";
            $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
            while ($row = mysqli_fetch_array($result)) {
                $icon = "<i class=\"far fa-times-circle\" style=\"color:#F46B75\"></i>";
                if ($row['rw'] == 0) {
                    $icon = "<i class=\"fas fa-check-circle\" style=\"color:#9BCB57\"></i>";
                }
                $ranswer = $row['answer'];
                $yanswer = $row['yanswer'];
                if ($row['qtype'] === "T") {
                    $ranswer = $row['answer'] === "0" ? "True" : "False";
                    $yanswer = $row['yanswer'] === "0" ? "True" : "False";
                }
                echo "<table class=\"result\" >";
                echo "<tr><td colspan='5'><i class=\"fas fa-question-circle\"></i>&nbsp;&nbsp;".$qcnt.": ".$row['question']."</td></tr>";
                echo "<tr><td width='10%'>".$icon ."</td><th width='20%'>Right answer: </th><td width='25%'>".$ranswer.
                    "</td><th width='20%'>Your answer: </th><td width='25%'>".$yanswer."</td></tr>";
                echo "</div>";
                $qcnt++;
            }
            }
            mysqli_close($mysqli);
            ?>
        </div>
    </div>

</div>
</div>
</body>
</html>