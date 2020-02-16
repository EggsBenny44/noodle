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
    <?php
        include("favicons.php");
    ?>
</head>
<body>
    <div class="contents">
        <?php
        include("header.php");
        ?>
        <div class="sub-contents">
            <div class="menu">
                <div class="item-maker" onclick="location.href='createtest.php'" style="cursor:pointer">
                    <div class="menu-content"><p><i class="fas fa-poll-h  fa-7x"></i><br>Create Test</p></div>
                </div>
                <div class="item-test" onclick="location.href='selecttest.php'" style="cursor:pointer">
                    <div class="menu-content"><p><i class="fas fa-hourglass-start  fa-7x"></i><br>Test</p></div>
                </div>
                <div class="item-score" onclick="location.href='history.php'" style="cursor:pointer">
                    <div class="menu-content"><p><i class="fas fa-medal  fa-7x"></i><br>Submission</p></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
