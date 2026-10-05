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
    .gallery-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        margin-bottom: 25px;
        background: #fff;
        transition: transform 0.2s;
    }
    .gallery-card:hover {
        transform: translateY(-3px);
    }
    .gallery-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .gallery-card .card-body {
        padding: 15px;
        text-align: center;
    }
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Gallery</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container mt-4 pb-5">
    <h4 class="text-center text-white mb-3">Photo Gallery</h4>
    <hr class="divider mb-4">
    <div class="row">
    <?php
    $fpath = 'admin/assets/uploads/gallery';
    $files = is_dir($fpath) ? scandir($fpath) : array();
    $img = array();
    foreach($files as $val){
        if(!in_array($val, array('.','..'))){
            $n = explode('_', $val);
            $img[$n[0]] = $val;
        }
    }
    $gallery = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
    if($gallery->num_rows == 0):
    ?>
    <div class="col-md-12 text-center text-white">
        <p>No photos uploaded yet.</p>
    </div>
    <?php else: while($row = $gallery->fetch_assoc()): 
        $imgSrc = isset($img[$row['id']]) && is_file($fpath.'/'.$img[$row['id']]) ? $fpath.'/'.$img[$row['id']] : '';
    ?>
    <?php if(!empty($imgSrc)): ?>
    <div class="col-md-4">
        <div class="gallery-card">
            <img src="<?php echo $imgSrc ?>" alt="<?php echo $row['about'] ?>">
            <?php if(!empty($row['about'])): ?>
            <div class="card-body">
                <p class="text-dark mb-0"><?php echo $row['about'] ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endwhile; endif; ?>
    </div>
</div>