<?php
session_start();
session_destroy();
unset($_SESSION["uid"]);
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
</head>
<body>
<div class="contents">
    <?php
    include("header.php");
    ?>
    <div class="breadcrumbs">
    </div>
    <div class="sub-contents">

        <div style="display: flex; flex-flow: column;position: relative;width:90%"">
            <div style="display: flex;flex-flow: row; justify-content: center;">
                <form id="account-form" method="POST" action="" style="width:60%">
                    <fieldset>
                        <legend><h3>User Information</h3></legend>
                        <div style="display: grid;grid-gap: 3px 10px;grid-template-columns:20px auto; padding: 0 20px;">
                            <i class="fas fa-user"></i>
                            <div>
                                <input id="fname" name="fname"  size="40" maxlength="30"　type="text" placeholder="First Name"/>
                                <p id="error"></p>
                            </div>
                            <div></div>
                            <div>
                                <input id="lname" name="lname"  size="40" maxlength="30"　type="text" placeholder="Last Name"/>
                                <p id="errorl"></p>
                            </div>
                            <i class="far fa-envelope"></i>
                            <div>
                                <input id="email" name="email"  size="60" maxlength="50"　type="email" placeholder="email" />
                                <p id="errore"></p>
                            </div>
                            <i class="fas fa-key"></i>
                            <div>
                                <input type="password" name="psw" size="60" id="psw" maxlength="100" placeholder="password"/>
                                <p id="errorp"></p>
                            </div>
                            <div></div>
                            <div>
                                <input type="password" name="confpsw" id="confpsw" size="60" maxlength="100" placeholder="Confirm password"/>
                                <p id="errorcp"></p>
                            </div>
                        </div>

                        <div style="grid-column-start: 1;grid-column-end: 3;justify-self: center;">
                            <input type="button" id="register" class="submit-btn" style="width: 80px;" value="REGISTER">
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>
</div>
</body>

<script type="text/javascript">
    $(function(){
        function validateForm() {
            let ret = true;
            let fname = $('#fname').val().trim();
            if (fname === "") {
                $('#error').text("First name is required");
                ret = false;
            } else if (fname.length > 20) {
                $('#error').text("Please enter less than 20 characters");
                ret = false;
            }
            let lname = $('#lname').val().trim();
            if (lname === "") {
                $('#errorl').text("Last name is required");
                ret = false;
            } else if (lname.length > 20) {
                $('#errorl').text("Please enter less than 20 characters");
                ret = false;
            }
            let email = $('#email').val().trim();
            if (email === "") {
                $('#errore').text("email is required");
                ret = false;
            } else if (email.length > 255) {
                $('#errore').text("Please enter less than 255 characters");
                ret = false;
            }
            let psw = $('#psw').val().trim();
            if (psw === "") {
                $('#errorp').text("password is required");
                ret = false;
            } else if (psw.length > 20) {
                $('#errorp').text("Please enter less than 20 characters");
                ret = false;
            }
            let confpsw = $('#confpsw').val().trim();
            if (confpsw === "") {
                $('#errorcp').text("comfirm password is required");
                ret = false;
            } else if (confpsw.length > 20) {
                $('#errorcp').text("Please enter less than 20 characters");
                ret = false;
            } else if (psw != confpsw) {
                $('#errorcp').text("Please enter the same password");
                ret = false;
            }
            return ret;
        }

        // #on Click the register button
        $('#register').on('click',function(){
            $('#error').text("");
            $('#errorl').text("");
            $('#errore').text("");
            $('#errorp').text("");
            $('#errorcp').text("");
            if (validateForm() === false) return false;
            $.ajax({
                type: "POST",
                url: "register_account.php",
                datatype: "json",
                data: {
                    "fname" : $('#fname').val(),
                    "lname" : $('#lname').val(),
                    "email" : $('#email').val(),
                    "psw" : $('#psw').val()
                },
                success: function(data) {
                    console.log("success");
                    window.alert("Your account has been registered. Thank you!!");
                    $('#account-form')[0].reset();
                },
                error: function(data) {
                    console.log("fail");
                    $('#errore').text("This email address already has been registered");
                    return false;
                }
            });
            return false;
        });
    });
</script>

</html>
