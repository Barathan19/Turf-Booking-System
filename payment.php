<?php 

require('admin/inc/db_config.php');
require('admin/inc/essentials.php');


date_default_timezone_set("Asia/Kolkata");

session_start();

if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

$user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],"i");
$user_data = mysqli_fetch_assoc($user_res);

$payamt = $_SESSION['game']['payment'];
$bookg_id = uniqid();

$user_res = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],"i");
$user_data = mysqli_fetch_assoc($user_res);

if(isset($_POST['pay_now'])){


    $frm_data = filteration($_POST);
    $query1= "INSERT INTO `booking_order`(`user_id`, `game_id`, `checkin_time`, `checkout_time`, `check_date`,`order_id`) VALUES (?,?,?,?,?,?)";
    insert($query1,[$user_data['id'],$_SESSION['game']['id'],$frm_data['checkin'],$frm_data['checkout'],$frm_data['date_book'],$bookg_id],'iissss');
    
    $booking_id= mysqli_insert_id($con);
  
    $query2="INSERT INTO `booking_details`(`booking_id`, `game_name`, `price`, `total_pay`,`user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";
  
     insert($query2,[$booking_id,$_SESSION['game']['name'],$_SESSION['game']['price'],$_SESSION['game']['payment'],$user_data['name'],$user_data['phonenum'],$user_data['address']],'isiisss');
  
     $update_query = "UPDATE `booking_order` SET `booking_status`='booked' WHERE `booking_id`=?";
     update($update_query, [$booking_id], 'i');
    }
  
  
?>












<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- custom css file link  -->
    <link rel="stylesheet">
    <style class="">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600&display=swap');

 *{
  font-family: 'Poppins', sans-serif;
  margin:0; padding:0;
  box-sizing: border-box;
  outline: none; border:none;
  text-transform: capitalize;
  transition: all .2s linear;
 }

 .container{
  display: flex;
  justify-content: center;
  align-items: center;
  padding:25px;
  min-height: 100vh;
  background: linear-gradient(90deg, grey 60%, grey 40.1%);
 }

 .container form{
  padding:20px;
  width:700px;
  background: #fff;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
 }

 .container form .row{
  display: flex;
  flex-wrap: wrap;
  gap:15px;
 }

 .container form .row .col{
  flex:1 1 250px;
 }

 .container form .row .col .title{
  font-size: 20px;
  color:#333;
  padding-bottom: 5px;
  text-transform: uppercase;
 }

 .container form .row .col .inputBox{
  margin:15px 0;
 }

 .container form .row .col .inputBox span{
  margin-bottom: 10px;
  display: block;
 }

 .container form .row .col .inputBox input{
  width: 100%;
  border:1px solid #ccc;
  padding:10px 15px;
  font-size: 15px;
  text-transform: none;
 }

 .container form .row .col .inputBox input:focus{
  border:1px solid #000;
 }

 .container form .row .col .flex{
  display: flex;
  gap:15px;
 }

 .container form .row .col .flex .inputBox{
  margin-top: 5px;
 }

 .container form .row .col .inputBox img{
  height: 34px;
  margin-top: 5px;
  filter: drop-shadow(0 0 1px #000);
 }

 .container form .submit-btn{
  width: 100%;
  padding:12px;
  font-size: 17px;
  background: black;
  color:white;
  margin-top: 5px;
  cursor: pointer;
 }

 .container form .submit-btn:hover{
  background: grey;
 }
    </style>

</head>
<body>

<div class="container">
  <form action="payres.php" onsubmit="validateForm(event)">
    <h1>BILLING</h1>
    <fieldset>
      <div class="row">
        <div class="col">
          <div class="inputBox">
            <label for="name">Name</label>
            <input type="text" id="name" required>
          </div>
          <div class="inputBox">
            <label for="email">Email</label>
            <input type="email" id="email" required>
          </div>
          <div class="inputBox">
            <label for="address">Address</label>
            <input type="text" id="address" required>
          </div>
        </div>
      </div>
    </fieldset>
    <div class="row">
      <div class="col">
         <h3>Online Payment</h3>
          <div class="inputBox">
             <label for="cardholder">Gpay No.</label>
             <input type="number" id="g-pay" name="g-pay" min="1000000000" max="9999999999">
         </div>
         <h3>Card Payment</h3>
         <div class="inputBox">
             <label for="cardnumber">Card Number</label>
             <input type="number" id="c-pay" name="c-pay" min="1000000000000000" max="9999999999999999">
         </div>
         <div class="inputBox">
             <label for="cvv">CVV</label>
             <input type="number" id="cv-pay" name="cv-pay">
          </div>
      </div>
      <div class="col">
         <h3>Booking Summary</h3>
         <div class="order-row">
             <span>Name :</span>
             <span><?php echo $user_data['name'] ?></span>
         </div>
         <div class="order-row">
             <span>Phone Number :</span>
             <span><?php echo $user_data['phonenum'] ?></span>
         </div>
         <div class="order-row">
             <span>Amount :</span>
             <span>₹<?php echo $payamt ?></span>
         </div>
         <div class="order-row">
             <span>Booking ID :</span>
             <span><?php echo $bookg_id ?></span>
         </div>
      </div>
    </div>
    <button type="submit" name="c-btn" class="submit-btn">Confirm Booking</button>
  </form>

  
<script class="">
    function validateForm(event){
        event.preventDefault();
      const cPayInput = document.getElementById("c-pay");
      const gPayInput = document.getElementById("g-pay");
      const cvPayInput = document.getElementById("cv-pay");

     if (cPayInput.value === "" && gPayInput.value === "") {
       alert("Please fill either card-pay or g-pay input.");
       return false;
     } else if ((cPayInput.value !== "" && gPayInput.value !== "")|| (cPayInput.value !== "" && cvPayInput.value == "") ) {
      alert("Please fill only one input, card-pay or g-pay / if card-pay is filled, fill cvv no. also");
      return false;
     }
     window.location = "payres.php";
   }
</script>
</div>
</body>
</html>
