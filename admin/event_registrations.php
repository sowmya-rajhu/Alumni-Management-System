<?php include 'db_connect.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Event Registrations</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Name</th>
                                <th>Alumni Name</th>
                                <th>Event Date</th>
                                <th>Registered On</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $registrations = $conn->query("SELECT ec.*, e.title, e.schedule, u.name 
                                FROM event_commits ec 
                                LEFT JOIN events e ON ec.event_id = e.id 
                                LEFT JOIN users u ON ec.user_id = u.id 
                                ORDER BY ec.id DESC");
                            $i = 1;
                            while($row = $registrations->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo ucwords($row['title']) ?></td>
                                <td><?php echo ucwords($row['name']) ?></td>
                                <td><?php echo date("M d, Y h:i A", strtotime($row['schedule'])) ?></td>
                                <td><?php echo date("M d, Y", strtotime($row['date_created'] ?? 'now')) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>