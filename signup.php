<?php 
include 'admin/db_connect.php'; 
?>
<style>
    .masthead{
        min-height: 23vh !important;
        height: 23vh !important;
    }
    .masthead:before{
        min-height: 23vh !important;
        height: 23vh !important;
    }
    .signup-card {
        max-width: 700px;
        margin: 40px auto;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        background: #fff;
    }
    .signup-card .card-body {
        padding: 35px 40px;
    }
    .signup-card h4 {
        color: #333;
        margin-bottom: 25px;
        font-weight: 600;
        text-align: center;
    }
    .signup-card .form-group label {
        color: #555;
        font-weight: 500;
    }
    .signup-card .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        margin-top: 10px;
    }
    .divider-line {
        border-top: 1px solid #eee;
        margin: 20px 0;
    }
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Create Account</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 pb-5">
    <div class="signup-card">
        <div class="card-body">
            <h4>Alumni Registration</h4>
            <form action="" id="create_account">

                <div class="form-group">
    <label class="control-label">Profile Photo <span class="text-muted">(optional)</span></label>
    <input type="file" class="form-control" name="img" accept="image/*" onchange="previewImage(this)">
    <div class="mt-2 text-center" id="preview_container" style="display:none;">
        <img id="preview_img" src="" alt="Preview" style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid #17a2b8;">
    </div>
    <small class="text-muted">This photo will appear on your alumni profile card.</small>
</div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Middle Name</label>
                        <input type="text" class="form-control" name="middlename">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="control-label">Gender <span class="text-danger">*</span></label>
                        <select class="custom-select" name="gender" required>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Batch Year <span class="text-danger">*</span></label>
                        <input type="input" class="form-control datepickerY" name="batch" required placeholder="Select Year">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Course Graduated <span class="text-danger">*</span></label>
                        <select class="custom-select select2" name="course_id" required>
                            <option></option>
                            <?php 
                            $course = $conn->query("SELECT * FROM courses order by course asc");
                            while($row=$course->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['course'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Currently Connected To</label>
                    <textarea name="connected_to" cols="30" rows="3" class="form-control" placeholder="e.g. Company name, institution, or organization"></textarea>
                </div>

                <div class="divider-line"></div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="control-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required placeholder="Enter your email">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="password" required placeholder="Create a password">
                    </div>
                </div>

                <div id="msg"></div>

                <button class="btn btn-primary">Create Account</button>

                <div class="text-center mt-3">
                    <small class="text-muted">Already have an account? <a href="#" onclick="uni_modal('Login','login.php')" style="color:#17a2b8;">Login here</a></small>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
   $('.datepickerY').datepicker({
        format: "yyyy", 
        viewMode: "years", 
        minViewMode: "years"
   })
   $('.select2').select2({
    placeholder:"Please Select Here",
    width:"100%"
   })

$('#create_account').submit(function(e){
    e.preventDefault()
    start_load()
    $.ajax({
        url:'admin/ajax.php?action=signup',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success:function(resp){
            if(resp == 1){
                location.replace('index.php')
            }else{
                $('#msg').html('<div class="alert alert-danger">Email already exists.</div>')
                end_load()
            }
        }
    })
})
function previewImage(input){
    if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
            $('#preview_img').attr('src', e.target.result);
            $('#preview_container').show();
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>