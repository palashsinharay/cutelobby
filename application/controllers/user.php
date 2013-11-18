<?php
if(!isset($_SESSION)) session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller {

	
 function __construct()
    {
        parent::__construct();
 
        /* Standard Libraries of codeigniter are required */
        $this->load->database();
        $this->load->helper('url');
        //$this->lang->load("message",$this->session->userdata('site_lang'));
        /* ------------------ */ 
        $this->load->model('UserModel');
 
    }
    
    public function index()
	{
	   echo "hiiii"; 
           //$data['first_name']=$this->lang->line("msg_first_name");
           // $this->load->view('welcome_message',$data);
           // $datalogin['message'] = NULL;
           // $this->load->view('login.php',$datalogin);
		  
	}
      public function registration()
	{
	   //echo "hiiii"; 
           //$data['first_name']=$this->lang->line("msg_first_name");
            $this->load->view('user_registration',$data);
           	  
	}
    public function getUserDetails()
    {
        //echo "hiiii"; 
         $posted["first_name"]       = trim($this->input->post("first_name"));
         $posted["last_name"]                 = trim($this->input->post("last_name"));
         $posted["email_address"]                = trim($this->input->post("email_address"));
         $posted["password"]                = trim($this->input->post("password"));
         $posted["lang_code"]                = trim($this->input->post("lang_code"));
         $posted["address"]     = trim($this->input->post("address"));
         $posted["city"]     = trim($this->input->post("city"));
         $posted["zip_code"]     = trim($this->input->post("zip_code"));
         $posted["country_code"]     = trim($this->input->post("country_code"));
         $posted["state_id"]     = trim($this->input->post("state_id"));
         $posted["ph_no"]     = trim($this->input->post("ph_no"));
         

            //        echo "<pre>";
            //        print_r($posted);
            //        echo "</pre>";
            //$data['userdetail']=$posted;
         $i_newid = $this->UserModel->insertUser($posted);
            if($i_newid!=0)
            {
                // email shoot to new user
                   $msg="Thank You for your registration to cutelobby ";
                   echo '<p><strong>check your mail for registration confirmation</strong></p>';
                   $this->load->library('email');
                   //                $config['protocol'] = 'sendmail';
                   //                $config['mailpath'] = '/usr/sbin/sendmail';
                   //                $config['charset'] = 'iso-8859-1';
                   //                $config['wordwrap'] = TRUE;
                   //                $config['mailtype'] = 'html';
                   $this->email->initialize($config);
                   $this->email->from('info@cutelobby.com', 'cutelobby');
                   $this->email->to($posted["email_address"]);
                   $this->email->subject('Email Test');
                   $this->email->message($msg);

                   $this->email->send();
                   
                // Save user details in session
                   
                        $data['user_data'] = array( 
			'is_logged_in' => true,
			'user_id' => $i_newid,
			'user_first_name' => $posted["first_name"] ,
			'user_last_name' => $posted["last_name"] ,
			'user_lang_code' => $posted["lang_code"] ,
			'user_email_address'=>$posted["email_address"],
			'user_address'=> $posted["address"],
                        'user_ph_no'=>$posted["ph_no"] ,
			'user_country_code'=>$posted["country_code"],
			'user_city'=>$posted["city"] ,
			'user_zip_code'=>$posted["zip_code"],
                        'user_state_id'=>$posted["state_id"]                           
			);
                    $_SESSION['LOGGEDIN_USER']=$data;
	            
//                    echo "<pre>";
//                    print_r($_SESSION);
//                    echo "</pre>";
//                    die();
                    redirect('gethotelpackage/search');

            }
            else 
            {
                echo '<p><strong>There is some problem in your registration !! Try again later !! </strong></p>';
            }
         
        
    } 
    public function login()
    {
      //echo "hiiii"; 
      //$data['first_name']=$this->lang->line("msg_first_name");
       $this->load->view('login',$data);

    }
    public function loginDetails()
    {

         $posted["email_address"]                = trim($this->input->post("email_address"));
         $posted["password"]                     = trim($this->input->post("password"));
    

                    $user['data'] = $this->UserModel->loginUser($posted["email_address"],$posted["password"]);
//                    echo "<pre>";
//                    print_r($user['data']);
//                    echo "</pre>";
//                    echo $user['data']['email_address'];
//                    die();

            if($posted["email_address"]==$user['data']['email_address'])
            {

                // Save user details in session
                   
                        $data['user_data'] = array( 
			'is_logged_in' => true,
			'user_id' => $user['data']['id'],
			'user_first_name' => $user['data']['first_name'],
			'user_last_name' => $user['data']['last_name'],
			'user_lang_code' => $user['data']['lang_code'],
			'user_email_address'=>$user['data']['email_address'],
			'user_address'=> $user['data']['address'],
                        'user_ph_no'=>$user['data']['ph_no'],
			'user_country_code'=>$user['data']['country_code'],
			'user_city'=>$user['data']['city'],
			'user_zip_code'=>$user['data']['zip_code'],
                        'user_state_id'=>$user['data']['state_id']                           
			);
                    $_SESSION['LOGGEDIN_USER']=$data;
	            
//                    echo "<pre>";
//                    print_r($_SESSION);
//                    echo "</pre>";
//                    die();
                    redirect('gethotelpackage/search');

            }
            else 
            {
                echo '<p><strong>There is some problem in your registration !! Try again later !! </strong></p>';
            }
         
        
    }     
    public function logout()
    {
       
        redirect(base_url());
    }
        
}



?>
