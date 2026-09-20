<?php include 'admin/db_connect.php'; ?>
<style>
    .masthead{
        min-height: 23vh !important;
        height: 23vh !important;
    }
    .masthead:before{
        min-height: 23vh !important;
        height: 23vh !important;
    }
    .forgot-card {
        max-width: 500px;
        margin: 40px auto;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        background: #fff;
    }
    .forgot-card .card-body {
        padding: 35px 40px;
    }
    .forgot-card h4 {
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
        text-align: center;
    }
    .forgot-card .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        margin-top: 10px;
    }
    .step { display: none; }
    .step.active { display: block; }
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Forgot Password</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 pb-5">
    <div class="forgot-card">
        <div class="card-body">
            <h4>Reset Your Password</h4>

            <div id="msg"></div>

            <!-- Step 1 - Enter Email -->
            <div class="step active" id="step1">
                <div class="form-group">
                    <label class="control-label">Enter your registered Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email_input" placeholder="Enter your email">
                </div>
                <button class="btn btn-primary" onclick="checkEmail()">Next</button>
                <div class="text-center mt-3">
                    <small><a href="#" onclick="uni_modal('Login','login.php')">Back to Login</a></small>
                </div>
            </div>

            <!-- Step 2 - Answer Security Question -->
            <div class="step" id="step2">
                <div class="form-group">
                    <label class="control-label">Security Question</label>
                    <p class="font-weight-bold text-dark" id="security_question_text"></p>
                </div>
                <div class="form-group">
                    <label class="control-label">Your Answer <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="answer_input" placeholder="Enter your answer">
                </div>
                <button class="btn btn-primary" onclick="checkAnswer()">Verify Answer</button>
            </div>

            <!-- Step 3 - Reset Password -->
            <div class="step" id="step3">
                <div class="form-group">
                    <label class="control-label">New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="new_password" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label class="control-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" placeholder="Confirm new password">
                </div>
                <button class="btn btn-primary" onclick="resetPassword()">Reset Password</button>
            </div>

        </div>
    </div>
</div>

<script>
var user_email = '';

function checkEmail(){
    user_email = $('#email_input').val();
    if(user_email == ''){
        $('#msg').html('<div class="alert alert-danger">Please enter your email.</div>');
        return;
    }
    $.ajax({
        url: 'admin/ajax.php?action=check_email',
        method: 'POST',
        data: { email: user_email },
        success: function(resp){
            var data = JSON.parse(resp);
            if(data.status == 1){
                $('#msg').html('');
                $('#security_question_text').text(data.question);
                $('#step1').removeClass('active');
                $('#step2').addClass('active');
            } else {
                $('#msg').html('<div class="alert alert-danger">Email not found. Please check and try again.</div>');
            }
        }
    });
}

function checkAnswer(){
    var answer = $('#answer_input').val();
    if(answer == ''){
        $('#msg').html('<div class="alert alert-danger">Please enter your answer.</div>');
        return;
    }
    $.ajax({
        url: 'admin/ajax.php?action=check_answer',
        method: 'POST',
        data: { email: user_email, answer: answer },
        success: function(resp){
            if(resp == 1){
                $('#msg').html('');
                $('#step2').removeClass('active');
                $('#step3').addClass('active');
            } else {
                $('#msg').html('<div class="alert alert-danger">Wrong answer. Please try again.</div>');
            }
        }
    });
}

function resetPassword(){
    var new_pass = $('#new_password').val();
    var confirm_pass = $('#confirm_password').val();
    if(new_pass == '' || confirm_pass == ''){
        $('#msg').html('<div class="alert alert-danger">Please fill in both password fields.</div>');
        return;
    }
    if(new_pass != confirm_pass){
        $('#msg').html('<div class="alert alert-danger">Passwords do not match.</div>');
        return;
    }
    $.ajax({
        url: 'admin/ajax.php?action=reset_password',
        method: 'POST',
        data: { email: user_email, password: new_pass },
        success: function(resp){
            if(resp == 1){
                $('#msg').html('<div class="alert alert-success">Password reset successfully! Redirecting to login...</div>');
                setTimeout(function(){
                    location.href = 'index.php?page=home';
                }, 2000);
            } else {
                $('#msg').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
            }
        }
    });
}
</script>