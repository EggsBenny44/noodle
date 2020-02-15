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
        <a href="mainmenu.php">MENU</a> > TEST MAINTENANCE
    </div>
    <div class="sub-contents">
        <div class="maintenance">
            <div class="maintenance-form">
                <form id="test-form" method="POST" action="">
                    <fieldset>
                        <legend style="color:#336699">Create or Update Test</legend>
                        <p id="mode">New</p>
                        <div style="padding-bottom: 10px">
                            <input  type="hidden" id="tid" name="tid" size="5" readonly>
                        </div>
                        <div style="padding-bottom: 10px">
                            <i class="fas fa-file-alt"></i>&nbsp;&nbsp;<input type="text" id="tname" name="tname" size="25" maxlength="30" placeholder="Please enter the test title" required>
                            <p id="error"></p>
                        </div>
                        <div style="padding-bottom: 10px">
                            <i class="far fa-thumbs-up"></i>&nbsp;&nbsp;
                            <input type="radio" id="ena" name="ena" value="0">
                            <label for="0">enable</label>
                            <input type="radio" id="ena" name="ena" value="1" checked>
                            <label for="1">disable</label>
                        </div>
                        <div style="padding-bottom: 5px">
                            <input type="button" id="register" class="submit-btn" style="width: 80px;" value="REGISTER">
                            <input type="button" id="clear" name="clear" class="submit-btn" style="width: 80px;" value="CLEAR">
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="maintenance-list">
                <form id="search-form" method="POST" action="" style="border-bottom: 2px solid #336699;">
                    <div class="search">
                        <i class="fas fa-search" style="color:#2FB6FD;"></i>&nbsp;<input type="text" id="search_name" name="search_name" size="50" maxlength="50" placeholder="Please enter the search test name.">
                        &nbsp; &nbsp;<input type="button" id="search" name="search" class="submit-btn" value="SEARCH">
                    </div>
                </form>
                <br>
                <table id="test-list">
                    <tr>
                        <th width="20px">edit</th>
                        <th width="20px">QA</th>
                        <th width="70%">test name</th>
                        <th width="20%">enabled</th>
                        <th width="10%">#Q</th>
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
        function validateForm() {
            let tname = $('#tname').val().trim();
            if (tname === "") {
                $('#error').text("Test name is required");
                return false;
            } else if (tname.length > 50) {
                $('#error').text("Please enter less than 50 characters");
                return false;
            }
            return true;
        }

        function getLisData() {
            $.ajax({
                type: "GET",
                url: "search_test.php",
                datatype: "json",
                data: { },
                success: function(data) {
                    $.each(data, function(key, value){
                        updateLisData(value);
                      });
                    console.log("success");
                    console.log(data);
                },
                error: function(){
                    console.log("fail");
                }
            });
        }
        function updateLisData(value) {
            $('#test-list').append(
                "<tr><td><a class=\"detail\" href='#' data-id=\"" + value.tid +"\" ><i class=\"fas fa-pen-alt\" style=\"color:#FFC335;\"></i></a></td>" +
                "<td><a href=\"createqa.php?tid=" + value.tid + "\"><i class=\"fas fa-plus-circle\" style=\"color:#589a13;\"></i></a></td>" +
                "<td>" + value.tname + "</td><td>" + (value.ena === '0' ? '<i class=\"far fa-thumbs-up\" style=\"color:#2FB6FD;\"></i>' : '')  + "</td><td>" + value.qnum + "</td>" +
                "<td><a class=\"delete\" href='#' data-id=\"" + value.tid +"\" ><i class=\"fas fa-trash-alt\" style=\"color:#a3a3a3;\"></i></a></td></tr>");
        }
        getLisData();
        $(document).on('click', '.detail', function(e) {
            $('#error').text("");
            e.preventDefault();
            $('#mode').text("EDIT");
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "search_test.php",
                datatype: "json",
                data: { tid: id },
                success: function(data) {
                    $('#tid').val(data[0].tid);
                    $('#tname').val(data[0].tname);
                    $("input[name='ena']").val([data[0].ena]);
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
            var confirmResult = window.confirm("Are you sure you want to delete? Questions and Answers are also deleted permanently.");
            if (!confirmResult) return;
            e.preventDefault();
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "delete_test.php",
                datatype: "json",
                data: { tid: id },
                success: function(data) {
                    $('#test-list').find("tr:gt(0)").remove();
                    $.each(data, function(key, value){
                        updateLisData(value);
                    });
                    clearForm();
                },
                error: function(data) {
                    console.log("fail");
                    console.log(data);
                }
            });

            return false;
        });
        function clearForm() {
            $('#test-form')[0].reset();
            $('#mode').text("NEW");
            $('#error').text("");
            $('#tid').val("");
        }
        $('#clear').on('click',function(){
            clearForm();
        });
        
        $('#search').on('click',function(){
            let name = $('#search_name').val();
                $.ajax({
                    type: "POST",
                    url: "search_test.php",
                    datatype: "json",
                    data: {
                        "search_name" : name
                    },
                    success: function(data) {
                        $('#test-list').find("tr:gt(0)").remove();
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

        });
        // #on Click the register button
        $('#register').on('click',function(){
            if (validateForm() === false) return false;
            var ena = $("input[name='ena']:checked").val();
            // var confirmResult = window.confirm("Are you sure you want to register?");
            // if(confirmResult) {
                $.ajax({
                    type: "POST",
                    url: "register_test.php",
                    datatype: "json",
                    data: {
                        "tid" : $('#tid').val(),
                        "tname" : $('#tname').val(),
                        "ena" : ena
                    },
                    success: function(data) {
                        //window.alert("Data was updated successfully!!")
                        console.log("success??");
                        console.log(data);
                        $('#test-list').find("tr:gt(0)").remove();
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



