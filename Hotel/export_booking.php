<?php

require 'config/db.php';

$non_trivial = [];
$stmt = $db->prepare("SELECT * FROM `bookings` INNER JOIN `rooms` ON `booking_room_id` = `room_id` INNER JOIN `guests` ON `booking_guest_id` = `guest_id` ORDER BY `booking_id` DESC");
if ($stmt->execute()) {
    $non_trivial = $stmt->fetchAll();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="booking.csv"');
$data = array(
        'aaa,bbb,ccc,dddd',
        '123,456,789',
        '"aaa","bbb"'
);

$data = [];

// columns
$data[] = [
    "ID", "Room", "Guest", "Amount", "Check-in", "Check-out", "Creation Date"
];

// rows
foreach ($non_trivial as $booking) {
    $data[] = [
        $booking['booking_id'],
        $booking['room_name'],
        $booking['guest_name'],
        $booking['booking_amount'],
        $booking['booking_checkin'],
        $booking['booking_checkout'],
        $booking['booking_created']
    ];
}

$fp = fopen('php://output', 'wb');
foreach ( $data as $line ) {
    fputcsv($fp, $line, ',');
}
fclose($fp);

exit();
