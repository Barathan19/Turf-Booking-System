<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> - Confirm Booking</title>
</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>
<?php 



/* check room id from url is present or not
   shutdown mode is active or not
   User is logged in or not  
 */





if(!isset($_GET['id']) || $settings_r['shutdown']==true){
  redirect('games.php');
}
else if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
  redirect('games.php');
}




//filter and get game and user data





$data = filteration($_GET);

$game_res = select("SELECT * FROM `games` WHERE `id`=? AND `status`=? AND `removed`=?",[$data['id'],1,0],'iii');

if(mysqli_num_rows($game_res)==0){
  redirect('games.php');
}

$game_data= mysqli_fetch_assoc($game_res);

$_SESSION['game']= [
  "id" => $game_data['id'],
  "name" => $game_data['name'],
  "price" => $game_data['price'],
  "payment" => null,
  "available" => false,
];

$user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],"i");
$user_data = mysqli_fetch_assoc($user_res);

?>




<div class="container">
  <div class="row">
    <div class="col-12 my-5 px-4">
      <h2 class="fw-bold">Confirm Booking</h2>
     <div style="font-size: 14px;">
       <a href="index.php" class="text-secondary text-decoration-none">Home</a>
       <span class="text-secondary"> > </span>
       <a href="games.php" class="text-secondary text-decoration-none">Games</a>
       <span class="text-secondary"> > </span>
       <a href="#" class="text-secondary text-decoration-none">Confirm</a>
     </div>
   </div>
   <div class="col-lg-7 col-md-12 px-4">
    <?php
     $game_thumb =ABOUT_IMG_PATH."thumbnail.jpg";
     $thumb_q = mysqli_query($con,"SELECT * FROM `game_images` WHERE `game_id`='$game_data[id]' AND `thumb`='1'");
     if(mysqli_num_rows($thumb_q)>0){
      $thumb_res = mysqli_fetch_assoc($thumb_q);
      $game_thumb=ABOUT_IMG_PATH.$thumb_res['image'];
     }
     
     echo<<<data
       <div class="card p-3 shadow-sm rounded">
         <img src="$game_thumb" class="img-fluid rounded mb-3">
         <h5>$game_data[name]</h5>
         <h6>₹$game_data[price] per hour</h6>
       </div>
     data;
    ?>
   </div>

   <div class="col-lg-5 col-md-12 px-4">
     <div class="card mb-4 border-0 shadow-sm rounded-3">
        <div class="card-body">
          <form action="payment.php" method="POST" id="booking_form">
            <h6 class="mb-3">BOOKING DETAILS<hr></h6>           
            <div class="row">
              <div class="col-md-6 mb-3">
               <label class="form-label">Name</label>
               <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
             </div>   
             <div class="col-md-6 mb-3">
               <label class="form-label">Phone Number</label>
               <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
             </div>
             <div class="col-md-12 mb-3">
               <label class="form-label">Address</label>
               <textarea name="address" rows="1" class="form-control shadow-none" required><?php echo $user_data['address'] ?></textarea>
             </div>
             <div class="col-md-6 mb-3">
               <label class="form-label">Check-in</label>
               <input name="checkin" onchange="check_availability()" id="check-in-time" type="time" class="form-control shadow-none" required>
             </div>
             <div class="col-md-6 mb-3">
               <label class="form-label">Check-out</label>
               <input name="checkout" onchange="check_availability()" id="check-out-time" type="time" class="form-control shadow-none" required>
             </div>
             <div class="col-md-9 mb-4">
               <label class="form-label">Date</label>
               <input name="date_book" onchange="check_availability()" id="datef" type="date" class="form-control shadow-none" required>
             </div> 
             <div class="col-12">
               <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                 <span class="visually-hidden">Loading...</span>
               </div>
               <h6 class="mb-3 text-danger" id="pay_info">Provide Check-in & Check-out time along with the date!</h6>
               <button id="pay-now-btn" name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>
             </div>          
           </div>
          </form>
        </div>
      </div>
   </div>

  </div>
</div>





<?php require('inc/footer.php'); ?>

<script>

let booking_form = document.getElementById('booking_form');
let info_loader = document.getElementById('info_loader');
let pay_info = document.getElementById('pay_info');


function check_availability()
{

  let checkin_val = booking_form.elements['checkin'].value;
  let checkout_val = booking_form.elements['checkout'].value;
  let date_val = booking_form.elements['date_book'].value;
  booking_form.elements['pay_now'].setAttribute('disabled',true);

  if(checkin_val!='' && checkout_val!='' && date_val!=''){
    pay_info.classList.add('d-none');
    pay_info.classList.replace('text-dark','text-danger');
    info_loader.classList.remove('d-none');
    let data= new FormData();
    data.append('check_availability','');
    data.append('check_in',checkin_val);
    data.append('check_out',checkout_val);
    data.append('date_b',date_val);

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/confirm_booking.php",true);


    xhr.onload = function(){
        console.log(this.responseText);
        let data = JSON.parse(this.responseText);

       if(data.status=='check_in_out_equal'){
        pay_info.innerHTML = "You can't Check-in and Check-out at same time!";
        alert('error',"You can't Check-in and Check-out at same time!");
       }
       else if(data.status=='check_out_earlier'){
        pay_info.innerHTML = "Check-out-time is earlier than Check-in-time!";
        alert('error',"Check-out-time is earlier than Check-in-time!");
       }
       else if(data.status=='check_in_earlier'){
        pay_info.innerHTML = "Check-in-time is earlier than today's date!";
        alert('error',"Check-in-time is earlier than today's date!");
       }
       else if(data.status=='unavailable'){
        pay_info.innerHTML = "Game not available at this check-in-time!";
       alert('error',"Game not available at this check-in-time!");
       }
       else{
        pay_info.innerHTML = "No. of Hours:"+data.hours+"<br>Total Amout to Pay: $"+data.payment;
        pay_info.classList.replace('text-danger','text-dark');
        booking_form.elements['pay_now'].removeAttribute('disabled');
       }
       pay_info.classList.remove('d-none');
       info_loader.classList.add('d-none');
    }
    xhr.send(data);
  }
}













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
      console.log(document.querySelector('#check-in-time'));
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
