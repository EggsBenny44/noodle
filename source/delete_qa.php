<?php
include("database_include.php");
session_start();
doDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tid = filter_input(INPUT_GET, 'tid');
    $qid = filter_input(INPUT_GET, 'qid');
    $sql = sprintf("DELETE FROM question WHERE tid = %s and qid = %s", $tid, $qid);
    $res = mysqli_query($mysqli, $sql);
    if ($res === TRUE) {
        $sql = sprintf("DELETE FROM answer WHERE tid = %s and qid = %s", $tid, $qid);
        $res = mysqli_query($mysqli, $sql);

        $sql = "select tq.tid, tq.qid, tq.question, tq.qtype, ta.ans from question tq "
            ."left join ( "
            ."select tid, qid, group_concat(distinct choice order by aid separator ',') as ans from answer "
            ."where answer = 0 group by tid, qid) ta "
            ."on tq.tid = ta.tid and tq.qid = ta.qid "
            ."where tq.tid = $tid "
            ."order by tq.tid, tq.qid ";

        $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        while ($row = mysqli_fetch_array($result)) {
            $qaList[] = array(
                'tid'    => $row['tid'],
                'qid'  => $row['qid'],
                'question' => $row['question'],
                'qtype' => $row['qtype'],
                'ans' => $row['ans']
            );
        }
        header('Content-type: application/json');
        echo json_encode($qaList);
    } else {
        echo json_encode("err");
    }
    mysqli_close($mysqli);
}