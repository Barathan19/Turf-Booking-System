<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> - Games</title>


</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>



<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">Our Games</h2>
  <h2 class="h-line bg-dark"></h2>
  <p class="text-center mt-3">
   Our state-of-the-art indoor turf provides the perfect surface for fast-paced games, while our high-quality equipment ensures that every game is played at its best. Whether you're looking for a fun game with friends or a competitive match, our games section has something for everyone. Book your turf today and experience the thrill of indoor sports like never before.
  </p>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
     <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
       <div class="container-fluid flex-lg-column align-items-stretch">
         <h4 class="mt-2">Filters</h4>
         <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
          <div class="border bg-light p-3 rounded mb-3">
            <h5 class="mb-3" style="font-size: 18px;">Check Availability</h5>
            <label class="form-label">Check-in</label>
            <input type="time" id="check-in-time" class="form-control shadow-none mb-3">
            <label class="form-label">Check-out</label>
            <input type="time" id="check-out-time" class="form-control shadow-none mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control shadow-none mb-3">
          </div>
          <div class="border bg-light p-3 rounded mb-3">
            <h5 class="mb-3" style="font-size: 18px;">Games</h5>
            <div class="mb-2">
             <input type="checkbox" id="f1" class="form-check-input shadow-none me-1">
             <label class="form-check-label" for="f1"> Cricket</label>
            </div>
            <div class="mb-2">
             <input type="checkbox" id="f2" class="form-check-input shadow-none me-1">
             <label class="form-check-label" for="f2"> Football</label>
            </div>
            <div class="mb-2">
             <input type="checkbox" id="f3" class="form-check-input shadow-none me-1">
             <label class="form-check-label" for="f3"> Badminton</label>
            </div>
          </div>
         </div>
       </div>
     </nav>
    </div>
    <div class="col-lg-9 col-md-12 px-4">

     <?php 
     
     $game_res = select("SELECT * FROM `games` WHERE `status`=? AND `removed`=?",[1,0],'ii');
     while($game_data = mysqli_fetch_assoc($game_res)){
      //get features of game

      $fea_q =mysqli_query($con,"SELECT f.name FROM `features` f INNER JOIN `games_features` gfea ON f.id = gfea.feature_id WHERE gfea.game_id = '$game_data[id]'");
      $features_data="";
      while($fea_row = mysqli_fetch_assoc($fea_q)){
         $features_data .="<span class='badge bg-light text-dark mb-3 text-wrap me-1 mb-1'> 
         $fea_row[name]
         </span>";
      }
          //get thumbnail of game

     $game_thumb =ABOUT_IMG_PATH."thumbnail.jpg";
     $thumb_q = mysqli_query($con,"SELECT * FROM `game_images` WHERE `game_id`='$game_data[id]' AND `thumb`='1'");
     if(mysqli_num_rows($thumb_q)>0){
      $thumb_res = mysqli_fetch_assoc($thumb_q);
      $game_thumb=ABOUT_IMG_PATH.$thumb_res['image'];
     }



     $book_btn="";
     if(!$settings_r['shutdown']){
      $book_btn="<a href='#' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</a>";
     }


     //print game card

     echo <<<data
       <div class="card mb-4 border-0 shadow-none">
         <div class="row g-0 p-3 align-items-center">
           <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
             <img src="$game_thumb" class="img-fluid rounded">
           </div>
           <div class="col-md-5 px-lg-3 px-md-3 px-0">
             <h5 class="mb-1">$game_data[name]</h5>
             <br><br>
             <div class="features mb-3">
               <h6 class="mb-1">Equipments Provided</h6>
               $features_data
             </div>
           </div>
             <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
             <h6 class="mb-4">₹$game_data[price] per hour</h6>
             $book_btn
             <a href="game_details.php?id=$game_data[id]" class="btn btn-sm w-100 btn-outline-dark shadow-none mb-2">More Details</a>
           </div>
         </div>
       data;
     }

     ?>
   </div>
  </div>
</div>
<?php require('inc/footer.php'); ?>

<script>
  const timeInputs = document.querySelectorAll('input[type="time"]');

  timeInputs.forEach(timeInput => {
  timeInput.addEventListener('change', () => {
    const selectedTime = timeInput.value;
    const [hours, minutes] = selectedTime.split(':');
    const roundedMinutes = Math.floor(parseInt(minutes) / 30) * 30;
    const formattedTime = hours + ':' + roundedMinutes.toString().padStart(2, '0');
    timeInput.value = formattedTime;

    const checkInTime = document.querySelector('#check-in-time').value;
    const checkOutTime = document.querySelector('#check-out-time').value;

    if (timeInput.id === 'check-out-time') {
      if (checkInTime && checkOutTime) {
        const checkInDateTime = new Date(`2000-01-01T${checkInTime}`);
        const checkOutDateTime = new Date(`2000-01-01T${checkOutTime}`);
        if (checkOutDateTime < checkInDateTime) {
          alert('Check-out time cannot be earlier than check-in time.');
          timeInput.value = checkInTime;
        }
      }
    }
  });
});

</script>
</body>
</html>
