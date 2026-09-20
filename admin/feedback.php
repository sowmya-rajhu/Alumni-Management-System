<?php include 'db_connect.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Feedback List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Alumni Name</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Rating</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $feedbacks = $conn->query("SELECT f.*, u.name FROM feedback f LEFT JOIN users u ON f.user_id = u.id ORDER BY f.date_created DESC");
                            $i = 1;
                            while($row = $feedbacks->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo ucwords($row['name']) ?></td>
                                <td><?php echo $row['subject'] ?></td>
                                <td><?php echo $row['message'] ?></td>
                                <td>
                                    <?php for($s=1; $s<=5; $s++): ?>
                                        <span style="color:<?php echo $s <= $row['rating'] ? '#f5a623' : '#ccc' ?>">&#9733;</span>
                                    <?php endfor; ?>
                                </td>
                                <td><?php echo date("M d, Y", strtotime($row['date_created'])) ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete_feedback" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i> Delete</button>
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
$('.delete_feedback').click(function(){
    var id = $(this).attr('data-id');
    var row = $(this).closest('tr');
    if(confirm('Are you sure you want to delete this feedback?')){
        $.ajax({
            url: 'ajax.php?action=delete_feedback',
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