<?php

require 'config/db.php';

$page = 'non_trivial';

$non_trivial = [];
$stmt = $db->prepare("SELECT `rtype_name` AS 'Name', COUNT(rtype_id) AS 'Piece' FROM `bookings` INNER JOIN `rooms` ON `booking_room_id` = `room_id` INNER JOIN `room_types` ON `room_rtype_id` = `rtype_id` GROUP BY `rtype_name`");
if ($stmt->execute()) {
    $non_trivial = $stmt->fetchAll();
}
?>
<?php require 'layout/header.php'; ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Piece</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($non_trivial)): ?>
            <?php foreach ($non_trivial as $i => $adat): ?>
            <tr>
                    <td><?=$adat['Name']?></td>
                    <td><?=$adat['Piece']?></td>
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
<?php require 'layout/footer.php'; ?>
