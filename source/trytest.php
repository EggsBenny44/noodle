<?php

include("database_include.php");
session_start();
doDB();
$tname  = "";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tid = filter_input(INPUT_GET, 'tid');
    $sql = sprintf("SELECT * FROM test WHERE tid = %s", $tid);
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    if (mysqli_num_rows($result) == 1) {
        while ($info = mysqli_fetch_array($result)) {
            $tname = $info['tname'];
        }
    }
    $sql = "SELECT q.tid, q.qid, question, qtype, aid, choice FROM question q INNER JOIN "
    ."( SELECT tid, qid, group_concat(distinct aid order by aid separator ',')as aid, "
    ."group_concat(DISTINCT choice ORDER BY aid separator ',') AS choice "
    ."FROM answer GROUP BY tid, qid) a ON (q.tid = a.tid AND q.qid = a.qid) WHERE q.tid = $tid ORDER BY q.tid, q.qid, a.aid ";

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
        <a href="mainmenu.php">MENU</a> > <a href="selecttest.php">SELECT TEST</a> > TRY TEST
    </div>
    <div class="sub-contents">

        <div style="display: flex; flex-flow: column;position: relative;width:90%"">
            <h2 style="border-bottom: 2px solid #336699;width: 100%;"><?php echo $tname; ?> </h2>
            <div>
                <form id="question-form" method="POST" action="submittest.php
" style="width:100%">
                    <input id="tid" name="tid" type="hidden" value="<?php echo $tid; ?>">
                    <input id="qid" name="qid" type="hidden">
                    <table class="result" style="width:100%">
                    <?php
                    $qcnt = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td><i class=\"fas fa-question-circle\"></i>&nbsp;&nbsp;".$qcnt.": ".$row['question']."</td>";
                        echo "</tr><tr>";

                        switch ($row['qtype']) {
                            case "M":
                                $choice = explode(",", $row['choice']);
                                $aids = explode(",", $row['aid']);
                                echo "<td>";
                                for ($i = 0; $i < count($choice); $i++) {
                                    echo "<input type=\"checkbox\" id=\"q".$row['qid']."[]\" name=\"q".$row['qid']."[]\" value=\"".$choice[$i]."\">".$choice[$i]."<br>";
                                }
                                echo "</td>";
                                break;
                            case "T":
                                echo "<td><input type=\"radio\" id=\"q".$row['qid']."\" name=\"q".$row['qid']."\" value=\"0\" checked>True &nbsp;&nbsp;";
                                echo "<input type=\"radio\" id=\"q".$row['qid']."\" name=\"q".$row['qid']."\" value=\"1\">False</td>";
                                break;
                            case "S":
                                echo "<td><input type=\"text\" id=\"q".$row['qid']."\" name=\"q".$row['qid']."\" value=\"\" size=\"50\" maxlength=\"50\" required></td>";
                                break;    
                        }
                        echo "</tr>";
                        $qcnt++;
                    }
                    ?>
                    </table>
                        <div>
                            <input type="submit" id="register" class="submit-btn" style="width: 100px;" value="SUBMIT">&nbsp; &nbsp; &nbsp;
                            <input type="button" id="clear" class="clear" style="width: 100px;" value="CLEAR">
                        </div>
                </form>
            </div>
        </div>

    </div>
</div>
</body>
</html>

<?php
}
mysqli_close($mysqli);
?>

<script type="text/javascript">
    $(function() {
        $('#clear').on('click',function(){
            $('#question-form')[0].reset();
        });
    })
</script>