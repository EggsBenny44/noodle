<?php
include("database_include.php");
session_start();
doDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tid = filter_input(INPUT_POST, 'tid');
    $tname = filter_input(INPUT_POST, 'tname');
    $ena = filter_input(INPUT_POST, 'ena');
    if ( $tid === "") {
        $sql = sprintf("INSERT INTO test VALUES (null, '%s', %s, %s, %s)", $tname, $_SESSION['uid'], $_SESSION['uid'], $ena);
    } else {
        $sql = sprintf("UPDATE test SET tname = '%s', update_uid = %s, enabled = %s WHERE tid = %s", $tname, $_SESSION['uid'], $ena, $tid);
    }
    $res = mysqli_query($mysqli, $sql);
    if ($res === TRUE) {
        $tid = mysqli_insert_id($mysqli);
        $sql = "SELECT *, (SELECT COUNT(qid) FROM question where tid = test.tid) qcnt FROM test ORDER BY tid";
        $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        while ($row = mysqli_fetch_array($result)) {
            $testList[] = array(
                'tid'    => $row['tid'],
                'tname'  => $row['tname'],
                'ena' => $row['enabled'],
                'qnum'  => $row['qcnt']
            );
        }
        header('Content-type: application/json');
        echo json_encode($testList);
    } else {
        echo json_encode("err");
    }
    mysqli_close($mysqli);

}