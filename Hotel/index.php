<?php

require 'config/db.php';

$page = 'index';

$rooms = [];
$q = "SELECT * FROM `rooms` JOIN `room_types` ON `room_rtype_id` = `rtype_id` LEFT JOIN `bookings` ON `room_booking_id` = `booking_id` LEFT JOIN `guests` ON `booking_guest_id` = `guest_id`";
$stmt = $db->prepare($q);
if ($stmt->execute()) {
    $rooms = $stmt->fetchAll();
}

if (isset($_GET) && !empty($_GET)) {
    
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $q = "DELETE FROM `rooms` WHERE `room_id` = '$id'";
        $stmt = $db->prepare($q);
        if ($stmt->execute()) {
            die(header('Location: index.php'));
        } else {
            $errors[] = "Unable to delete room";
        }
    } else if (isset($_GET['unbook'])) {

        $id = $_GET['unbook'];
        $q = "UPDATE `rooms` SET `room_status` = 'Available', `room_booking_id` = NULL WHERE `room_id` = '$id'";
        $stmt = $db->prepare($q);
        if ($stmt->execute()) {
            die(header('Location: index.php'));
        } else {
            $errors[] = "Unable to unbook the room";
        }

    }

}


?>

<?php require 'layout/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-table-cells"></i> Rooms</h5>
                    </div>
                    <div class="col-auto">
                        <a href="./add_booking.php" class="btn btn-sm btn-danger"><i class="fas fa-calendar-plus me-1"></i> Add Booking</a>
                        <a href="./add_room.php" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i> Add Room</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error):?>
                                <li><?=$error?></li>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php foreach ($rooms as $room): ?>
                    <div class="col-md-4 col-lg-3 my-2">
                        <div class="border text-center p-2">
                            <div class="badge bg-<?=$room['room_status']=='Available'?'success':'danger'?>"><?=$room['room_status']?></div>
                            <div>
                                <h4><?=$room['room_name']?></h4>

                                <?php if ($room['room_status'] == 'Available'): ?>
                                    <table class="table table-bordered" style="font-size: 11px;">
                                        <tbody>
                                            <tr>
                                                <th>Type</th>
                                                <td><?=$room['rtype_name']?></td>
                                            </tr>
                                            <tr>
                                                <th>Cost</th>
                                                <td>$<?=$room['rtype_cost']?>/day</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <table class="table table-bordered" style="font-size: 11px;">
                                        <tbody>
                                            <tr>
                                                <th>Guest</th>
                                                <td><?=$room['guest_name']?></td>
                                            </tr>
                                            <tr>
                                                <th>Booking</th>
                                                <td><?=normal_date($room['booking_checkin'], 'M d, Y')?> - <?=normal_date($room['booking_checkout'], 'M d, Y')?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <div>
                                <?php if ($room['room_status'] == 'Available'): ?>
                                    <a href="./add_booking.php?room=<?=$room['room_id']?>" class="btn btn-sm btn-success">Book</a>
                                <?php else: ?>
                                    <a href="./bookings.php?booking=<?=$room['booking_id']?>" class="btn btn-sm btn-success">View Booking</a>
                                    <a href="./index.php?unbook=<?=$room['room_id']?>" class="btn btn-sm btn-warning">Unbook</a>
                                <?php endif; ?>
                                <a href="./index.php?delete=<?=$room['room_id']?>" class="btn btn-sm btn-danger">delete room</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require 'layout/footer.php'; ?>
