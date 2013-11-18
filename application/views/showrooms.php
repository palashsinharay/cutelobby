<?php
echo '<pre>';
//print_r($roomsresult);
echo '</pre>';
 echo "<form action='".base_url()."Getreservationmulti/getresrv'  method='post' >";
foreach ($roomsresult as $hotelkey => $value) {
  
   
    echo 'Hotel Name:<h3>'.$value->hotelName.'</h3>';
    echo '<br>ArrivalDate:'.$value->arrivalDate;
    echo '<br>DepartureDate:'.$value->departureDate;
    echo '<br>Number Of Rooms Requested:'.$value->numberOfRoomsRequested;
    
    echo '<ul>';
    $booking = array();
    foreach ($value->HotelRoomResponse as $roomkey => $roomValue) {
        
        $booking['hotel_id'] = strval($value->hotelId);
        $booking['hotelName'] = strval($value->hotelName);
        $booking['checkIn'] = strval($value->arrivalDate);
        $booking['checkOut'] = strval($value->departureDate);
        $booking['numberOfRoomsRequested'] = strval($value->numberOfRoomsRequested);
        $booking['rateCode'] = strval($roomValue->rateCode);
        $booking['roomTypeCode'] = strval($roomValue->roomTypeCode);
        $booking['rateKey'] = strval($roomValue->RateInfos->RateInfo->RoomGroup->Room->rateKey);
        $booking['chargeableRate'] = strval($roomValue->RateInfos->RateInfo->ChargeableRateInfo[total]);
        
        
        echo '<li>Room Description:'.$roomValue->rateDescription.' | Room Rate:'.$roomValue->RateInfos->RateInfo->ChargeableRateInfo[total].'</li>';
        
       echo "<input type='radio' name='.$hotelkey.' value='".json_encode($booking)."'>";
        
       
    }
    echo '</ul>';
    
    
}
echo form_submit('book', 'booking');
    echo '</form>';


?>
