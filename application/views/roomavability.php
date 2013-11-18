<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            
        }); 
    </script>
	
</head>
<body>

<div id="container">
	<h1>Welcome to cutelobby</h1>

            <?php
           
            $attributes = array('class' => 'hotelsearch', 'id' => 'hotelsearchform');
            echo form_open('getreservation/getresrv',$attributes);
            
            $hiddendata = array(
            'roomTypeCode' => $roomTypeCode,
            'rateCode' => $rateCode,
            'chargeableRate' => $chargeableRate,
            'ratekey' => $ratekey,
            'numberOfRooms' => $numberOfRooms,
            'hotel_id' => $hotel_id
            );

            echo form_hidden($hiddendata);
            
            
            for($i = 0 ;$i<$numberOfRooms;$i++){
            $attributes = array('id' => 'room-'.$i, 'class' => 'guest_details');
            echo form_fieldset('Guest Details', $attributes);
            echo form_label('First Name', 'room-'.$i.'-firstName');
            $firstName = array(
              'name'        => 'room-'.$i.'-firstName',
              'id'          => 'room-'.$i.'-firstName',
              //'value'       => 'kolkata',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'Test Booking'
            );

            echo form_input($firstName);
            echo '<br>';
            echo form_label('Last Name', 'room-'.$i.'-lastName');
            $lastName = array(
              'name'        => 'room-'.$i.'-lastName',
              'id'          => 'room-'.$i.'-lastName',
              //'value'       => 'kolkata',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'Test Booking'
            );

            echo form_input($lastName);
            echo '<br>';
            echo form_fieldset_close();
            }
            
            echo form_label('Email', 'email');
            $email = array(
              'name'        => 'email',
              'id'          => 'email',
              //'value'       => 'kolkata',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'test@travelnow.com'
            );

            echo form_input($email);
            echo '<br>';
            echo form_label('Phone No', 'phone');
            $phone = array(
              'name'        => 'phone',
              'id'          => 'phone',
              'value'       => '2145370159',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '9831338622'
            );

            echo form_input($phone);
            echo '<br>';
            
            $attributes = array('id' => 'card-details', 'class' => 'card_details');
            echo form_fieldset('Card Details', $attributes);
            
            echo form_label('Email', 'contact_email');
            $contact_email = array(
              'name'        => 'contact_email',
              'id'          => 'contact_email',
              'value'       => 'test@travelnow.com',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'test@travelnow.com'
            );

            echo form_input($contact_email);
            echo '<br>';
            
            echo form_label('FirstName', 'cardFirstName');
            $cardFirstName = array(
              'name'        => 'cardFirstName',
              'id'          => 'cardFirstName',
              'value'       => 'Test Booking',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'Test Booking'
            );

            echo form_input($cardFirstName);
            echo '<br>';
            
            echo form_label('LastName', 'cardLastName');
            $cardLastName = array(
              'name'        => 'cardLastName',
              'id'          => 'cardLastName',
              'value'       => 'Test Booking',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'Test Booking'
            );

            echo form_input($cardLastName);
            echo '<br>';
            
            echo form_label('Supplier Type', 'cardType');
            $cardType = array(
               'VI'  => 'Visa',
               'CA'    => 'MasterCard Canada',
            );

            
            
            echo form_dropdown('cardType', $cardType,'CA');
            echo '<br>';
            
            echo form_label('Card Number', 'cardNumber');
            $cardNumber = array(
              'name'        => 'cardNumber',
              'id'          => 'cardNumber',
              'value'       => '5401999999999999',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '5401999999999999'
            );

            echo form_input($cardNumber);
            echo '<br>';
            
            echo form_label('Card Security code', 'cardSecurity');
            $cardSecurity = array(
              'name'        => 'cardSecurity',
              'id'          => 'cardSecurity',
              'value'       => '123',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '123'
            );

            echo form_input($cardSecurity);
            echo '<br>';
            
            echo form_label('Card Exp Month', 'cardExpMonth');
            $cardExpMonth = array(
              'name'        => 'cardExpMonth',
              'id'          => 'cardExpMonth',
              'value'       => '11',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '11'
            );

            echo form_input($cardExpMonth);
            echo '<br>';
            
            echo form_label('Card Exp Year', 'cardExpYear');
            $cardExpYear = array(
              'name'        => 'cardExpYear',
              'id'          => 'cardExpYear',
              'value'       => '2015',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '2015'
            );

            echo form_input($cardExpYear);
            echo '<br>';
            
            
            
            echo form_fieldset_close();
            
            
    echo form_submit('book', 'BOOK NOW');
            
	?>
</div>

</body>
</html>