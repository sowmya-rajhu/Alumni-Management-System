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
    .feedback-card {
        max-width: 600px;
        margin: 40px auto;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        background: #fff;
    }
    .feedback-card .card-body {
        padding: 35px 40px;
    }
    .feedback-card h4 {
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
        text-align: center;
    }
    .feedback-card .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        margin-top: 10px;
    }
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        margin-bottom: 15px;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        font-size: 40px;
        color: #ccc;
        cursor: pointer;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f5a623;
    }
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Feedback</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 pb-5">
    <?php if(!isset($_SESSION['login_id'])): ?>
    <div class="alert alert-warning text-center">
        Please <a href="#" onclick="uni_modal('Login','login.php')">login</a> to submit feedback.
    </div>
    <?php else: ?>
    <div class="feedback-card">
        <div class="card-body">
            <h4>Submit Your Feedback</h4>
            <div id="msg"></div>
            <form id="feedback_form">
                <div class="form-group">
                    <label class="control-label">Subject <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="subject" required placeholder="Enter subject">
                </div>
                <div class="form-group">
                    <label class="control-label">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="message" rows="5" required placeholder="Write your feedback here..."></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Rating <span class="text-danger">*</span></label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5">&#9733;</label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4">&#9733;</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3">&#9733;</label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2">&#9733;</label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1">&#9733;</label>
                    </div>
                </div>
                <button class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Previous Feedbacks -->
    <?php if(isset($_SESSION['login_id'])): ?>
    <div class="mt-5">
        <h4 class="text-center text-white">Your Previous Feedbacks</h4>
        <hr class="divider">
        <?php
        $feedbacks = $conn->query("SELECT * FROM feedback WHERE user_id = '{$_SESSION['login_id']}' ORDER BY date_created DESC");
        if($feedbacks->num_rows == 0):
        ?>
        <p class="text-center text-white">You have not submitted any feedback yet.</p>
        <?php else: while($row = $feedbacks->fetch_assoc()): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="text-dark"><b><?php echo $row['subject'] ?></b></h5>
                <p class="text-muted"><small><?php echo date("F d, Y h:i A", strtotime($row['date_created'])) ?></small></p>
                <p><?php echo $row['message'] ?></p>
                <p>
                    <?php for($i=1; $i<=5; $i++): ?>
                        <span style="color:<?php echo $i <= $row['rating'] ? '#f5a623' : '#ccc' ?>; font-size:20px;">&#9733;</span>
                    <?php endfor; ?>
                </p>
            </div>
        </div>
        <?php endwhile; endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
$('#feedback_form').submit(function(e){
    e.preventDefault();
    $.ajax({
        url: 'admin/ajax.php?action=save_feedback',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp){
            if(resp == 1){
                $('#msg').html('<div class="alert alert-success">Feedback submitted successfully! Thank you.</div>');
                $('#feedback_form')[0].reset();
                setTimeout(function(){
                    location.reload();
                }, 2000);
            } else {
                $('#msg').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
            }
        }
    });
});
</script>