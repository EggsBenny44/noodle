<?php
include("database_include.php");
session_start();
doDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tid = filter_input(INPUT_GET, 'tid');
    $sql = sprintf("DELETE FROM test WHERE tid = %s", $tid);
    $res = mysqli_query($mysqli, $sql);
    if ($res === TRUE) {
        $sql = "SELECT * FROM test order by tid";
        $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        while ($row = mysqli_fetch_array($result)) {
            $testList[] = array(
                'tid'    => $row['tid'],
                'tname'  => $row['tname'],
                'ena' => $row['enabled'],
                'qnum'  => 0
            );
        }
        header('Content-type: application/json');
        echo json_encode($testList);
    } else {
        echo json_encode("err");
    }
    mysqli_close($mysqli);
}