<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#number-Of-Rooms").change(function(){
              //alert($(this).val());
                                 
                    $.ajax({
                            url: "<?php echo base_url().'gethotels/addroomfield/'?>"+$(this).val(),
                            //url: "main/email_send",
                            type: 'POST',
                            async : false,
                            //data: form_data,
                            success: function(msg) {
                            //alert(msg);

                            $('#room').html(msg);
                                                        
                            }
                           });
            });
        }); 
    </script>
	
</head>
<body>

<div id="container">
	<h1>Welcome to cutelobby</h1>
        <h3>hello, <?php echo $user_data['user_first_name'];?> </h3>
        <h3><a href="<?php echo base_url('user/logout');?>">Logout</a> </h3>
            <?php
            $_SESSION['current_search']=NULL;
            $attributes = array('class' => 'hotelsearch', 'id' => 'hotelsearchform');
            echo form_open($action,$attributes);
    //location feild
            echo form_label('City', 'city');
            $city = array(
              'name'        => 'city',
              'id'          => 'city',
              'value'       => 'Goa,IN',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'kolkata'
            );

            echo form_input($city);
            echo '<br>';
    //country code feild
            echo form_label('Country', 'countryCode');
            $countryCode = array(
              'name'        => 'countryCode',
              'id'          => 'countryCode',
              'value'       => 'IN',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'IN'
            );

            echo form_input($countryCode);
            echo '<br>';
    //checkIn feild
            echo form_label('Check In', 'checkIn');
            $checkIn = array(
              'name'        => 'checkIn',
              'id'          => 'checkIn',
              'value'       => '12/9/2013',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '11/7/2013'
            );

            echo form_input($checkIn);
            echo '<br>';
    //checkOut feild
            echo form_label('Check Out', 'checkOut');
            $checkOut = array(
              'name'        => 'checkOut',
              'id'          => 'checkOut',
              'value'       => '12/11/2013',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => '11/8/2013'
            );

            echo form_input($checkOut);
            echo '<br>';
    //numberOfRooms type feild
            
            echo form_label('Number Of Rooms', 'numberOfRooms');
            $numberOfRooms = array(
                '1'    => '1',
                '2'    => '2',
                '3'    => '3',
                '4'    => '4',
                '5'    => '5',
            );

            
            $js = 'id="number-Of-Rooms"';
            echo form_dropdown('numberOfRooms', $numberOfRooms,'1',$js);
            echo '<br>';
            
            
            $attributes = array('id' => 'room', 'class' => 'room_details');
            echo form_fieldset('Room Details', $attributes);
            echo form_label('ROOM1'); echo " => ";
            echo form_label('Number Of Adult', 'room-0-adult-total');
            $room[0]['adulttotal'] = array(
                '1'    => '1',
                '2'    => '2',
                '3'    => '3',
                '4'    => '4',
                
            );

            echo form_dropdown('room-0-adult-total', $room[0]['adulttotal']);
            
            echo form_label('Number Of child', 'room-0-child-total');
            $room[0]['childtotal'] = array(
                '1'    => '1',
                '2'    => '2',
                '3'    => '3',
                              
            );

            echo form_dropdown('room-0-child-total', $room[0]['childtotal']);
            echo form_fieldset_close();
            
            
    //supplier type feild
            echo form_label('Supplier Type', 'supplierType');
            $supplierType = array(
               'E'  => 'Online pay',
               'S'    => 'Hotel pay',
            );

            
            
            echo form_dropdown('supplierType', $supplierType);
            echo '<br>';
            echo form_submit('Search', 'search hotels');
            
	?>
</div>

</body>
</html>