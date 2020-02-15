<?php

include("database_include.php");
session_start();
doDB();
$tname  = "";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tid = filter_input(INPUT_GET, 'tid');
    $sql = sprintf("SELECT * FROM test WHERE tid = %s", $tid);
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    if (mysqli_num_rows($result) == 1) {
        while ($info = mysqli_fetch_array($result)) {
            $tname = $info['tname'];
        }
    }
}
mysqli_close($mysqli);
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
        <a href="mainmenu.php">MENU</a> > <a href="createtest.php">TEST MAINTENANCE</a> > QA MAINTENANCE
    </div>
    <div class="sub-contents">

        <div style="display: flex; flex-flow: column;position: relative;">
            <h2 style="border-bottom: 2px solid #336699;width: 100%;"><?php echo $tname; ?> </h2>
            <div>
                <form id="question-form" method="POST" action="" style="width:100%">
                    <fieldset>
                        <input id="tid" name="tid" type="hidden" value="<?php echo $tid; ?>">
                        <input id="qid" name="qid" type="hidden">
                        <legend style="color:#336699">Question & Answer</legend>
                        <p id="mode">New</p>
                        <div>
                        <i class="fas fa-question-circle" style="padding-right: 10px"></i>
                            <input type="text" id="question" name="question" size="90" maxlength="1000" placeholder="Please enter a question." required><br><br>
                        </div>
                        <p id="errorq"></p>
                        <i class="fas fa-ellipsis-h" style="padding-right: 10px"></i>
                        <select id="qtype">
                            <option value="M" selected>Multiple choice</option>
                            <option value="T">True / False</option>
                            <option value="S">Short Answer</option>
                        </select>&nbsp; &nbsp; <label id="multiple-note" style="color: #F46B75">Please checkbox on if the option is answer</label><br><br>
                        <div id="multiple">
                            <i class="fas fa-list-ol" style="padding-right: 10px"></i>
                            <input type="text" id="op0" name="op0" size="15" placeholder="Option #1" required> <input type="checkbox" class="ma0" id="ma" name="ma" value="0" size="20" maxlength="20">
                            <input type="text" id="op1" name="op1" size="15" placeholder="Option #2"> <input type="checkbox" class="ma1" id="ma" name="ma" value="1" size="20" maxlength="20">
                            <input type="text" id="op2" name="op2" size="15" placeholder="Option #3"> <input type="checkbox" class="ma2" id="ma" name="ma" value="2" size="20" maxlength="20">
                            <input type="text" id="op3" name="op3" size="15" placeholder="Option #4"> <input type="checkbox" class="ma3" id="ma" name="ma" value="3" size="20" maxlength="20">
                            <input type="text" id="op4" name="op4" size="15" placeholder="Option #5"> <input type="checkbox" class="ma4" id="ma" name="ma" value="4" size="20" maxlength="20">
                        </div>
                        <p id="errorm"></p>
                        <div id="truefalse">
                            <i class="fas fa-list-ol" style="padding-right: 10px"></i>
                            <input type="radio" id="ta" name="ta" value="0" checked>
                            <label for="0">true</label>
                            <input type="radio" id="ta" name="ta" value="1">
                            <label for="1">false</label>
                        </div>
                        <div id="short">
                            <i class="fas fa-list-ol" style="padding-right: 10px"></i>
                            <input type="text" id="sa" name="sa" size="50" maxlength="50" placeholder="Answer">
                        </div>
                        <p id="errors"></p>
                        <div>
                            <input type="button" id="register" class="submit-btn" style="width: 100px;" value="REGISTER">&nbsp; &nbsp; &nbsp;
                            <input type="button" id="clear" name="clear" class="submit-btn" style="width: 100px;" value="CLEAR">
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="question-list">
                <table id="question-table" style="width: 100%">
                    <tr>
                        <th width="20px">edit</th>
                        <th >question</th>
                        <th width="20%">type</th>
                        <th width="30%">answer</th>
                        <th width="20px">delete</th>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
<script type="text/javascript">
    $(function(){
        $("#truefalse").css("display", "none");
        $("#short").css("display", "none");

        function validateForm() {
            $('#errorq').text("");
            $('#errorm').text("");
            $('#errors').text("");
            let ret = true;
            let question = $('#question').val().trim();
            if (question === "") {
                $('#errorq').text("Question is required");
                ret =  false;
            } else if (question.length > 1000) {
                $('#errorq').text("Please enter less than 100 characters");
                ret =  false;
            }
            if ($('#qtype').val() == "M") {
                op = $('#op0').val().trim() + $('#op1').val().trim() + $('#op2').val().trim() + $('#op3').val().trim() + $('#op4').val().trim();
                ma = $('input[name=ma]:checked').map(function(){
                    return $(this).val();
                }).get();
                let msg = "";
                if (op.length == 0) {
                    msg = "Please enter at least 1 answer ";
                }
                if (ma.length == 0) {
                    if (msg === "") {
                        msg += "Please ";
                    } else {
                        msg += "and ";
                    }
                    msg += " select at least 1 checkboxes";
                }
                if (msg === "") {
                    for(let i = 0; i < ma.length; i++){
                        if ($('#op'+ ma[i]).val().trim() === "") {
                            msg = "Please enter the answer if the checkbox is on ";
                            break;
                        }
                    }
                }
                if (msg !== "") {
                    $('#errors').text(msg);
                    ret = false;
                }
            } else if ($('#qtype').val() == "S") {
                let sa = $('#sa').val().trim();
                if (sa === "") {
                    $('#errors').text("Answer is required");
                    ret =  false;
                } else if (sa.length > 50) {
                    $('#errors').text("Please enter less than 50 characters");
                    ret =  false;
                }
            }
            return ret;
        }

        function getLisData() {
            $.ajax({
                type: "GET",
                url: "search_qa.php",
                datatype: "json",
                data: { tid : $('#tid').val() },
                success: function(data) {
                    $.each(data, function(key, value){
                        updateLisData(value);
                    });
                    console.log("success");
                    console.log(data);
                },
                error: function(){
                    console.log("fail desy");
                }
            });
        }
        function updateLisData(value) {
            let typ;
            let answers;
            answers = value.ans;
            if (value.qtype === "M") {
                typ = "Multiple choice";
            } else if (value.qtype === "T") {
                typ = "True / False";
                if (value.ans === "0") {
                    answers = "True"
                } else {
                    answers = "False"
                }
            } else {
                typ = "Short answer";
            }
            $('#question-table').append("<tr><td><a class=\"detail\" href='#' data-id=\"" +  value.tid + "," + value.qid +"\" ><i class=\"fas fa-pen-alt\" style=\"color:#FFC335;\"></i></a></td>" +
                "<td>" + value.question + "</td>" + "<td>" + typ + "</td>"+ "<td>" + answers + "</td>"  +
                "<td><a class=\"delete\" href='#' data-id=\"" + value.tid + "," + value.qid + "\" ><i class=\"fas fa-trash-alt\" style=\"color:#a3a3a3;\"></i></a></td></tr>");

        }
        getLisData();

        $(document).on('click', '.detail', function(e) {
            $('#errorq').text("");
            $('#errorm').text("");
            $('#errors').text("");
            e.preventDefault();
            $('#mode').text("EDIT");
            let ids = $(this).data('id').split(",");
            $('#qid').val(ids[1]);
            $.ajax({
                type: "GET",
                url: "retrieve_qa.php",
                datatype: "json",
                data: { tid: ids[0], qid: ids[1]},
                success: function(data) {
                    $('#qid').val(data[0].qid);
                    $('#question').val(data[0].question);
                    $('#qtype').val(data[0].qtype);
                    $('#qtype').trigger('change');
                    if (data[0].qtype === "M") {
                        for (i = 0; i < data[0].ans.length; i++) {
                            let nm = "#op" + i;
                            $(nm).val(data[0].ans[i].choice);
                            if (data[0].ans[i].answer == "0") {
                                $('.' + "ma"+i).prop('checked', true);
                            }
                        }
                    } else if (data[0].qtype === "T") {
                        // $('#ta').val(data[0].ans[0]);
                        if (data[0].ans[0].choice === "0") {
                            $('input[name=ta]:eq(0)').prop('checked', true);
                        } else {
                            $('input[name=ta]:eq(1)').prop('checked', true);
                        }
                    } else {
                        $('#sa').val(data[0].ans[0].choice);
                    }

                    console.log("success");
                    console.log(data);
                },
                error: function(data) {
                    console.log("fail");
                    console.log(data);
                }
            });

            return false;
        });

        $(document).on('click', '.delete', function(e) {
            var confirmResult = window.confirm("Are you sure you want to delete? Answers are also deleted permanently.");
            if (!confirmResult) return;
            e.preventDefault();
            let ids = $(this).data('id').split(",");
            $.ajax({
                type: "GET",
                url: "delete_qa.php",
                datatype: "json",
                data: { tid: ids[0], qid: ids[1] },
                success: function(data) {
                    $('#question-table').find("tr:gt(0)").remove();
                    $.each(data, function(key, value){
                        updateLisData(value);
                    });
                },
                error: function(data) {
                    console.log("fail");
                    console.log(data);
                }
            });

            return false;
        });

        $('#qtype').on('change',function(){
            $('#errorq').text("");
            $('#errorm').text("");
            $('#errors').text("");            let op = this.value;
            $("#multiple").css("display", "none");
            $("#multiple-note").css("display", "none");
            $("#truefalse").css("display", "none");
            $("#short").css("display", "none");

            if (op == "M") {
                $("#multiple").toggle();
                $("#multiple-note").toggle();
            } else if (op == "T") {
                $("#truefalse").toggle();
            } else {
                $("#short").toggle();
            }
        });
        function clearForm() {
            $('#question-form')[0].reset();
            $("#qid").val("");
            $("#multiple").css("display", "none");
            $("#multiple-note").css("display", "none");
            $("#truefalse").css("display", "none");
            $("#short").css("display", "none");
            $("#multiple").toggle();
            $("#multiple-note").toggle();
            $('#mode').text("NEW");
            $('#errorq').text("");
            $('#errorm').text("");
            $('#errors').text("");        }
        $('#clear').on('click',function(){
            clearForm();
        });
        $('#register').on('click',function(){
            // var confirmResult = window.confirm("Are you sure you want to register?");
            // if(confirmResult) {
            if (validateForm() === false) return false;
            let op, ma, ta, sa;
            if ($('#qtype').val() == "M") {
                op = [$('#op0').val(), $('#op1').val(), $('#op2').val(), $('#op3').val(), $('#op4').val()];
                ma = $('input[name=ma]:checked').map(function(){
                    return $(this).val();
                }).get();


            } else if ($('#qtype').val() == "T") {
                    ta = $("input[name='ta']:checked").val();
                } else {
                    sa = $('#sa').val();
                    console.log("sa:"+sa)
                }
                $.ajax({
                    type: "POST",
                    url: "register_qa.php",
                    datatype: "json",
                    data: {
                        "tid" : $('#tid').val(),
                        "qid" : $('#qid').val(),
                        "question" : $('#question').val(),
                        "qtype" : $('#qtype').val(),
                        "op" : op,
                        "ma" : ma,
                        "ta" : ta,
                        "sa" : sa
                    },
                    success: function(data) {
                        //window.alert("Data was updated successfully!!")
                        console.log("success??");
                        console.log(data);
                        $('#question-table').find("tr:gt(0)").remove();
                        $.each(data, function(key, value){
                            updateLisData(value);
                        });
                        clearForm();
                    },
                    error: function(data) {
                        console.log("fail?");
                        console.log(data);
                    }
                });
            // }
            return false;
        });


    });
</script>
</html>



