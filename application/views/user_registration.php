<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
//            $("#number-Of-Rooms").change(function(){
//              //alert($(this).val());
//                                 
//                    $.ajax({
//                            url: "<?php //echo base_url().'gethotels/addroomfield/'?>"+$(this).val(),
//                            //url: "main/email_send",
//                            type: 'POST',
//                            async : false,
//                            //data: form_data,
//                            success: function(msg) {
//                            //alert(msg);
//
//                            $('#room').html(msg);
//                                                        
//                            }
//                           });
//            });
        }); 
    </script>
	
</head>
<body>

<div id="container">
	<h1>User Registration</h1>

            <?php
            //session_destroy();
            $attributes = array('class' => 'user', 'id' => 'user-registration');
            $action ='user/getUserDetails';
            echo form_open($action,$attributes);


            echo form_label('First Name : ', 'first_name');
            $first_name = array(
              'name'        => 'first_name',
              'id'          => 'first_name',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'first name'
            );

            echo form_input($first_name);
            echo '<br>';

            echo form_label('Last Name : ', 'first_name');
             $last_name = array(
              'name'        => 'last_name',
              'id'          => 'last_name',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'last name'
            );

            echo form_input($last_name);
            echo '<br>';

             echo form_label('Email : ', 'email_address');
             $email_address = array(
              'name'        => 'email_address',
              'id'          => 'email_address',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'email'
            );

            echo form_input($email_address);
            echo '<br>';
            
             echo form_label('Password : ', 'password');
             $password = array(
              'type'        => 'password',   
              'name'        => 'password',
              'id'          => 'password',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'password'
            );

            echo form_input($password);
            echo '<br>';            
            
            

            echo form_label('Language : ', 'lang_code');
            $languages = array(
                'en_US'    => 'English',
                'it_IT'    => 'Italian',
                 );


            $attr = 'id="lang_code"';
            echo form_dropdown('lang_code', $languages,'en_US',$attr);
            echo '<br>';


            echo form_label('Address : ', 'address');
            $address = array(
              'name'        => 'address',
              'id'          => 'address',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'address'
            );

            echo form_input($address);
            echo '<br>';

            echo form_label('City : ', 'city');
            $city = array(
              'name'        => 'city',
              'id'          => 'city',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'city'
            );

            echo form_input($city);
            echo '<br>';            


            echo form_label('ZipCode : ', 'zip_code');
            $zip_code = array(
              'name'        => 'zip_code',
              'id'          => 'zip_code',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'zip code'
            );

            echo form_input($zip_code);
            echo '<br>';    

            echo form_label('Country : ', 'country_code');
            $country_code= array(
                'IN'    => 'India',
                'US'    => 'United States',
                 );
            $attr = 'id="country_code"';
            echo form_dropdown('country_code', $country_code,'IN',$attr);
            echo '<br>';

            echo form_label('State : ', 'state_id');
            $state_id= array(
                'AA'    => 'Armed Forces Americas',
                'AB'    => 'Alberta',
                 );
            $attr = 'id="$state_id"';
            echo form_dropdown('state_id', $state_id,'AB',$attr);
            echo '<br>';

            echo form_label('Phone : ', 'ph_no');
            $ph_no = array(
              'name'        => 'ph_no',
              'id'          => 'ph_no',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              //'style'       => 'width:50%',
              'placeholder' => 'phone'
            );

            echo form_input($ph_no);
            echo '<br>';                



            echo '<br>';
            echo form_submit('Register', 'Register');
            echo form_close();
	?>
</div>

</body>
</html>