<?php 
include 'admin/db_connect.php'; 
?>
<style>
.masthead {
    min-height: 23vh !important;
    height: 23vh !important;
}
.masthead:before {
    min-height: 23vh !important;
    height: 23vh !important;
}
.alumni-card {
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}
.alumni-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transform: translateY(-3px);
}
.alumni-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #17a2b8;
    margin: 0 auto 10px auto;
    display: block;
}
.alumni-avatar-placeholder {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #17a2b8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px auto;
    border: 3px solid #17a2b8;
}
.alumni-name {
    font-size: 16px;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}
.alumni-divider {
    border-color: #17a2b8;
    width: 50px;
    border-width: 2px;
    margin: 8px auto;
}
.alumni-detail {
    font-size: 13px;
    color: #555;
    margin-bottom: 5px;
    text-align: left;
}
.alumni-detail i {
    color: #17a2b8;
    width: 16px;
    margin-right: 5px;
}
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Alumnus/Alumnae List</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container mt-3 pb-5">
    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="filter" placeholder="Search by name...">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <select class="custom-select" id="filter_course">
                        <option value="">-- All Courses --</option>
                        <?php
                        $courses = $conn->query("SELECT * FROM courses ORDER BY course ASC");
                        while($c = $courses->fetch_assoc()):
                        ?>
                        <option value="<?php echo $c['course'] ?>"><?php echo $c['course'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select class="custom-select" id="filter_batch">
                        <option value="">-- All Batches --</option>
                        <?php
                        $batches = $conn->query("SELECT DISTINCT batch FROM alumnus_bio WHERE status = 1 ORDER BY batch DESC");
                        while($b = $batches->fetch_assoc()):
                        ?>
                        <option value="<?php echo $b['batch'] ?>"><?php echo $b['batch'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-primary btn-block" id="search">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alumni Cards -->
    <div class="row">
        <?php
        $fpath = 'admin/assets/uploads';
        $alumni = $conn->query("SELECT a.*, c.course, CONCAT(a.lastname,', ',a.firstname,' ',IFNULL(a.middlename,'')) as fullname FROM alumnus_bio a INNER JOIN courses c ON c.id = a.course_id WHERE a.status = 1 ORDER BY a.lastname ASC");
        
        if($alumni->num_rows == 0): ?>
            <div class="col-md-12 text-center text-muted mt-5">
                <i class="fa fa-users fa-3x mb-3"></i>
                <p>No alumni registered yet.</p>
            </div>
        <?php else:
        while($row = $alumni->fetch_assoc()): ?>
        <div class="col-md-4 item">
            <div class="card alumni-card">
                <div class="card-body text-center p-4">
                    <!-- Profile Picture -->
                    <?php if(!empty($row['avatar'])): ?>
                        <img src="<?php echo $fpath.'/'.$row['avatar'] ?>" alt="" class="alumni-avatar">
                    <?php else: ?>
                        <div class="alumni-avatar-placeholder">
                            <i class="fa fa-user fa-2x text-white"></i>
                        </div>
                    <?php endif; ?>

                    <!-- Name -->
                    <p class="alumni-name filter-txt"><?php echo ucwords($row['fullname']) ?></p>
                    <hr class="alumni-divider">

                    <!-- Details -->
                    <p class="alumni-detail filter-txt">
                        <i class="fa fa-envelope"></i><?php echo $row['email'] ?>
                    </p>
                    <p class="alumni-detail filter-txt">
                        <i class="fa fa-graduation-cap"></i><?php echo $row['course'] ?>
                    </p>
                    <p class="alumni-detail filter-txt">
                        <i class="fa fa-calendar"></i>Batch: <?php echo $row['batch'] ?>
                    </p>
                    <?php if(!empty($row['connected_to'])): ?>
                    <p class="alumni-detail filter-txt">
                        <i class="fa fa-briefcase"></i><?php echo $row['connected_to'] ?>
                    </p>
                    <?php endif; ?>

                    <!-- Connect Button -->
                    <a href="mailto:<?php echo $row['email'] ?>" class="btn btn-outline-primary btn-sm btn-block mt-3">
                        <i class="fa fa-envelope mr-1"></i> Connect
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; endif; ?>
    </div>
</div>

<script>
    $('#filter').keypress(function(e){
        if(e.which == 13)
            $('#search').trigger('click')
    })
    $('#filter_course, #filter_batch').change(function(){
        $('#search').trigger('click')
    })
    $('#search').click(function(){
        var txt = $('#filter').val().toLowerCase()
        var course = $('#filter_course').val().toLowerCase()
        var batch = $('#filter_batch').val().toLowerCase()
        
        start_load()
        
        $('.item').each(function(){
            var content = "";
            $(this).find(".filter-txt").each(function(){
                content += ' ' + $(this).text().toLowerCase()
            })
            
            var show = true;
            
            if(txt != '' && !content.includes(txt))
                show = false;
            if(course != '' && !content.includes(course))
                show = false;
            if(batch != '' && !content.includes(batch))
                show = false;
            
            $(this).toggle(show)
        })
        end_load()
    })
</script>