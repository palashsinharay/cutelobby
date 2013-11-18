<?php 
if(!isset($_SESSION)) session_start();
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once 'getrooms.php';
class Getreservationmulti extends Getrooms {

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
	//public function getresrv($rateKey,$roomTypeCode,$rateCode,$chargeableRate,$checkIn,$checkOut,$numberOfRooms,$hotel_id,$supplierType)
        public function getresrv()
	{
		$this->infoArray = $_SESSION['current_search']['infoArray'];
            
                $this->load->library('ean_api');
//              var_dump($rateKey);
//              die();
//                echo '<pre>';
//                foreach ($_POST as $key => $value) {
//                    print_r(json_decode($_POST[$key]));
//                }
//                print_r($this->infoArray);
//                echo '</pre>';
//                die();
                foreach ($_POST as $key => $value) {
                    if($value == 'booking')
                       break;
                    $data = (array)json_decode($value);
                     
//		$this->infoArray['checkIn'] = "11/6/2013";
                $this->infoArray['checkIn'] = $data['checkIn'];
		$this->infoArray['checkOut'] = $data['checkOut'];
                //$this->infoArray['checkOut'] = date('m/d/Y',strtotime($checkOut));
		$this->infoArray['numberOfRooms'] = 1;
                //$this->infoArray['numberOfRooms'] = $numberOfRooms;
//		$this->infoArray['hotel_id'] = 397487;
                $this->infoArray['hotel_id'] = $data['hotel_id'];
//              $this->infoArray['supplier_type'] = "E";
                
                $this->infoArray['rateKey'] = $data['rateKey'];
		$this->infoArray['roomTypeCode'] = $data['roomTypeCode'];
		$this->infoArray['rateCode'] = $data['rateCode'];
		$this->infoArray['chargeableRate'] = $data['chargeableRate'];
                
//                for($i = 0 ;$i<$this->infoArray['numberOfRooms'];$i++){
//                    $this->infoArray['room-'.$i.'-firstName'] = $_POST['room-'.$i.'-firstName'];
//                    $this->infoArray['room-'.$i.'-lastName'] = $_POST['room-'.$i.'-lastName'];
//                        
//                }
//                $this->infoArray['room-0-adult-total'] = 1;
                $this->infoArray['room-0-firstName'] = 'Test Booking';
                $this->infoArray['room-0-lastName'] = 'Test Booking';
                //$this->infoArray['supplierType'] = $supplierType;
                
                $this->infoArray['contact_email'] = 'test@travelnow.com';
                $this->infoArray['cardFirstName'] = 'Test Booking';
                $this->infoArray['cardLastName'] = 'Test Booking';
                $this->infoArray['contact_phone'] = 2145370159;
                $this->infoArray['contact_workPhone'] = 2145370159;
//                $this->infoArray['contact_email'] = $_POST['contact_email'];
//                $this->infoArray['cardFirstName'] = $_POST['cardFirstName'];
//                $this->infoArray['cardLastName'] = $_POST['cardLastName'];
//                $this->infoArray['contact_phone'] = $_POST['phone'];
//                $this->infoArray['contact_workPhone'] = $_POST['phone'];
                
                
                if($this->infoArray['supplierType'] == 'S'){
                $this->infoArray['cardType'] = 'VI';
                $this->infoArray['cardNumber'] = '4005550000000019';  
                }
                $this->infoArray['cardType'] = 'CA';
                $this->infoArray['cardNumber'] = '5401999999999999';
                $this->infoArray['cardSecurity'] = 123;
                $this->infoArray['cardExpMonth'] = 11;
                $this->infoArray['cardExpYear'] = 2015;
//                $this->infoArray['cardType'] = $_POST['cardType'];
//                $this->infoArray['cardNumber'] = $_POST['cardNumber'];
//                $this->infoArray['cardSecurity'] = $_POST['cardSecurity'];
//                $this->infoArray['cardExpMonth'] = $_POST['cardExpMonth'];
//                $this->infoArray['cardExpYear'] = $_POST['cardExpYear'];
                $this->infoArray['billing_address'] = 'travelnow';
                $this->infoArray['billing_city'] = 'Seattle';
                $this->infoArray['billing_state'] = 'WA';
                $this->infoArray['billing_country'] = 'US';
                $this->infoArray['billing_postalCode'] = 98004;
                
        
                $bookingresult[] = $this->ean_api->getReservation($this->infoArray);
        }
                echo "<pre>";
                print_r($bookingresult);
                echo "</pre>";
                die();
                $status = $bookingresult->reservationStatusCode = 'CF' ? 'Confirmed'  : 'not confirmed';
                $msg ='<strong>Hotel Name :</strong>'.$bookingresult->hotelName;
               	$msg .='<br><strong>City :</strong>'.$bookingresult->hotelCity;
                $msg .='<br><strong>Hotel Address :</strong>'.$bookingresult->hotelAddress;
                $msg .='<br><strong>Booking status :</strong>'.$status;
                $msg .='<br><strong>Room booked</strong> from '.$bookingresult->arrivalDate.' To '.$bookingresult->departureDate;
                $msg .='<br><strong>Bill charged:</strong>'.$bookingresult->RateInfos->RateInfo->ChargeableRateInfo[total];
                $msg .='<br><strong>Cancellation Policy:</strong>'.$bookingresult->RateInfos->RateInfo->cancellationPolicy;
                
                echo $msg;
                echo '<p><strong>check your mail for booking details</strong></p>';
                $this->load->library('email');
//                $config['protocol'] = 'sendmail';
//                $config['mailpath'] = '/usr/sbin/sendmail';
//                $config['charset'] = 'iso-8859-1';
//                $config['wordwrap'] = TRUE;
//                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from('info@cutelobby.com', 'cutelobby');
                $this->email->to($_POST['email']);
                

                $this->email->subject('Email Test');
                $this->email->message($msg);

                $this->email->send();

                //echo $this->email->print_debugger();
                
                
                }
}

