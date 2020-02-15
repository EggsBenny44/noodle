<?php
include("database_include.php");
session_start();
doDB();
$qaList = array();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tid = filter_input(INPUT_GET, 'tid');
    $qid = filter_input(INPUT_GET, 'qid');
    if ($qid === null) {
        $sql = "select tq.tid, tq.qid, tq.question, tq.qtype, ta.ans from question tq "
            ."left join ( "
            ."select tid, qid, group_concat(distinct choice order by aid separator ',') as ans from answer "
            ."where answer = 0 group by tid, qid) ta "
            ."on tq.tid = ta.tid and tq.qid = ta.qid "
            ."where tq.tid = $tid "
            ."order by tq.tid, tq.qid ";
    } else {
        $sql = "select tq.tid, tq.qid, tq.question, tq.qtype, ta.ans from question tq "
            ."left join ( "
            ."select tid, qid, group_concat(distinct choice order by aid separator ',') as ans from answer "
            ."where answer = 0 group by tid, qid) ta "
            ."on tq.tid = ta.tid and tq.qid = ta.qid "
            ."where tq.tid = $tid and tq.qid = $qid "
            ."order by tq.tid, tq.qid ";
    }
}
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
while ($row = mysqli_fetch_array($result)) {
    $qaList[] = array (
        'tid'    => $row['tid'],
        'qid'  => $row['qid'],
        'question' => $row['question'],
        'qtype' => $row['qtype'],
        'ans' => $row['ans']
    );
}
header('Content-type: application/json');
echo json_encode($qaList);
mysqli_close($mysqli);
?>
