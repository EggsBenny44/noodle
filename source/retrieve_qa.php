<?php
include("database_include.php");
session_start();
doDB();
$qaList = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tid = filter_input(INPUT_GET, 'tid');
    $qid = filter_input(INPUT_GET, 'qid');
    $sql = "select * from answer where tid = $tid and qid = $qid order by tid, qid, aid ";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    while ($row = mysqli_fetch_array($result)) {
        $ansList[] = array(
            'choice' => $row['choice'],
            'answer' => $row['answer']
        );
    }
    $sql = "select * from question where tid = $tid and qid = $qid ";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    while ($row = mysqli_fetch_array($result)) {
        $qaList[] = array (
            'tid'    => $row['tid'],
            'qid'  => $row['qid'],
            'question' => $row['question'],
            'qtype' => $row['qtype'],
            'ans' => $ansList
        );
    }
}

mysqli_close($mysqli);
header('Content-type: application/json');
echo json_encode($qaList);

?>
