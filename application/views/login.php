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
	<h1>User Login</h1>

            <?php
            //session_destroy();
            $attributes = array('class' => 'user', 'id' => 'user-login');
            $action ='user/loginDetails';
            echo form_open($action,$attributes);


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
            
            

 

            echo '<br>';
            echo form_submit('Login', 'Login');
            echo form_close();
	?>
</div>

</body>
</html>