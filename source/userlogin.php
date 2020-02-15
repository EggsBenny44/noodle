<?php
include("database_include.php");

session_start();

//check for required fields from the form
if (!(filter_input(INPUT_POST,'email')) || !(filter_input(INPUT_POST,'password'))) {
	header("Location: userlogin.html");
	exit;
}

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
} else {
//create and issue the query
    doDB();

    $sql = "SELECT * FROM account WHERE email = '" . filter_input(INPUT_POST, 'email') .
        "' AND password = " . "PASSWORD('" . filter_input(INPUT_POST, 'password') . "');";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//get the number of rows in the result set; should be 1 if a match
    if (mysqli_num_rows($result) == 1) {


        //if authorized, get the values of firstname and lastname
        while ($info = mysqli_fetch_array($result)) {
            $uid = $info['uid'];
            $email = $info['email'];
            $fname = $info['fname'];
            $lname = $info['lname'];
        }

        //set authorization cookie
        setcookie("auth", "1");
        $_SESSION['uid'] = $uid;
        $_SESSION['email'] = $email;
        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;

        $query = "UPDATE account SET lastLogin = CURDATE() where uid = $uid";
        $res = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
        mysqli_close($mysqli);

        //redirect to Services Page
        header("Location: mainmenu.php");
    } else {
        //redirect back to login form if not authorized
        header("Location: userlogin.html");
        exit;
    }
}
?>


