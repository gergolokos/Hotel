<?php

require 'config/db.php';

$page = 'room_types';

$types = [];

$stmt = $db->prepare("SELECT * FROM `room_types`");
if ($stmt->execute()) {
    $types = $stmt->fetchAll();
}

$errors = [];

if (isset($_GET) && !empty($_GET)) {
    
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $q = "DELETE FROM `room_types` WHERE `rtype_id` = '$id'";
        $stmt = $db->prepare($q);
        if ($stmt->execute()) {
            die(header('Location: room_types.php'));
        } else {
            $errors[] = "Unable to delete room type";
        }
    } 

}

if (isset($_POST) && !empty($_POST)) {

    $name = trim($_POST['name']);
    $cost = trim($_POST['cost']);
    
    if (empty($name)) {
        $errors[] = "Type name cannot be empty";
    } else {

        foreach ($types as $t) {
            if ($t['rtype_name'] == $name) {
                $errors[] = "Type name already exists, write any unique name";
                break;
            }
        }

    }

    if (empty($cost)) {
        $errors[] = "Cost cannot be empty";
    } else if (!is_numeric($cost)) {
        $errors[] = "Cost must be in numbers";
    }

    if (empty($errors)) {
        $q = "INSERT INTO `room_types` (`rtype_name`, `rtype_cost`) VALUE ('$name', '$cost')";
        $stmt = $db->prepare($q);
        if ($stmt->execute()) {
            die(header('Location: room_types.php'));
        } else {
            $errors[] = "Unable to add room type";
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
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-plus"></i> Add room type</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
            
                <form action="./room_types.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Type name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?=$_POST['name']??''?>" placeholder="e.g. Single, Double">
                    </div>
                    <div class="mb-4">
                        <label for="cost" class="form-label">Cost <small>(<i>per day</i>)</small></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="cost" name="cost" class="form-control" value="<?=$_POST['cost']??''?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h5 class="mb-0"><i class="fas fa-rectangle-list"></i> Room Types</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Cost</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($types)): ?>
                                <?php foreach ($types as $i => $type): ?>
                                <tr>
                                    <td><?=($i+1)?></td>
                                    <td><?=$type['rtype_name']?></td>
                                    <td>$<?=$type['rtype_cost']?>/day</td>
                                    <td>
                                        <a href="./room_types.php?delete=<?=$type['rtype_id']?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center"><small><i>no data in table</i></small></td>
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
