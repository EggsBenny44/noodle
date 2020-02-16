<?php
include("database_include.php");
session_start();
doDB();
$qaList = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = filter_input(INPUT_POST, 'tid');
    $sql = "SELECT tname from test WHERE tid = $tid ";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    $tname = "";
    while ($row = mysqli_fetch_array($result)) {
        $tname = $row[0];
    }
    $sql = sprintf("INSERT INTO submission VALUES (null, %s, %s, '%s', 0, 0, CURDATE()) ", $_SESSION['uid'], $tid, $tname);
    $res = mysqli_query($mysqli, $sql);
    if ($res !== TRUE) {
        echo json_encode("err");
    }
    $sid = mysqli_insert_id($mysqli);
    $sql = "select tq.tid, tq.qid, tq.question, tq.qtype, ta.opt, ca.ans from question tq "
        ."inner join ( select tid, qid, "
        ."group_concat(distinct choice order by aid separator ',') as opt from answer group by tid, qid) ta "
        ."inner join ( select tid, qid, group_concat(distinct choice order by aid separator ',') as ans "
        ."from answer where answer = 0 group by tid, qid) ca "
        ."on tq.tid = ta.tid and tq.qid = ta.qid and tq.tid = ca.tid and tq.qid = ca.qid "
        ."where tq.tid = $tid order by tq.tid, tq.qid";

    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    $qcnt = mysqli_num_rows($result);
    $yans = [];
    while ($row = mysqli_fetch_array($result)) {
        if ($row['qtype'] === "M") {
            if(isset($_POST["q".$row['qid']])) {
                $yans = implode(",", $_POST["q".$row['qid']]);
            } else {
                $yans = "";
            }
        } else {
            $yans = filter_input(INPUT_POST, "q".$row['qid']);
        }
        $sql = sprintf("INSERT INTO result VALUES (%s, null, %s, '%s', '%s', '%s', '%s', '%s', %b) ",
            $sid, $row['qid'], $row['question'], $row['qtype'], $row['opt'], $row['ans'], $yans, strtolower($row['ans']) === strtolower($yans) ? 0 : 1 );
        $res = mysqli_query($mysqli, $sql);
        if ($res !== TRUE) {
            echo json_encode("err");
        }
    }
    $sql = "SELECT COUNT(sid), SUM(rw) from result WHERE sid = $sid ";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    $total = 0;
    $rans = 0;
    while ($row = mysqli_fetch_array($result)) {
        $total = $row[0];
        $rans = $row[0] - $row[1];
    }
    $sql = "UPDATE submission SET total = $total, rans = $rans WHERE sid = $sid ";
    $res = mysqli_query($mysqli, $sql);
    if ($res !== TRUE) {
        echo json_encode("err");
    }
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
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
            <a href="mainmenu.php">MENU</a> > <a href="selecttest.php">SELECT TEST</a> > TRY TEST
        </div>
        <div class="sub-contents">

        <div style="display: flex; flex-flow: column;position: relative;width:90%"">
        <h2 style="border-bottom: 2px solid #336699;width: 100%;"><?php echo $tname; ?> </h2>
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