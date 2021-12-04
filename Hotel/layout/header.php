<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" integrity="sha512-fzff82+8pzHnwA1mQ0dzz9/E0B+ZRizq08yZfya66INZBz86qKTCt9MLU0NCNIgaMJCgeyhujhasnFUsYMsi0Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-4">
                <a href="index.php" class="btn btn-<?=$page==='index'?'primary':'default'?> border"><small class="me-1"><i class="fas fa-table-cells"></i></small> Rooms</a>
                <a href="bookings.php" class="btn btn-<?=$page==='bookings'?'primary':'default'?> border"><small class="me-1"><i class="fas fa-calendar-check"></i></small> Bookings</a>
                <a href="room_types.php" class="btn btn-<?=$page==='room_types'?'primary':'default'?> border"><small class="me-1"><i class="fas fa-rectangle-list"></i></small> Room Types</a>
                <a href="non_trivial.php" class="btn btn-<?=$page==='non_trivial'?'primary':'default'?> border"><small class="me-1"><i class="fas fa-calendar"></i></small> Non Trivial</a>
            </div>
        </div>

