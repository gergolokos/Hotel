<?php

require 'config/db.php';

$page = 'index';

$rooms = [];
$stmt = $db->prepare("SELECT * FROM `rooms` INNER JOIN `room_types` ON `room_rtype_id` = `rtype_id` WHERE `room_status` = 'Available'");
if ($stmt->execute()) {
    $rooms = $stmt->fetchAll();
}

if (isset($_POST) && !empty($_POST)) {

    $room = trim($_POST['room']);
    $name = trim($_POST['name']);
    $checkin = trim($_POST['checkin']);
    $days = trim($_POST['days']);
    
    
    if (empty($room)) {
        $errors[] = "Select room";
    } else if (!is_numeric($room)) {
        $errors[] = "Room value must be numeric";
    } else {
        $selected_room = [];
        foreach ($rooms as $r) {
            if ($r['room_id'] == $room) {
                $selected_room = $r;
                break;
            }
        }

        if (empty($selected_room)) {
            $errors[] = "Selected room is not available";
        }
    }

    if (empty($name)) {
        $errors[] = "Guest name cannot be empty";
    } 
    
    if (empty($checkin)) {
        $errors[] = "Check-in date cannot be empty";
    } else {
        if (!is_valid_date($checkin)) {
            $errors[] = "Check-in date is in invalid format";
        }
    }

    if (empty($days)) {
        $errors[] = "Enter number of days";
    } else if (!is_numeric($days)) {
        $errors[] = "Number of days must be numeric";
    }

    if (empty($errors)) {

        // picture upload
        $picture = NULL;
        if (!empty($_FILES) && !empty($_FILES['picture'])) {
            $new_file_name = time().'.'.strtolower(pathinfo($_FILES["picture"]["name"],PATHINFO_EXTENSION));
            $target_file = 'uploads/'.$new_file_name;

            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                $picture = $new_file_name;
            }
        }

        $checkout = add_days_to_date($checkin, $days);

        $checkin = normal_to_db_date($checkin);
        $checkout = normal_to_db_date($checkout);
        $created = current_date();

        $amount = ((float)$selected_room['rtype_cost']) * ((int)$days);

        if (empty($errors)) {

            $q = "INSERT INTO `guests` (`guest_name`, `guest_picture`) VALUE ('$name', '$picture')";
            $stmt = $db->prepare($q);
            if ($stmt->execute()) {

                $guest_id = $db->lastInsertId();

                $q = "INSERT INTO `bookings` (`booking_room_id`, `booking_guest_id`, `booking_amount`, `booking_created`, `booking_checkin`, `booking_checkout`) VALUE ('$room', '$guest_id', '$amount', '$created', '$checkin', '$checkout')";
                $stmt = $db->prepare($q);
                if ($stmt->execute()) {
                
                    $booking_id = $db->lastInsertId();

                    $q = "UPDATE `rooms` SET `room_status` = 'Occupied', `room_booking_id` = '$booking_id' WHERE `room_id` = '$room'";
                    $stmt = $db->prepare($q);
                    if ($stmt->execute()) {
                        die(header("Location: bookings.php?booking=$booking_id&booked=true"));
                    } else {
                        $errors[] = "Unable to add booking";
                    }

                } else {
                    $errors[] = "Unable to add booking";
                }
                
            }


        }

    }

}


if (isset($_GET['room'])) {
    $_POST['room'] = $_GET['room'];
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
    
    <div class="col-md-4 offset-md-4">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-plus"></i> Add booking</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
            
                <form action="./add_booking.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="room" class="form-label">Room</label>
                        <select name="room" id="room" class="form-control" required>
                            <option value="">select type</option>
                            <?php foreach ($rooms as $room): ?>
                            <option value="<?=$room['room_id']?>" <?=isset($_POST['room'])?($_POST['room']==$room['room_id']?'selected':''):''?>><?=$room['room_name']?> ($<?=$room['rtype_cost']?>/day)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Guest name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?=$_POST['name']??''?>" placeholder="e.g. John Snow" required>
                    </div>
                    <div class="mb-3">
                        <label for="picture" class="form-label">Guest picture</label>
                        <input type="file" name="picture" id="picture">
                    </div>

                    
                    <div class="mb-3">
                        <label for="checkin" class="form-label">Check-in date</label>
                        <input type="date" class="form-control" id="checkin" name="checkin" value="<?=$_POST['checkin']??''?>" placeholder="Date of checkin" required>
                    </div>
                    <div class="mb-3">
                        <label for="days" class="form-label">No. of days of stay</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" name="days" id="days" value="<?=$_POST['days']??''?>" required>
                            <span class="input-group-text">days</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Booking</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

<?php require 'layout/footer.php'; ?>
