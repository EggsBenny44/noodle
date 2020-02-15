<?php
include("database_include.php");
session_start();
doDB();
$testList = array();
$sql = "SELECT *, (SELECT COUNT(qid) FROM question where tid = test.tid) qcnt FROM test ORDER BY tid";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchName = filter_input(INPUT_POST, 'search_name');
    if ($searchName != "") {
        $sql = "SELECT *, (SELECT COUNT(qid) FROM question where tid = test.tid) qcnt FROM test where tname like '%$searchName%' ORDER BY tid";
    }
} else {
    $tid = filter_input(INPUT_GET, 'tid');
    if ($tid != "") {
        $sql = "SELECT * FROM test where tid = $tid";
    }
}
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
while ($row = mysqli_fetch_array($result)) {
    $testList[] = array(
        'tid'    => $row['tid'],
        'tname'  => $row['tname'],
        'ena' => $row['enabled'],
        'qnum'  => $row['qcnt']
    );
}
mysqli_close($mysqli);
header('Content-type: application/json');
echo json_encode($testList);
?>
