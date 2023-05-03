<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> - Home</title>
<style>
@media screen and (max-width: 575px){
  .availability-form{
    margin-top: 0px;
    padding: 0 35px;
}
}
.availability-form{
     margin-top: -50px;
     z-index:2;
     position: relative;
}
</style>


</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>


<!-- Image Slider Home Page --> 

<div class="container-fluid px-lg-4 mt-4">
 <div class="swiper swiper-container">
   <div class="swiper-wrapper">
      <div class="swiper-slide">
       <img src="images\about\1.jpg" class="w-100 d-block">
     </div>
     <div class="swiper-slide">
       <img src="images\about\2.jpg" class="w-100 d-block">
     </div>
   </div>
 </div>
</div>

<!-- Check Availability-Form -->


<div class="container availability-form">
  <div class="row">
    <div class="col-lg-12 bg-white shadow p-4 rounded">
      <h5 class="mb-4">Check Booking Availability</h5>
      <form>
        <div class="row align-items-end">
          <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Check-in</label>
            <input type="time" id="checkin-time-input" class="form-control shadow-none">
         </div> 
         <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Check-out</label>
            <input type="time" id="checkout-time-input" class="form-control shadow-none" step="1800">
         </div>
         <div class="col-lg-3 mb-3">
            <label class="form-label" style="font-weight: 500;">Date</label>
            <input type="date" id="datef" class="form-control shadow-none">
         </div>
         <div class="col-lg-2 mb-3">
            <label class="form-label" style="font-weight: 500;">No.of Members</label>
            <select class="form-select shadow-none">
             <option selected>Select</option>
             <option value="1">One</option>
             <option value="2">Two</option>
             <option value="3">Three</option>
             <option value="4">Four</option>
             <option value="5">Five</option>
             <option value="6">Six</option>
             <option value="7">Seven</option>
             <option value="8">Eight</option>
             <option value="9">Nine</option>
             <option value="10">Ten</option>
           </select>
         </div> 
         <div class="col-lg-1 mb-lg-3 mt-2 d-flex justify-content-center">
           <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
         </div>
       </div>
     </form>
   </div>
 </div>
</div>



<!--Our Games -->


<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR GAMES</h2>
<div class="container">
  <div class="row">
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
      $login=0;
      if(isset($_SESSION['login']) && $_SESSION['login']==true){
        $login=1;
      }
      $book_btn="<button onclick='checkLoginToBook($login,$game_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";
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
    <div class="col-lg-12 text-center mt-5">
      <a href="games.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More>>></a>
    </div>
  </div>
</div>

<!--Our Facilities -->


<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR Facilities</h2>
<div class="container">
  <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
    <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
      <img src="pictures\facilities\IMG_43553.svg" width="80px">
      <h5 class="mt-3">Wifi</h5>
    </div>
    <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
      <img src="pictures\facilities\9.jpg" width="80px">
      <h5 class="mt-3">Stadium</h5>
    </div>
    <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
      <img src="pictures\facilities\11.jpg" width="80px">
      <h5 class="mt-3">Vending Machine</h5>
    </div>
  </div>
</div>



<!--Testimonials -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Testimonials</h2>
<div class="container">
 <div class="swiper swiper-testimonals">
   <div class="swiper-wrapper">
     <div class="swiper-slide bg-white p-4">
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
      <div class="swiper-slide bg-white p-4">
       <div class="profile d-flex align-items-center mb-3">
         <i class="bi bi-person-circle fs-3 me-2"></i>
         <h6 class="m-0 ms-0">Random User2</h6>
       </div>
        <p>
         The artificial turf surface provides a consistent playing surface that is
         not affected by rain, snow, or extreme temperatures, which makes it ideal
         for sports such as soccer, football, and field hockey.
        </p>
        <div class="rating">
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
          <i class="bi bi-star-fill text-warning"></i>
        </div>
      </div>
    </div>
  <div class="swiper-pagination"></div>
  </div>
</div>


<!--Reach Us -->



<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach Us</h2>
<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
    <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe>
    </div>
    <div class="col-lg-4 col-md-4">
      <div class="bg-white p-4 rounded">
        <h5>Call Us</h5>
        <a href="tel: +<?php echo $contact['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
        <i class="bi bi-telephone-fill"></i>+<?php echo $contact_r['pn1'] ?>
        </a>
        <br>
        <?php
         if($contact_r['pn2']!=''){
          echo<<<data
          <a href="tel: +$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i>+$contact_r[pn2]
        </a>
        data;
         }
        ?>
      </div>
      <br>
      <div class="bg-white p-4 rounded">
        <h5>Follow Us</h5>
        <?php
        if($contact_r['tw']!=''){
          echo<<<data
          <a href="$contact_r[tw]" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-twitter me-1"></i> Twitter</span>
          </a>
          <br>
          data;
        }
        ?>
        <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-facebook m-1"></i> Facebook</span>
        </a>
        <br>
        <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2"><i class="bi bi-instagram me-1"></i> Instagram</span>
        </a>
      </div>
    </div>
  </div>
</div>

<?php require('inc/footer.php'); ?>




<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });

    var swiper = new Swiper(".swiper-testimonals", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
      },
      pagination: {
        el: ".swiper-pagination",
      },
    });
</script>
<script>
  const timeInputs = document.querySelectorAll('input[type="time"]');

timeInputs.forEach(timeInput => {
  timeInput.addEventListener('change', () => {
    const selectedTime = timeInput.value;
    const [hours, minutes] = selectedTime.split(':');
    const roundedMinutes = Math.floor(parseInt(minutes) / 30) * 30;
    const formattedTime = hours + ':' + roundedMinutes.toString().padStart(2, '0');
    timeInput.value = formattedTime;

    const checkInTime = document.querySelector('#checkin-time-input').value;
    const checkOutTime = document.querySelector('#checkout-time-input').value;

    if (timeInput.id === 'checkout-time-input') {
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
