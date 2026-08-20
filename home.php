<?php 
include 'admin/db_connect.php'; 
?>
<style>
.hero-section {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    flex-direction: column;
    padding: 60px 20px;
}
.hero-section h1 {
    font-size: 3rem;
    font-weight: 700;
    color: #ffffff;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    margin-bottom: 20px;
}
.hero-section p {
    font-size: 1.2rem;
    color: #dddddd;
    max-width: 650px;
    margin: 0 auto 30px auto;
    line-height: 1.8;
}
.hero-buttons .btn {
    margin: 8px;
    padding: 12px 30px;
    font-size: 15px;
    border-radius: 30px;
    font-weight: 600;
}
.stats-section {
    background: #111;
    padding: 40px 0;
    text-align: center;
}
.stats-section .stat-box {
    padding: 20px;
}
.stats-section .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #17a2b8;
}
.stats-section .stat-label {
    color: #aaa;
    font-size: 14px;
    margin-top: 5px;
}
#portfolio .img-fluid{
    width: calc(100%);
    height: 30vh;
    z-index: -1;
    position: relative;
    padding: 1em;
}
.event-list{
    cursor: pointer;
    border: unset;
    flex-direction: inherit;
}
.banner{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 26vh;
    width: calc(40%);
}
.banner img{
    width: calc(100%);
    height: calc(100%);
    cursor: pointer;
}
.event-list .card-body {
    width: calc(60%);
}
.event-list .banner img {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    min-height: 50vh;
}
.banner{
   min-height: calc(100%);
}
.section-title {
    color: #ffffff;
    text-align: center;
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 10px;
}
</style>

<!-- Hero Section -->
<header class="masthead">
    <div class="container h-100">
        <div class="hero-section">
            <h1>Welcome to <?php echo $_SESSION['system']['name']; ?></h1>
            <p>Stay connected with your alma mater. Explore opportunities, reconnect with batchmates, and grow together as a community.</p>
            <div class="hero-buttons">
                <?php if(!isset($_SESSION['login_id'])): ?>
                    <a href="#" onclick="uni_modal('Login','login.php')" class="btn btn-primary">Login</a>
                    <a href="index.php?page=signup" class="btn btn-outline-light">Create Account</a>
                <?php else: ?>
                    <a href="index.php?page=alumni_list" class="btn btn-primary">View Alumni</a>
                    <a href="index.php?page=careers" class="btn btn-outline-light">Browse Jobs</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- Stats Section -->
<?php
$total_alumni = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE type != 1")->fetch_assoc()['cnt'];
$total_events = $conn->query("SELECT COUNT(*) as cnt FROM events")->fetch_assoc()['cnt'];
$total_jobs = $conn->query("SELECT COUNT(*) as cnt FROM careers WHERE user_id IN (SELECT id FROM users WHERE type = 1)")->fetch_assoc()['cnt'];
?>
<div class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 stat-box">
                <div class="stat-number"><?php echo $total_alumni; ?></div>
                <div class="stat-label">Registered Alumni</div>
            </div>
            <div class="col-md-4 stat-box">
                <div class="stat-number"><?php echo $total_events; ?></div>
                <div class="stat-label">Events Organized</div>
            </div>
            <div class="col-md-4 stat-box">
                <div class="stat-number"><?php echo $total_jobs; ?></div>
                <div class="stat-label">Job Opportunities</div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events -->
<div class="container mt-5 pb-5">
    <h4 class="section-title">Upcoming Events</h4>
    <hr class="divider">
    <?php
    $event = $conn->query("SELECT * FROM events where date_format(schedule,'%Y-%m-%d') >= '".date('Y-m-d')."' order by unix_timestamp(schedule) asc");
    $event_count = $event->num_rows;
    if($event_count == 0): ?>
        <p class="text-center text-muted">No upcoming events at the moment.</p>
    <?php else:
    while($row = $event->fetch_assoc()):
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
        $desc = strtr(html_entity_decode($row['content']), $trans);
        $desc = str_replace(array("<li>","</li>"), array("",","), $desc);
    ?>
    <div class="card event-list mb-4" data-id="<?php echo $row['id'] ?>">
        <div class='banner'>
            <?php if(!empty($row['banner'])): ?>
                <img src="admin/assets/uploads/<?php echo($row['banner']) ?>" alt="">
            <?php else: ?>
                <div style="background:#1a1a2e; width:100%; min-height:200px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa fa-calendar fa-3x" style="color:#17a2b8;"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="row align-items-center justify-content-center text-center h-100">
                <div class="">
                    <h3><b class="filter-txt"><?php echo ucwords($row['title']) ?></b></h3>
                    <div><small><p><b><i class="fa fa-calendar"></i> <?php echo date("F d, Y h:i A", strtotime($row['schedule'])) ?></b></p></small></div>
                    <hr>
                    <p class="filter-txt"><?php echo strip_tags($desc) ?></p>
                    <hr class="divider" style="max-width: calc(80%)">
                    <button class="btn btn-primary float-right read_more" data-id="<?php echo $row['id'] ?>">Read More</button>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; endif; ?>
</div>

<!-- Past Events -->
<div class="container mt-5 pb-5">
    <h4 class="section-title">Past Events</h4>
    <hr class="divider">
    <?php
    $past_event = $conn->query("SELECT * FROM events WHERE date_format(schedule,'%Y-%m-%d') < '".date('Y-m-d')."' ORDER BY unix_timestamp(schedule) DESC");
    $past_count = $past_event->num_rows;
    if($past_count == 0): ?>
        <p class="text-center text-muted">No past events yet.</p>
    <?php else: ?>
    <div class="row">
    <?php while($row = $past_event->fetch_assoc()):
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
        $desc = strtr(html_entity_decode($row['content']), $trans);
        $desc = str_replace(array("<li>","</li>"), array("",","), $desc);
    ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100" style="background:#1a1a2e; border:1px solid #333; border-radius:10px;">
            <?php if(!empty($row['banner'])): ?>
                <img src="admin/assets/uploads/<?php echo $row['banner'] ?>" class="card-img-top" style="height:180px; object-fit:cover; border-radius:10px 10px 0 0;" alt="">
            <?php else: ?>
                <div style="background:#111; height:180px; display:flex; align-items:center; justify-content:center; border-radius:10px 10px 0 0;">
                    <i class="fa fa-calendar-check fa-3x" style="color:#555;"></i>
                </div>
            <?php endif; ?>
            <div class="card-body text-center">
                <h5 style="color:#fff; font-weight:600;"><?php echo ucwords($row['title']) ?></h5>
                <small style="color:#17a2b8;"><i class="fa fa-calendar"></i> <?php echo date("F d, Y", strtotime($row['schedule'])) ?></small>
                <hr style="border-color:#333;">
                <p style="color:#aaa; font-size:13px;"><?php echo substr(strip_tags($desc), 0, 100) ?>...</p>
                <span class="badge" style="background:#17a2b8; color:#fff; padding:5px 12px; border-radius:20px;">
                    <i class="fa fa-check-circle"></i> Completed
                </span>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<script>
    // Active nav highlight
    $(document).ready(function(){
        var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home'; ?>';
        $('.nav-link').each(function(){
            var href = $(this).attr('href');
            if(href && href.includes('page='+page)){
                $(this).addClass('active').css('color','#17a2b8');
            }
        });
        // Always highlight Home when on home page
        if(page === 'home'){
            $('a[href="index.php?page=home"]').addClass('active').css('color','#17a2b8');
        }
    });

    $('.read_more').click(function(){
        location.href = "index.php?page=view_event&id="+$(this).attr('data-id');
    });
    $('.banner img').click(function(){
        viewer_modal($(this).attr('src'));
    });
    $('#filter').keyup(function(e){
        var filter = $(this).val();
        $('.card.event-list .filter-txt').each(function(){
            var txt = $(this).html();
            if((txt.toLowerCase()).includes((filter.toLowerCase()))){
                $(this).closest('.card').toggle(true);
            }else{
                $(this).closest('.card').toggle(false);
            }
        });
    });
</script>