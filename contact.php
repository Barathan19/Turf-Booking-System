<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> - Contact</title>


</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>



<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">Contact Us</h2>
  <h2 class="h-line bg-dark"></h2>
  <p class="text-center mt-3">
   Thank you for considering Elite Turf for your indoor turf needs. We are dedicated
   to providing high-quality turf solutions and exceptional customer service. If you
   have any questions or would like to request a quote, please fill out the contact
   form below, and one of our representatives will get back to you as soon as possible.
  </p>
</div>






<div class="container">
  <div class="row">
    <div class="col-lg-6 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4">
        <iframe src="<?php echo $contact_r['iframe'] ?>" height="320px" class="w-100 rounded mb-4"></iframe>
          <h5 class="">Address</h5>
          <a href="<?php echo $contact_r['gmap']?>" target="_blank" class="d-inline-block text-decoration-none text-dark">
          <i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address'] ?>
          </a>
          <h5 class="mt-4">Call Us</h5>
          <a href="tel: +<?php echo $contact_r['pn1']?>" class="d-inline-block mb-2 text-decoration-none text-dark">
          <i class="bi bi-telephone-fill"></i>+<?php echo $contact_r['pn1']?>
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
        <h5 class="mt-4">Email</h5>
        <a href="mailto: <?php echo $contact_r['email'] ?>" class="d-inline-block text-decoration-none text-dark">
          <i class="bi bi-envelope-fill"></i> <?php echo $contact_r['email'] ?>
        </a>
        <h5 class="mt-4">Follow Us</h5>
        <?php
        if($contact_r['tw']!=''){
          echo<<<data
          <a href="$contact_r[tw]" class="d-inline-block text-dark fs-5 me-2">
           <i class="bi bi-twitter me-1"></i>
          </a>
         data;
        }
        
        ?>
        <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark fs-5 me-2">
         <i class="bi bi-facebook m-1"></i>
        </a>
        <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark fs-5 me-2">
          <i class="bi bi-instagram me-1"></i>
        </a>
      </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4">
        <form method="POST">
          <h5 class="">Send a Message</h5>
          <div class="mt-3">
             <label class="form-label" style="font-weight: 500;">Name</label>
             <input name="name" required type="text" class="form-control shadow-none">
           </div>
           <div class="mt-3">
             <label class="form-label" style="font-weight: 500;">Email</label>
             <input name="email" required type="email" class="form-control shadow-none">
           </div>
           <div class="mt-3">
             <label class="form-label" style="font-weight: 500;">Subject</label>
             <input name="subject" required type="text" class="form-control shadow-none">
           </div>
           <div class="mt-3">
             <label class="form-label" style="font-weight: 500;">Message</label>
             <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
           </div>
           <button type="submit" name="send" class="btn text-white custom-bg shadow-none mt-3">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>


<?php 

if(isset($_POST['send'])){
  $frm_data= filteration($_POST);

  $q="INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
  $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

  $res = insert($q,$values,'ssss');
  if($res==1){
    alert('success','Mail sent!');
  }
  else{
    alert('error','server down try later!');
  }
}



?>



<?php require('inc/footer.php'); ?>


</body>
</html>
