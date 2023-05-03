<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> - Game Details</title>
</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>
<?php 
if(!isset($_GET['id'])){
  redirect('games.php');
}
$data = filteration($_GET);

$game_res = select("SELECT * FROM `games` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

if(mysqli_num_rows($game_res)==0){
  redirect('games.php');
}

$game_data= mysqli_fetch_assoc($game_res);

?>




<div class="container">
  <div class="row">
    <div class="col-12 my-5 px-4">
      <h2 class="fw-bold"><?php echo $game_data['name'] ?></h2>
     <div style="font-size: 14px;">
       <a href="index.php" class="text-secondary text-decoration-none">Home</a>
       <span class="text-secondary"> > </span>
       <a href="games.php" class="text-secondary text-decoration-none">Games</a>
     </div>
   </div>
   <div class="col-lg-7 col-md-12 px-4">
     <div id="gameCarousel" class="carousel slide" data-bs-ride="carousel">
       <div class="carousel-inner">
         <?php 
            $game_img =ABOUT_IMG_PATH."thumbnail.jpg";
            $img_q = mysqli_query($con,"SELECT * FROM `game_images` WHERE `game_id`='$game_data[id]'");
            if(mysqli_num_rows($img_q)>0){
             $active_class='active';
             while($img_res = mysqli_fetch_assoc($img_q)){
              echo"
              <div class='carousel-item $active_class'>
                <img src='".ABOUT_IMG_PATH.$img_res['image']."' class='d-block w-100 rounded'>
              </div>             
              ";
              $active_class='';
             }
             
            }
            else{
              echo
               "<div class='carousel-item active'>
                 <img src='$game_img' class='d-block w-100'>
               </div>"
              ;
            }
        
        
         ?>
         <button class="carousel-control-prev" type="button" data-bs-target="#gameCarousel" data-bs-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="visually-hidden">Previous</span>
         </button>
         <button class="carousel-control-next" type="button" data-bs-target="#gameCarousel" data-bs-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="visually-hidden">Next</span>
         </button>
       </div> 
     </div>
   </div>

   <div class="col-lg-5 col-md-12 px-4">
     <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <?php
          echo<<<price
            <h4>₹$game_data[price] per hour</h4>
          price;
          $fea_q =mysqli_query($con,"SELECT f.name FROM `features` f INNER JOIN `games_features` gfea ON f.id = gfea.feature_id WHERE gfea.game_id = '$game_data[id]'");
            $features_data="";
            while($fea_row = mysqli_fetch_assoc($fea_q)){
               $features_data .="<span class='badge bg-light text-dark mb-3 text-wrap me-1 mb-1'> 
               $fea_row[name]
               </span>";
            }

            echo<<<features
              <div class="mb-3">
               <h6 class="mb-1">Equipments Provided</h6>
               $features_data
             </div>
           features;
           $book_btn="";
           if(!$settings_r['shutdown']){
            $login=0;
            if(isset($_SESSION['login']) && $_SESSION['login']==true){
              $login=1;
            }
            echo<<<book
              <button onclick='checkLoginToBook($login,$game_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>
            book;
           }

          ?>
        </div>
      </div>
   </div>
    <div class="col-12 mt-4 px-4">
      <div class="mb-5">
        <h5>Description</h5>
        <p>
          <?php  
           echo $game_data['description'];
          ?>
        </p>
      </div>
      <div>
        <h5 class="mb-3">Reviews & Ratings</h5>
        <div class="">
          <div class="profile d-flex align-items-center mb-3">
            <i class="bi bi-person-circle fs-3 me-2"></i>
            <h6 class="m-0 ms-0">Random User1</h6>
         </div>
         <p>
         The soft and durable surface provides a comfortable and safe playing 
         experience, while the indoor environment allows for consistent temperature 
         and lighting conditions.
         </p>
         <div class="rating">
           <i class="bi bi-star-fill text-warning"></i>
           <i class="bi bi-star-fill text-warning"></i>
           <i class="bi bi-star-fill text-warning"></i>
           <i class="bi bi-star-fill text-warning"></i>
         </div>
       </div>
     </div>
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
