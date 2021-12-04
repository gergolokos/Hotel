<?php

require 'config/db.php';

$page = 'index';

$types = [];
$stmt = $db->prepare("SELECT * FROM `room_types`");
if ($stmt->execute()) {
    $types = $stmt->fetchAll();
}


if (isset($_POST) && !empty($_POST)) {

    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    
    if (empty($name)) {
        $errors[] = "Type name cannot be empty";
    } else {

        $q = "SELECT * FROM `rooms` WHERE `room_name` = '$name'";
        $stmt = $db->prepare($q);
        if ($stmt->execute()) {
            
            $rooms = $stmt->fetchAll();
            if (!empty($rooms)) {
                $errors[] = "Type name already exists, write any unique name";
            }

        } else {
            $errors[] = "Unable execute room query";
        }

    }

    if (empty($type)) {
        $errors[] = "Select type of room";
    } else if (!is_numeric($type)) {
        $errors[] = "Type of room must be numeric";
    }

    if (empty($errors)) {

        $q = "INSERT INTO `rooms` (`room_name`, `room_rtype_id`) VALUE ('$name', '$type')";
        $stmt = $db->prepare($q);
        if ($stmt->execute()) {
            die(header('Location: index.php'));
        } else {
            $errors[] = "Unable to add room";
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
    
    <div class="col-md-4 offset-md-4">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-plus"></i> Add room</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
            
                <form action="./add_room.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Room name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?=$_POST['name']??''?>" placeholder="e.g. Room-00" required>
                    </div>
                    <div class="mb-4">
                        <label for="type" class="form-label">Room Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="">select type</option>
                            <?php foreach ($types as $type): ?>
                            <option value="<?=$type['rtype_id']?>"><?=$type['rtype_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>

<?php require 'layout/footer.php'; ?>
