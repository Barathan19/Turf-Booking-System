<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']?> - Facilities</title>


</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>

<style>
  .pop:hover{
    border-top-color: var(--teal) !important;
    transform: scale(1.03);
    transition: all 0.3s;
  }
</style>


<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">Our Facilities</h2>
  <h2 class="h-line bg-dark"></h2>
  <p class="text-center mt-3">
   Indoor turf facilities are designed for sports and activities that require a safe
   and durable surface for athletes to train and compete on. The following is an outline
   of the typical features and considerations for indoor turf facilities:
  </p>
</div>

<div class="container">
  <div class="row">
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
         <img src="pictures\facilities\IMG_43553.svg" width="40px">
         <h5 class="m-0 ms-3">Wifi</h5>
        </div>
        <p>
         An indoor WiFi facility that also has an indoor turf area combines wireless internet
         connectivity with a safe and durable surface for athletes to train and compete.
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
         <img src="pictures\facilities\9.jpg" width="40px">
         <h5 class="m-0 ms-3">Stadium</h5>
        </div>
        <p>
         Indoor turf stadium facilities are large enclosed spaces designed for various sports 
         and activities that require a safe, durable, and flexible playing surface. 
        </p>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-5 px-4">
      <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
        <div class="d-flex align-items-center mb-2">
         <img src="pictures\facilities\11.jpg" width="40px">
         <h5 class="m-0 ms-3">Vending Machine</h5>
        </div>
        <p>
         The vending machines are strategically placed around the facility to ensure easy access for users,
         and they usually dispense food, drinks, and other items.
        </p>
      </div>
    </div>
  </div>
</div>
<?php require('inc/footer.php'); ?>


</body>
</html>
