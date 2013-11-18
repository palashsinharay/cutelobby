<?php 
if(!isset($_SESSION)) session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once 'gethotels.php';
class Getrooms extends Gethotels {

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
	
        
    
        //public function buildroom($city,$checkIn,$checkOut,$numberOfRooms,$hotel_id,$supplierType)
        public function buildroom($hotel_id)
	{
	
            $this->infoArray = $_SESSION['current_search']['infoArray'];
            
                
                $this->load->library('ean_api');
//                echo "<pre>";
//                print_r($this->infoArray);
//                echo "</pre>";
//                die();
                
//                $this->infoArray['city'] = "kolkata";
                //$this->infoArray['city'] = $city;
//		$this->infoArray['checkIn'] = "11/6/2013";
               // $this->infoArray['checkIn'] = date('m/d/Y',strtotime($checkIn));
                
//		$this->infoArray['checkOut'] = "11/8/2013";
                //$this->infoArray['checkOut'] = date('m/d/Y',strtotime($checkOut));
//		$this->infoArray['numberOfRooms'] = 1;
                //$this->infoArray['numberOfRooms'] = $numberOfRooms;
//		$this->infoArray['hotel_id'] = 397487;
                $this->infoArray['hotel_id'] = $hotel_id;
               // $this->infoArray['supplierType'] = $supplierType;
              
        
                $hotelresult = $this->ean_api->getRooms($this->infoArray);
                echo "<pre>";
                //print_r($hotelresult);
                echo "</pre>";
                //echo "xxx";
                $roomlist = $hotelresult->HotelRoomResponse;
                echo '<ol>';
                foreach ($roomlist as $key => $value) {
                
                echo "<pre>";
                //print_r($value);
                echo "</pre>";
                //die();
               
               $roomTypeCode = $value->roomTypeCode;
               $rateCode = $value->rateCode;
               if($this->infoArray['supplierType'] == 'E'){
               $chargeableRate = $value->RateInfos->RateInfo->ChargeableRateInfo[total];
               $ratekey = $value->RateInfos->RateInfo->RoomGroup->Room->rateKey;
               } else {
               $chargeableRate = $value->RateInfos->RateInfo->ChargeableRateInfo[commissionableUsdTotal];
               $ratekey = 'empty';
               }
               //$numberOfRooms = $this->infoArray['numberOfRooms'];
               $numberOfRooms = 1; //for test booking number of room should be one
               //$this->roomavability($roomTypeCode,$rateCode,$chargeableRate,$ratekey,$numberOfRooms,$this->infoArray['hotel_id']);
                //die();
                //$link=site_url().'getreservation/getresrv/'.$ratekey.'/'.$roomTypeCode.'/'.$rateCode.'/'.$chargeableRate.'/'.date('Y-m-d',strtotime($this->infoArray['checkIn'])).'/'.date('Y-m-d',strtotime($this->infoArray['checkOut'])).'/'.$numberOfRooms.'/'.$this->infoArray['hotel_id'].'/'.$this->infoArray['supplierType'];
                $link=site_url().'getreservation/roomavability/'.$roomTypeCode.'/'.$rateCode.'/'.$chargeableRate.'/'.$ratekey.'/'.$numberOfRooms.'/'.$this->infoArray['hotel_id'];
               echo "<li><strong>Room Description:</strong>".$value->rateDescription." <br> Book room : "."<a href=".$link.">".$value->rateDescription."</a> booking charge:".$chargeableRate."</li>";
                
                    
                }
                echo '</ol>';
                
               	
                
                }
                
        public function roomavability($roomTypeCode,$rateCode,$chargeableRate,$ratekey,$numberOfRooms,$hotel_id) {
            $data['roomTypeCode'] = $roomTypeCode;
            $data['rateCode'] = $rateCode;
            $data['chargeableRate'] = $chargeableRate;
            $data['ratekey'] = $ratekey;
            $data['numberOfRooms'] = $numberOfRooms;
            $data['hotel_id'] = $hotel_id;
            $this->load->view('roomavability',$data);
        }
}
