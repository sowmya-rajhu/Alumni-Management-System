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
    .achievement-card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        background: #fff;
        margin-bottom: 25px;
        transition: transform 0.2s;
    }
    .achievement-card:hover {
        transform: translateY(-3px);
    }
    .achievement-card .card-body {
        padding: 25px;
    }
    .category-badge {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .alumni-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #17a2b8;
    }
    .avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #17a2b8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    .add-achievement-card {
        max-width: 600px;
        margin: 0 auto 40px auto;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        background: #fff;
    }
    .add-achievement-card .card-body {
        padding: 30px;
    }
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Alumni Achievements</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 pb-5">

    <!-- Add Achievement Form (only for logged in alumni) -->
    <?php if(isset($_SESSION['login_id'])): ?>
    <div class="add-achievement-card">
        <div class="card-body">
            <h5 class="text-center mb-4"><b>Share Your Achievement</b></h5>
            <div id="achievement_msg"></div>
            <form id="achievement_form">
                <div class="form-group">
                    <label class="control-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required placeholder="e.g. Got promoted to Senior Engineer">
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="control-label">Category <span class="text-danger">*</span></label>
                        <select class="custom-select" name="category" required>
                            <option value="">-- Select Category --</option>
                            <option value="Career">Career</option>
                            <option value="Education">Education</option>
                            <option value="Award">Award</option>
                            <option value="Certification">Certification</option>
                            <option value="Entrepreneurship">Entrepreneurship</option>
                            <option value="Research">Research</option>
                            <option value="Sports">Sports</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Achievement Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="achievement_date" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Description <span class="text-muted">(optional)</span></label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Describe your achievement... (optional)"></textarea>
                </div>
                <button class="btn btn-primary btn-block">Share Achievement</button>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center mb-4">
        <a href="#" onclick="uni_modal('Login','login.php')">Login</a> to share your achievements!
    </div>
    <?php endif; ?>

    <!-- All Achievements -->
    <h4 class="text-center text-white mb-3">All Achievements</h4>
    <hr class="divider mb-4">

    <div id="achievements_list">
    <?php
    $achievements = $conn->query("SELECT a.*, u.name, b.avatar 
        FROM achievements a 
        LEFT JOIN users u ON a.user_id = u.id 
        LEFT JOIN alumnus_bio b ON u.alumnus_id = b.id 
        ORDER BY a.date_created DESC");
    if($achievements->num_rows == 0):
    ?>
    <p class="text-center text-white">No achievements posted yet. Be the first to share!</p>
    <?php else: while($row = $achievements->fetch_assoc()):
        $colors = [
            'Career' => 'primary',
            'Education' => 'success',
            'Award' => 'warning',
            'Certification' => 'info',
            'Entrepreneurship' => 'danger',
            'Research' => 'secondary',
            'Sports' => 'dark',
            'Other' => 'light'
        ];
        $badge_color = isset($colors[$row['category']]) ? $colors[$row['category']] : 'primary';
    ?>
    <div class="achievement-card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <?php if(!empty($row['avatar'])): ?>
                    <img src="assets/uploads/<?php echo $row['avatar'] ?>" class="alumni-avatar mr-3" alt="">
                <?php else: ?>
                    <div class="avatar-placeholder mr-3"><?php echo strtoupper(substr($row['name'],0,1)) ?></div>
                <?php endif; ?>
                <div>
                    <b class="text-dark"><?php echo ucwords($row['name']) ?></b><br>
                    <small class="text-muted"><?php echo date("F d, Y", strtotime($row['achievement_date'])) ?></small>
                </div>
                <span class="badge badge-<?php echo $badge_color ?> category-badge ml-auto"><?php echo $row['category'] ?></span>
            </div>
            <h5 class="text-dark"><b><?php echo $row['title'] ?></b></h5>
            <p class="text-muted"><?php echo $row['description'] ?></p>
            <?php if(isset($_SESSION['login_id']) && $_SESSION['login_id'] == $row['user_id']): ?>
            <button class="btn btn-sm btn-danger delete_achievement" data-id="<?php echo $row['id'] ?>">Delete</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; endif; ?>
    </div>
</div>

<script>
$('#achievement_form').submit(function(e){
    e.preventDefault();
    $.ajax({
        url: 'admin/ajax.php?action=save_achievement',
        method: 'POST',
        data: $(this).serialize(),
        success: function(resp){
            if(resp == 1){
                $('#achievement_msg').html('<div class="alert alert-success">Achievement shared successfully!</div>');
                $('#achievement_form')[0].reset();
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                $('#achievement_msg').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
            }
        }
    });
});

$('.delete_achievement').click(function(){
    var id = $(this).attr('data-id');
    if(confirm('Are you sure you want to delete this achievement?')){
        $.ajax({
            url: 'admin/ajax.php?action=delete_achievement',
            method: 'POST',
            data: { id: id },
            success: function(resp){
                if(resp == 1){
                    location.reload();
                }
            }
        });
    }
});
</script>