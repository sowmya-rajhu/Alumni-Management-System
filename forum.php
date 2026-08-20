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
.forum-list {
    border-radius: 10px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}
.forum-list:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
span.highlight {
    background: yellow;
}
</style>

<header class="masthead">
    <div class="container-fluid h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end mb-4 page-title">
                <h3 class="text-white">Forum</h3>
                <hr class="divider my-4" />
                <?php if(isset($_SESSION['login_id'])): ?>
                <button class="btn btn-primary col-sm-4" type="button" id="new_forum">
                    <i class="fa fa-plus"></i> Create New Topic
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<div class="container mt-3 pb-5">
    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="filter" placeholder="Filter topics...">
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-block" id="search">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Forum Topics -->
    <?php
    $forums = $conn->query("SELECT f.*, u.name FROM forum_topics f INNER JOIN users u ON u.id = f.user_id ORDER BY f.id DESC");
    $forum_count = $forums->num_rows;
    if($forum_count == 0): ?>
        <div class="text-center text-muted mt-5">
            <i class="fa fa-comments fa-3x mb-3"></i>
            <p>No forum topics yet. Be the first to start a discussion!</p>
        </div>
    <?php else:
    while($row = $forums->fetch_assoc()):
        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
        $desc = strtr(html_entity_decode($row['description']), $trans);
        $desc = str_replace(array("<li>","</li>"), array("",","), $desc);
        $count_comments = $conn->query("SELECT * FROM forum_comments WHERE topic_id = ".$row['id'])->num_rows;
    ?>
    <div class="card forum-list" data-id="<?php echo $row['id'] ?>">
        <div class="card-body">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-12">
                    <?php if(isset($_SESSION['login_id']) && $_SESSION['login_id'] == $row['user_id']): ?>
                    <div class="dropdown float-right mr-4">
                        <a class="text-dark" href="javascript:void(0)" data-toggle="dropdown">
                            <span class="fa fa-ellipsis-v"></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item edit_forum" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Edit</a>
                            <a class="dropdown-item delete_forum" data-id="<?php echo $row['id'] ?>" href="javascript:void(0)">Delete</a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <h4><b class="filter-txt"><?php echo ucwords($row['title']) ?></b></h4>
                    <hr>
                    <p class="filter-txt text-muted"><?php echo strip_tags(substr($desc, 0, 150)) ?>...</p>
                    <hr class="divider" style="max-width:80%">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-info px-3 py-2 mr-2">
                                <i class="fa fa-user"></i> Topic by: <span class="filter-txt"><?php echo $row['name'] ?></span>
                            </span>
                            <span class="badge badge-secondary px-3 py-2">
                                <i class="fa fa-comments"></i> <?php echo $count_comments ?> Comments
                            </span>
                        </div>
                        <button class="btn btn-primary btn-sm view_topic" data-id="<?php echo $row['id'] ?>">
                            View Topic
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; endif; ?>
</div>

<script>
    $('#new_forum').click(function(){
        uni_modal("New Topic", "manage_forum.php", 'mid-large')
    })
    $('.view_topic').click(function(){
        location.replace('index.php?page=view_forum&id='+$(this).attr('data-id'))
    })
    $('.edit_forum').click(function(){
        uni_modal("Edit Topic", "manage_forum.php?id="+$(this).attr('data-id'), 'mid-large')
    })
    $('.delete_forum').click(function(){
        _conf("Are you sure to delete this Topic?", "delete_forum", [$(this).attr('data-id')], 'mid-large')
    })
    function delete_forum($id){
        start_load()
        $.ajax({
            url:'admin/ajax.php?action=delete_forum',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp==1){
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }
    $('#filter').keypress(function(e){
        if(e.which == 13)
            $('#search').trigger('click')
    })
    $('#search').click(function(){
        var txt = $('#filter').val()
        start_load()
        if(txt == ''){
            $('.forum-list').show()
            end_load()
            return false;
        }
        $('.forum-list').each(function(){
            var content = "";
            $(this).find(".filter-txt").each(function(){
                content += ' ' + $(this).text()
            })
            if((content.toLowerCase()).includes(txt.toLowerCase())){
                $(this).toggle(true)
            }else{
                $(this).toggle(false)
            }
        })
        end_load()
    })
</script>