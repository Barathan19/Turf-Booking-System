<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

if(isset($_POST['check_availability'])){
    $frm_data = filteration($_POST);

    $status ="";
    $result ="";

    //check in and out validation

    $today_datetime = new DateTime();
    $today_date = new DateTime($today_datetime->format("Y-m-d"));
    $now_time = new DateTime($today_datetime->format("H:i:s"));
    
    $checkin_datetime = new DateTime($frm_data['check_in']);
    $checkin_date = new DateTime($checkin_datetime->format("Y-m-d"));
    $checkin_time = new DateTime($checkin_datetime->format("H:i:s"));
    
    $checkout_datetime = new DateTime($frm_data['check_out']);
    $checkout_time = new DateTime($checkout_datetime->format("H:i:s"));
    
    $booking_date = new DateTime($frm_data['date_b']);
    


    if($checkin_time == $checkout_time){
        $status='check_in_out_equal';
        $result = json_encode(["status"=>$status]);
    }
    else if($checkout_time < $checkin_time){
        $status='check_out_earlier';
        $result = json_encode(["status"=>$status]);
    }
    else if($booking_date < $today_date){
        $status='check_in_earlier';
        $result = json_encode(["status"=>$status]);
    }

    //check booking availability if status is blank else return the error


    if($status!=''){
        echo $result;
    }
    else{
       session_start();
       $_SESSION['game'];

       // Prepare query to check for existing bookings during the specified time period
       $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order` WHERE game_id = ? AND check_date = ? AND ((checkin_time <= ? AND checkout_time > ?) OR (checkin_time >= ? AND checkin_time < ?))";
       $values = [$_SESSION['game']['id'], $frm_data['date_b'], $checkin_time->format('H:i:s'), $checkin_time->format('H:i:s'), $checkin_time->format('H:i:s'), $checkout_time->format('H:i:s')];
       $rq_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'isssss'));

       // Check if there are any existing bookings during the specified time period
       if ($rq_fetch['total_bookings'] > 0) {
           $status = 'unavailable';
           $result = json_encode(['status' => $status]);
           echo $result;
           exit;
       }

       // Calculate time difference between check-in and check-out times
       $time_diff = $checkin_time->diff($checkout_time);
       $time_diff_str = $time_diff->format('%H');
       $payment = $_SESSION['game']['price'] * $time_diff_str;
       $_SESSION['game']['payment'] = $payment;
       $_SESSION['game']['available'] = true;

       $result = json_encode([
           'status' => 'available',
           'hours' => $time_diff_str,
           'payment' => $payment,
           'check_in' => $checkin_datetime->format('Y-m-d H:i:s'),
           'check_out' => $checkout_datetime->format('Y-m-d H:i:s')
       ]);
       echo $result;
    }
}

?>
