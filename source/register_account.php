<?php
include("database_include.php");
session_start();
doDB();
var_dump(http_response_code());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = filter_input(INPUT_POST, 'fname');
    $lname = filter_input(INPUT_POST, 'lname');
    $email = filter_input(INPUT_POST, 'email');
    $psw = filter_input(INPUT_POST, 'psw');
    $sql = "SELECT * FROM account where email = '$email'";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    if (mysqli_num_rows($result) != 0) {
        mysqli_close($mysqli);
        header('Temporary-Header: True', true, 404);
        header_remove('Temporary-Header');
    }
    $sql = sprintf("INSERT INTO account VALUES (\"%s\", null, '%s',  '%s')", $tid, $question, $qtype);
    $sql = sprintf("INSERT INTO account VALUES (null, '%s', '%s', '%s', PASSWORD('%s'), 1, CURDATE(), CURDATE())",
        $email, $fname, $lname, $psw);
    $res = mysqli_query($mysqli, $sql);
    mysqli_close($mysqli);

}
?>