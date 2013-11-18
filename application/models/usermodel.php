<?php



class UserModel extends CI_Model {
        public $_users = 'cutelobby_users';

	function __construct()
	{
		//parent::Model();
		parent::__construct();
	}


        //function for getting cms page content
	function get_login($username)
	{
           	$query = $this->db->get_where($this->_user, array('username' => $username));
                $this->result = $query->result();
                if($this->result != NULL){
                    return $this->result[0];
                }else{
                    return FALSE;
                }
  	}

        function insertUser($posted)
	{
           //  echo "ami ekhane";
//             echo "<pre>";
//             print_r($posted);
//             echo "</pre>";

// die();
             
            $i_ret_=0; ////Returns false
            if(!empty($posted))
            {
                                $s_qry="Insert Into cutelobby_users Set ";
                                $s_qry.=" email_address=? ";
                                $s_qry.=",first_name=? ";
                                $s_qry.=",last_name=? ";
                                $s_qry.=",password=? ";
                                $s_qry.=",lang_code=? ";
                                $s_qry.=",address=? ";
                                $s_qry.=",city=? ";
                                $s_qry.=",zip_code=? ";
                                $s_qry.=",country_code=? ";
                                $s_qry.=",state_id=? ";
                                $s_qry.=",ph_no=? ";
                                $s_qry.=",created_on=? ";
                                $s_qry.=",last_login=? ";
                                $s_qry.=",is_active=? ";
                                $s_qry.=",requested_passowrd=? ";
                                $this->db->query($s_qry,array(
                                $posted["email_address"],
                                $posted["first_name"],
                                $posted["last_name"],
                                md5($posted["password"]),
                                $posted["lang_code"],
                                $posted["address"],
                                $posted["city"],
                                $posted["zip_code"],
                                $posted["country_code"],
                                $posted["state_id"],
                                $posted["ph_no"],
                                date("Y-m-d H:i:s"),
                                date("Y-m-d H:i:s"),
                                '1',
                                '0',                                    
                                  
             ));
               // echo $this->db->last_query();
               // die();
                $i_ret_=$this->db->insert_id();     
                
            }
            unset($s_qry, $posted );
            return $i_ret_;
	}
        
        
       function loginUser($email_address,$password) {
        $this->query = $this->db->select('*')->from('cutelobby_users')->where(array('email_address'=>trim($email_address),'password'=>trim(md5($password))))->get();
        return $this->query->row_array();
     }

        
}	


		

	


