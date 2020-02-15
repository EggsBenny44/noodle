<?php
include("database_include.php");
session_start();
doDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = filter_input(INPUT_POST, 'tid');
    $qid = filter_input(INPUT_POST, 'qid');
    $question = filter_input(INPUT_POST, 'question');
    $qtype = filter_input(INPUT_POST, 'qtype');
    if ( $qid === "") {
        $sql = sprintf("INSERT INTO question VALUES (\"%s\", null, '%s',  '%s')", $tid, $question, $qtype);
    } else {
        $sql = sprintf("UPDATE question SET question = \"%s\", qtype = '%s' WHERE tid = %s and qid = %s", $question, $qtype, $tid, $qid);
    }
    $res = mysqli_query($mysqli, $sql);
    if ($res === TRUE) {
        if ($qid === "") {
            $qid = mysqli_insert_id($mysqli);
        } else {
            $sql = sprintf("DELETE FROM answer where tid = %s and qid = %s", $tid, $qid);
            $res = mysqli_query($mysqli, $sql);
        }
        switch ($qtype) {
            case "M":
                $op = $_POST['op'];
                $ma = $_POST['ma'];
                for ($i = 0 ; $i < count($op); $i++) {
                    if ($op[$i] !== "") {
                        $isAns = 1;
                        foreach ($ma as &$ans) {
                            if ($i === (int)$ans) {
                                $isAns = 0;
                            }
                        }
                        $sql = sprintf("INSERT INTO answer VALUES (%s, %s, null, \"%s\", %u)", $tid, $qid, $op[$i], $isAns);
                        $res = mysqli_query($mysqli, $sql);
                    }
                }
                break;
            case "T":
                $ta = filter_input(INPUT_POST, 'ta');
                $sql = sprintf("INSERT INTO answer VALUES (%s, %s, null, \"%s\", %u)", $tid, $qid, $ta, 0);
                $res = mysqli_query($mysqli, $sql);
                break;
            case "S":
                $sa = filter_input(INPUT_POST, 'sa');
                $sql = sprintf("INSERT INTO answer VALUES (%s, %s, null, '%s', %u)", $tid, $qid, $sa, 0);
                $res = mysqli_query($mysqli, $sql);
                break;
        }

        $sql = "select tq.tid, tq.qid, tq.question, tq.qtype, ta.ans from question tq "
                ."left join ( "
                ."select tid, qid, group_concat(distinct choice order by aid desc separator ',') as ans from answer "
                ."where answer = 0 group by tid, qid) ta "
                ."on tq.tid = ta.tid and tq.qid = ta.qid "
                ."where tq.tid = $tid "
                ."order by tq.tid, tq.qid";

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