<?php

require 'config/db.php';

$page = 'bookings';

$bookings = [];

$stmt = $db->prepare("SELECT * FROM `bookings` INNER JOIN `rooms` ON `booking_room_id` = `room_id` INNER JOIN `guests` ON `booking_guest_id` = `guest_id` ORDER BY `booking_id` DESC");
if ($stmt->execute()) {
    $bookings = $stmt->fetchAll();
}


$errors = [];


if (isset($_GET) && !empty($_GET)) {

    if (isset($_GET['delete']) && !empty($_GET['delete'])) {

        $delete_id = $_GET['delete'];
        
        $booking_details = "";
        // checking if booking id exists
        foreach ($bookings as $booking) {
            if ($booking['booking_id'] == $delete_id) {
                $booking_details = $booking;
                break;
            }
        }
        
        if (!empty($booking_details)) {

            // if room is attached to booking, setting status to available
            if ($booking_details['room_status'] == 'Occupied' && $booking_details['room_booking_id'] == $booking_details['booking_id']) {
                $q = "UPDATE `rooms` SET `room_status` = 'Available' WHERE `room_id` = '".$booking_details['room_id']."'";
                $stmt = $db->prepare($q);
                if (!$stmt->execute()) {
                    $errors[] = "Unable to remove availability status from booking's room";
                }
            }

            if (empty($errors)) {

                // removing booking
                $q = "DELETE FROM `bookings` WHERE `booking_id` = '".$booking_details['booking_id']."'";
                $stmt = $db->prepare($q);
                if (!$stmt->execute()) {
                    $errors[] = "Unable to remove booking";
                } else {
                    die(header('Location: bookings.php'));
                }
            }

        } else {
            $errors[] = "Unable to find booking id";
        }


    }


    if (isset($_GET['booking']) && !empty($_GET['booking'])) {

        $booking_id = $_GET['booking'];

        $booking_details = "";
        // checking if booking id exists
        foreach ($bookings as $booking) {
            if ($booking['booking_id'] == $booking_id) {
                $booking_details = $booking;
                break;
            }
        }

        if (!empty($booking_details)) {
            $bookings = [$booking_details];
        }

        if (isset($_GET['booked'])) {
            $success = '<div class="col">Booking successful. See booking details below.</div><div class="col-auto"><a href="./bookings.php" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> back to bookings</a></div>';
        } else {
            $success = '<div class="col">See booking details below.</div><div class="col-auto"><a href="./bookings.php" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> back to bookings</a></div>';
        }

    }



}


?>

<?php require 'layout/header.php'; ?>

<div class="row">

    <?php if (!empty($errors)): ?>
        <div class="col-md-12">
            <div class="alert alert-danger">
                <?php foreach ($errors as $error):?>
                <li><?=$error?></li>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Bookings</h5>
                    </div>
                    <div class="col-auto">
                        <a href="./add_booking.php" class="btn btn-sm btn-danger"><i class="fas fa-calendar-plus me-1"></i> Add Booking</a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="mb-4">
                    <a href="export_booking.php" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Data</a>
                </div>

                <?php if (!empty($success)): ?>
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <div class="row">
                                <?=$success?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Room</th>
                                <th>Guest</th>
                                <th>Amount</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Creation Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $i => $booking): ?>
                                <tr <?=$booking['booking_id'] === $booking['room_booking_id']?'class="table-success"':''?>>
                                    <td><?=$booking['booking_id']?></td>
                                    <td><?=$booking['room_name']?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($booking['guest_picture'])): ?>
                                                <img src="../uploads/<?=$booking['guest_picture']?>" style="width: 20px; height: 20px; " class="rounded">
                                            <?php endif; ?>
                                            <div>
                                                <?=$booking['guest_name']?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?=$booking['booking_amount']?></td>
                                    <td><?=normal_date($booking['booking_checkin'], 'M d, Y')?></td>
                                    <td><?=normal_date($booking['booking_checkout'], 'M d, Y')?></td>
                                    <td><?=normal_date($booking['booking_created'])?></td>
                                    <td>
                                        <a href="./bookings.php?delete=<?=$booking['booking_id']?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center"><small><i>no data in table</i></small></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require 'layout/footer.php'; ?>
