<?php
if(!isset($_SESSION)) session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gethotelpackage extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public $infoArray;

        
        public function index()
	{            
//              echo '<pre>';
//              print_r($_POST);
//              echo '</pre>';
//              die();
                $_SESSION['setting']['currency'] = 'INR';
                $_SESSION['setting']['currencyCode'] = 'INR';
                $_SESSION['setting']['countryLocale'] = 'en_US';
                $datediff = $this->dateDiff($_POST['checkIn'], $_POST['checkOut']);
                if($datediff > 14){
                    echo 'date diffrence more then 14<br>';
                    $link = site_url().'Gethotelpackage/search';
                    echo "<a href='".$link."'>click here to search again</a>";
                    die();
                    
                }
                $this->load->library('ean_api_multi');
                //$_SESSION = NULL;   
                //$this->infoArray["city"] = "mumbai";
                $this->infoArray["city"] = $_POST['city'];
                //$this->infoArray['countryCode'] = 'IN';
                $this->infoArray['countryCode'] = $_POST['countryCode'];
                //$this->infoArray['checkIn'] = "11/7/2013";
                $this->infoArray['checkIn'] = $_POST['checkIn'];
                //$this->infoArray['checkOut'] = "11/8/2013";
                $this->infoArray['checkOut'] = $_POST['checkOut'];
                //$this->infoArray['rooms'] = "room1=1,3&room2=1,5";
                //$this->infoArray['rooms'] = "room1=1,3";
                $this->infoArray['numberOfRooms'] = $_POST['numberOfRooms'];
                for($i = 0 ;$i<$this->infoArray['numberOfRooms'];$i++){
                    $this->infoArray['room-'.$i.'-adult-total'] = $_POST['room-'.$i.'-adult-total'];
                    $this->infoArray['room-'.$i.'-child-total'] = $_POST['room-'.$i.'-child-total'];
                        for($j=0;$j<$this->infoArray['room-'.$i.'-child-total'];$j++){
                            $this->infoArray['room-'.$i.'-child-'.$j.'-age'] = 5; //feeding static age for all child
                        }
                }
                
                $this->infoArray['numberOfResults'] = 1;
                $this->infoArray['sort'] = 'PRICE';
                $this->infoArray['searchRadius'] = 20;
                //$this->infoArray['supplierType'] = 'S';
                $this->infoArray['supplierType'] = $_POST['supplierType'];
                
                $hotelresult = $this->ean_api_multi->getHotels($this->infoArray);
//                echo "<pre>";
//                echo 'xxxxxx<br>hotel response<br>xxxxxx';
//                print_r($hotelresult);
//                echo "</pre>";
                
                if(empty($hotelresult['error'])){
                $roomsresult = $this->ean_api_multi->getRooms($this->infoArray,$hotelresult['packageResult']);
                }else{
                    echo 'error in hotel search:'.$hotelresult['error'];
                }
//                echo "<pre>";
//                echo 'xxxxxx<br>room response<br>xxxxxx';
//                print_r($roomsresult);
//                echo "</pre>";
//                die();
                $data['roomsresult'] = $roomsresult;
                $this->load->view('showrooms',$data);
                $_SESSION['current_search']['infoArray'] = $this->infoArray;
                //$hotellist = $hotelresult['hotels']->HotelList->HotelSummary;
                $hotelresult2pass = $hotelresult['hotels'];
                $this->infoArray['moreResultsAvailable'] = (string)$hotelresult['hotels']->moreResultsAvailable;
                $this->infoArray['cacheKey'] = (string)$hotelresult['hotels']->cacheKey;  
                $this->infoArray['cacheLocation'] = (string)$hotelresult['hotels']->cacheLocation;
                $this->infoArray['page'] = 0;
                $this->infoArray['supplierType'] = (string)$hotelresult['hotels']->HotelList->HotelSummary->supplierType;
                //$this->rendergetroomlink($hotelresult2pass);
             
	}
        
        public function rendergetroomlink($hotelresult2pass){
                $hotellist = $hotelresult2pass->HotelList->HotelSummary;
                //$_SESSION['current_search']['info_array'] = $infoArray;
                //$_SESSION['current_search']['hotel_result'] = $hotelresult2pass;
                $_SESSION['current_search']['infoArray'] = $this->infoArray;
//                echo "<pre>";
//                echo 'XXXXXXXXXXXX';
//                //print_r($hotelresult2pass);
//                print_r($_SESSION);
//                echo "</pre>";
                echo "<strong>City:</strong>".$_SESSION['current_search']['city'];
                echo "<br><strong>check in:</strong>".$_SESSION['current_search']['check_in'];
                echo "<br><strong>check out:</strong>".$_SESSION['current_search']['check_out'];
                echo '<br><br>';
            foreach ($hotellist as $key => $value) {
                echo "<pre>";
                //print_r($value);
                echo "</pre>";
//                $link = site_url().'getrooms/buildroom/'.$_SESSION["current_search"]["city"].'/'.  date('Y-m-d',strtotime($_SESSION["current_search"]["check_in"])) .'/'.date('Y-m-d',strtotime($_SESSION["current_search"]["check_out"])).'/'.$this->infoArray['numberOfRooms'].'/'.$value->hotelId.'/'.$value->supplierType;
                $link = site_url().'getrooms/buildroom/'.$value->hotelId;
                $rateType = $value->RoomRateDetailsList->RoomRateDetails->RateInfos->RateInfo->rateType;
                
                echo "Hotel Name:".$value->name." | check roomavability : "."<a href=".$link.">".$value->name."</a> | Payment type :".($rateType != 'MerchantStandard' ? 'Hotel Pay'  : 'Online pay');
                echo '<br>';
                }
                
                if($this->infoArray['moreResultsAvailable'] == true){
                
                echo '<br><br>';
                $nextmorelink = site_url().'gethotels/morehotels/'.$this->infoArray['cacheKey'].'/'.$this->infoArray['cacheLocation'].'/'.$this->infoArray['page'];
                
                    if($this->infoArray['page'] == 1 || $this->infoArray['page'] == 0 ){
                        $premorelink = site_url().'gethotels';
                    }else {$premorelink = site_url().'gethotels/morehotels/'.$_SESSION['current_search']['page'][$this->infoArray['page']-2]['key'].'/'.$_SESSION['current_search']['page'][$this->infoArray['page']-2]['location'].'/'.($this->infoArray['page']-2);
                    }
                
               // echo "<a href=".$premorelink."><--Pre</a>  |";
                echo "<a href=".$nextmorelink.">Next --></a>";
                
                }
        }
                
        public function addroomfield($num) {
            echo "<legend>Room Details</legend>";
            for($j=0;$j<$num;$j++){
            
            echo form_label('ROOM'.$j); echo " => ";
            echo form_label('Number Of Adult', 'room-'.$j.'-adult-total');
            $room[$j]['adulttotal'] = array(
                '1'    => '1',
                '2'    => '2',
                '3'    => '3',
                '4'    => '4',
                
            );

            echo form_dropdown('room-'.$j.'-adult-total', $room[$j]['adulttotal']);
            
            echo form_label('Number Of child', 'room-'.$j.'-child-total');
            $room[$j]['childtotal'] = array(
                '0'    => '0',
                '1'    => '1',
                '2'    => '2',
                '3'    => '3',
                              
            );

            echo form_dropdown('room-'.$j.'-child-total', $room[$j]['childtotal']);
            echo '<br>';
            }
        }

        public function search() {
            $data['action'] = 'gethotelpackage/index'; 
            $this->load->view('search',$data);
        }
        function dateDiff ($d1, $d2) {
        // Return the number of days between the two dates:

          return round(abs(strtotime($d1)-strtotime($d2))/86400);

        }  // end function dateDiff
}
