<?php include 'admin/db_connect.php'; ?>
<style>
.event-list{
    cursor: pointer;
    border: unset;
    flex-direction: inherit;
    display: flex;
    height: 300px;
    margin-bottom: 20px;
}
.event-list .banner {
    width: 40%;
    height: 300px;
    overflow: hidden;
}
.event-list .banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}
.event-list .card-body {
    width: 60%;
    overflow: hidden;
}
.event-list .card-body p {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
</style>
<div class="container mt-5 pt-5">
    <h4 class="text-center text-white">Past Events</h4>
    <hr class="divider">
    <?php
    $event = $conn->query("SELECT * FROM events WHERE schedule < NOW() ORDER BY schedule DESC");
    $count = $event->num_rows;
    if($count <= 0):
    ?>
    <p class="text-center text-white">No past events found.</p>
    <?php else: while($row = $event->fetch_assoc()):
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        unset($trans['"'], $trans['<'], $trans['>']);
        $desc = strtr(html_entity_decode($row['content']), $trans);
        $desc = str_replace(array("<li>","</li>"), array("",","), $desc);
    ?>
    <div class="card event-list">
        <div class='banner'>
            <?php if(!empty($row['banner'])): ?>
                <img src="admin/assets/uploads/<?php echo $row['banner'] ?>" alt="">
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="row align-items-center justify-content-center text-center h-100">
                <div class="w-100">
                    <h3><b><?php echo ucwords($row['title']) ?></b></h3>
                    <div><small><p><b><i class="fa fa-calendar"></i> <?php echo date("F d, Y h:i A", strtotime($row['schedule'])) ?></b></p></small></div>
                    <hr>
                    <p><?php echo strip_tags($desc) ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; endif; ?>
</div>