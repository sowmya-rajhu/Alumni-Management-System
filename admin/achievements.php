<?php include 'db_connect.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Alumni Achievements</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Alumni Name</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $achievements = $conn->query("SELECT a.*, u.name FROM achievements a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.date_created DESC");
                            $i = 1;
                            while($row = $achievements->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo ucwords($row['name']) ?></td>
                                <td><?php echo $row['title'] ?></td>
                                <td><span class="badge badge-primary"><?php echo $row['category'] ?></span></td>
                                <td><?php echo date("M d, Y", strtotime($row['achievement_date'])) ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete_achievement" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('.delete_achievement').click(function(){
    var id = $(this).attr('data-id');
    var row = $(this).closest('tr');
    if(confirm('Are you sure you want to delete this achievement?')){
        $.ajax({
            url: 'ajax.php?action=delete_achievement_admin',
            method: 'POST',
            data: { id: id },
            success: function(resp){
                if(resp == 1){
                    row.remove();
                }
            }
        });
    }
});
</script>