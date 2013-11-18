<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once 'Ean_api.php';
	class Ean_api_multi extends Ean_api{
            
        public function make_xml_request_multi($count,$service, $xml, $method = "get", $timestamp = ""){
			
			// re-case variables
			$method = strtolower($method);

			// For catching the server-latency errors from expedia
			if(empty($timestamp))
				$timestamp = gmdate('U');
			
			// Create signature
			$sig = md5($this->apiKey . $this->secret . $timestamp);
                        $i = 0;
			while($i <= $count) {
			// Set post-data array
			$postData[$i] = array(
				'minorRev' => $this->minorRev,
				'cid' => $this->cid,
				'apiKey' => urlencode($this->apiKey),
				'customerUserAgent' => urlencode($_SERVER['HTTP_USER_AGENT']),
				'customerIpAddress' => urlencode($_SERVER['REMOTE_ADDR']),
				'locale' => urlencode($this->countryLocale),
				'currencyCode' => urlencode($this->currency),
				'sig' => urlencode($sig),
				'_type' => urlencode($this->dataType),
				'xml' => urlencode($xml[$i])
			);
                        $i++;
                        } 
//			echo '<pre>';
//                        print_r($postData);
//                        echo '</pre>';
//                          die();
			// Construct URL to send request to
			$url = "http://api.ean.com/ean-services/rs/hotel/v3/" . $service . '?';
			
			// If using GET as request method, create postdata string
			if(strtolower($method) == "get"){
				$i = 0;
                                while($i <= $count) {
                                    $url_curl[$i] = $url;
                                foreach($postData[$i] as $key => $value)
                                    $url_curl[$i] .= $key . '=' . $value . '&'; 
                                
				$url_curl[$i] = substr($url_curl[$i], 0, -1);
                                
                                $i++;
                                }
			}
//                        echo '<pre>';
//                        print_r($url_curl);
//                        echo '</pre>';
//                        die();
						
			// Expedia 1 Query-Per-Second Rule
			$time = microtime();
			$microSeconds = $time - $lastRequest;
			if($microSeconds < 1000000 && $microSeconds > 0)
				usleep($microSeconds);
                        
                        // array of curl handles
                        $curly = array();
                        // data to be returned
                        $response = array();
                        
                        $result = array();

                        // multi handle
                        $mh = curl_multi_init();
				
                    
			// Begin executing CURL
			$curl_attempts = 0;					// Curl request counter
			$MAXIMUM_CURL_ATTEMPTS = $this->api_connection_retries;	// Max Curl attempts
			do{
                            // loop through based on $count and create curl handles
                            $i = 0;
                            while($i <= $count) {
                            
                                $curly[$i] = curl_init();
				curl_setopt($curly[$i],CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curly[$i],CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curly[$i],CURLOPT_HTTPAUTH, CURLAUTH_ANY);
				curl_setopt($curly[$i],CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8','Accept: application/xml'));
				curl_setopt($curly[$i],CURLOPT_RETURNTRANSFER,1);
				curl_setopt($curly[$i],CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curly[$i],CURLOPT_TIMEOUT,30);
				curl_setopt($curly[$i],CURLOPT_URL, $url_curl[$i]);
				curl_setopt($curly[$i],CURLOPT_VERBOSE,1);
				
				// If POSTing data, set appropriate curl options
				if(strtolower($method) == "post"){
					curl_setopt($curly[$i],CURLOPT_POST, 1);
					curl_setopt($curly[$i],CURLOPT_POSTFIELDS, $postData[$i]);
				}
                                // then add them to the multi-handle
                                curl_multi_add_handle($mh, $curly[$i]);
                            $i++;
                            }
//				// Execute, capture, and close curl request
//				$response = trim(curl_exec($curl));
//				curl_close($curl);
//                             echo '<pre>';
//                             print_r($curly);
//                             echo '</pre>';
//                             die();

                            // execute the handles
                            $running = null;
                            do {
                              curl_multi_exec($mh, $running);
                            } while($running > 0);


                            // get content and remove handles
                            foreach($curly as $id => $c) {
                              $response[$id] = curl_multi_getcontent($c);
                              curl_multi_remove_handle($mh, $c);
                            }

                            // all done
                            curl_multi_close($mh);

//                             echo '<pre>';
//                             print_r($response);
//                             echo '</pre>';
                             //die();
                         
			} while (empty($response) == 1 && ++$curl_attempts < $MAXIMUM_CURL_ATTEMPTS);
                        
                   

			// Set the lastrequest time to our current time			
			$this->lastRequest = microtime();
                        $i = 0;
			while ($i <=$count) {
			// Remove junk characters from response
			$response[$i] = str_replace("&amp;lt;","&lt;",$response[$i]);
			$response[$i] = str_replace("&amp;gt;","&gt;",$response[$i]);
			$response[$i] = str_replace("&amp;apos;","&apos;",$response[$i]);
			$response[$i] = str_replace("&amp;#x0A","",$response[$i]);
			$response[$i] = str_replace("&amp;#x0D","",$response[$i]);
			$response[$i] = str_replace("&#x0D","",$response[$i]);
			$response[$i] = str_replace("&#x0A","",$response[$i]);
			$response[$i] = str_replace("&amp;#x09","",$response[$i]);
			$response[$i] = str_replace("&amp;amp;amp;","&amp;",$response[$i]);
			$response[$i] = str_replace("&lt;br&gt;","<br />",$response[$i]);

			// load XML response as SimpleXML object
			$results[$i] = simplexml_load_string($response[$i]);
                        $i++;
                        }
			// ERROR CATCH: timestamp / latency between expedia server
			if((string)$results->EanWsError->handling == 'RECOVERABLE' && (string)$results->EanWsError->category == 'AUTHENTICATION'){
				$newServerTime = $results->EanWsError->ServerInfo['timestamp'];
				return $this->make_xml_request_multi($count,$service, $xml, $method, $newServerTime);
			}
			
			// If we cannot connect to the EAN API
			if (empty($response) == 1)
				$results['error'] = 'Error reaching XML gateway';
//                    echo '<pre>';
//                    print_r($results);
//                    echo '</pre>';
//                    die();
                                                    
                        return $results;
		}  
        public function getHotels($infoArray){
	
		$destination = $infoArray['city'];
		$destinationID = $infoArray['desID'];
		$language = $infoArray['locale'];
		$check_in = $infoArray['checkIn'];
		$check_out = $infoArray['checkOut'];
		$rooms = $infoArray['numberOfRooms'];
		$children_breakdown = "";
		$property_types = $infoArray['propertyType'];
		$hotel_name = $infoArray['hotel_name'];
		$page = $infoArray['page'];
		$sort = $infoArray['sort'];
		$address = $infoArray['address'];
		$hotel_ids = $infoArray['hotel_ids'];
		$nearby_landmark = $infoArray['nearby_landmark'];
		$cacheKey = $infoArray['cacheKey'];
		$cacheLoc = $infoArray['cacheLocation'];
		
		// If the number of results requested is different than default
		if(($num_show_results = intval($infoArray['numberOfResults'])) < 1)
			$num_show_results = $this->DEFAULT_SHOW_RESULTS;
                
		if ($page == 1)
			$_SESSION['EAN_hotel_results'] = array();
		
		$results = array();
		$destination_id = '';
		
			
		// Check if passed destination ID is valid
		if (preg_match('/([0-9A-F]{8})-([0-9A-F]{4})-([0-9A-F]{4})-([0-9A-F]{4})-([0-9A-F]{12})/',$destinationID))
			$destination_id = $destinationID;
			
		/* Start GEO-SEARCH
		* If no destination-ID or Hotel-ID was passed, OR if a new address WAS passed, perform
		*  a new geo-location search to get an appropriate location 
		*/
		if ((strlen($destination_id) == 0 && strlen($hotel_ids) == 0) || strlen($address) > 0){
		  // Determine destination string and perform geo-location search for a proper
		  $dest_string = strlen($address) > 0 ? $_GET['destination'] : $destination;
		  $possible_locations = $this->simpleLocationSearch($dest_string);
		  
			sleep(1); // Avoid 1-query-per-second rule

			$location_count = intval($possible_locations->LocationInfos['size']);
			
			if($location_count == 1){ // IF ONLY ONE LOCATION IS FOUND
				$destination_id = (string)$possible_locations->LocationInfos->LocationInfo->destinationId;
				$results['current_search']['city'] = (string)$possible_locations->LocationInfos->LocationInfo->city;
				$_SESSION['current_search']['destination'] = (string)$possible_locations->LocationInfos->LocationInfo->code;
			}else if($location_count > 1){ // IF MULTIPLE LOCATIONS ARE FOUND
				$results['locations'] = array();

        // Move all possible locations found into results var for further processing
				foreach ($possible_locations->LocationInfos->LocationInfo as $location){
					$results['locations'][] = array(
					'text' => str_replace(',,',',',(string)$location->code),
					'destination_id' => (string)$location->destinationId
					);
				}

        // Set the results[recoverable] var to an error message to display
				$results['recoverable'] = 'Multiple cities were found with that name';
				return $results;
				
			}else{  // NO LOCATIONS FOUND
				$results['locations'] = array();
				// Add returned XML object to results[error] var for debugging
				$results['error'] = $possible_locations;  

				return $results;
			}
		}
		// END GEO SEARCH
		
		$results['current_search']['check_in'] = $check_in;
		$results['current_search']['check_out'] = $check_out;
	
                // Start date
                $date = $check_in;
                // End date
                $end_date = $check_out;
                $id = 0;
                while (strtotime($date) <= strtotime($end_date)) {
                 
		//Start XML Search Request
		$xml[$id] = "<HotelListRequest>";
		if(isset($_SESSION['customerSessionId'])) 
                $xml[$id] .= "<customerSessionId>" . $_SESSION['customerSessionId'] . "</customerSessionId>";
                
                $dateRange[$id]['checkIn'] = $date;
		$xml[$id] .= "<arrivalDate>" . $date . "</arrivalDate>";
                
                if(strtotime(date ("m/d/Y", strtotime("+2 day", strtotime($date))) )< strtotime($end_date) ){
                $date = date ("m/d/Y", strtotime("+2 day", strtotime($date)));
                }else {
                $date = $end_date;
                }
		
                $dateRange[$id]['checkOut'] = $date;
                $xml[$id] .= "<departureDate>" . $date . "</departureDate>";
                $date = date ("m/d/Y", strtotime("+1 day", strtotime($date)));
		$xml[$id] .= "<numberOfResults>" . $num_show_results . "</numberOfResults>";
		$xml[$id] .= "<destinationId>" . $destination_id . "</destinationId>";
                
                if(!empty($infoArray['supplierType']))
			$xml[$id] .= "<supplierType>" . $infoArray['supplierType'] . "</supplierType>";
		
		if(!empty($infoArray['hotelName']))
			$xml[$id] .= "<propertyName>" . $infoArray['hotelName'] . "</propertyName>";
		
		// If property types were passed, set them
		if(is_array($infoArray['propertyTypes'])){
			$count = 0;
			$xml[$id] .= "<propertyCategory>";
			foreach($infoArray['propertyTypes'] as $key => $value)
				if(++$count == 1)
					$xml[$id] .= $value;
				else
					$xml[$id] .= ',' . $value;
			$xml[$id] .= "</propertyCategory>";
		}
		
		// If required amenities were passed
		if(is_array($infoArray['amenities'])){
			$count = 0;
			$xml[$id] .= "<amenities>";
			foreach($infoArray['amenities'] as $key => $value)
				if(++$count == 1)
					$xml[$id] .= $value;
				else
					$xml[$id] .= ',' . $value;
			$xml[$id] .= "</amenities>";
		}
		
		// If a minimum star rating is passed
		if(!empty($infoArray['minStarRating']))
			$xml[$id] .= "<minStarRating>" . $infoArray['minStarRating'] . "</minStarRating>";

    // If a hotel sorting type is set
		if(!empty($infoArray['sort']))
			$xml[$id] .= "<sort>" . $infoArray['sort'] . "</sort>";
    // If a hotel sorting type is set
		if(!empty($infoArray['searchRadius']))
			$xml[$id] .= "<searchRadius>" . $infoArray['searchRadius'] . "</searchRadius>";
		
		// Set Up Room object
		$xml[$id] .= "<RoomGroup>";
		for($i=0;$i<$rooms;$i++){
			$xml[$id] .= "<Room>";
			$xml[$id] .= "<numberOfAdults>" . $infoArray['room-'.$i.'-adult-total'] . "</numberOfAdults>";
			if(intval($infoArray['room-'.$i.'-child-total']) > 0){
				$xml[$id] .= "<numberOfChildren>" . $infoArray['room-'.$i.'-child-total'] . "</numberOfChildren>";
				$xml[$id] .= "<childAges>";
				for($j=0;$j<$infoArray['room-'.$i.'-child-total'];$j++){
					if($j == 0)
						$childAgesStr = $infoArray['room-'.$i.'-child-'.$j.'-age'];
					else
						$childAgesStr .= ','. $infoArray['room-'.$i.'-child-'.$j.'-age'];
				}
				$xml[$id] .= $childAgesStr;
				$xml[$id] .= "</childAges>";
			}
			if($infoArray['room-'.$i.'-bedType'])
				$xml[$id] .= "<bedTypeId>".$infoArray['room-'.$i.'-bedType']."</bedTypeId>";
			if($infoArray['room-'.$i.'-smokingPreference'])
				$xml[$id] .= "<smokingPreference>".$infoArray['room-'.$i.'-smokingPreference']."</smokingPreference>";
				
			$xml[$id] .= "</Room>";
		}
			$xml[$id] .= "</RoomGroup>";
			$xml[$id] .= "</HotelListRequest>";
                        $id++;
            } //loop end
                        $id--;
//                        echo '<pre>';
//                        print_r($xml);
//                        
//                        echo '</pre>';
//                        die();
                        // Make XML Request to Expedia Servers
			$search_results = $this->make_xml_request_multi($id,'list', $xml);

                        
                        foreach ($search_results as $key => $value) {
                            
                        
                            // Check if a Recoverable error was returned from expedia
                            if($value->EanWsError->handling == 'RECOVERABLE'){
                                            $results['recoverable'] = (string)$value->EanWsError->verboseMessage;
                                            return $results;
                            }

                            // Check if a unrecoverable error was returned from expedia
                            if($value->EanWsError->handling == 'UNRECOVERABLE'){
                                            $results['error'] = (string)$value->EanWsError->verboseMessage;
                                            return $results;
                            }

                            // Check if a UNKNOWN i.e no redord founderror was returned from expedia
                            if($value->EanWsError->handling == 'UNKNOWN'){
                                            $results['error'] = (string)$value->EanWsError->verboseMessage;
                                            return $results;
                            }
                        }
			// Store the CacheKey and cacheLocation returned by expedia
			if($search_results->cacheKey){
				$cacheKey = (string)$search_results->cacheKey;
				$cacheLoc = (string)$search_results->cacheLocation;
				$_SESSION['cacheKey'] = $cacheKey;
				$_SESSION['cacheLocation'] = $cacheLoc;
			}else{
				unset($_SESSION['cacheKey']);
				unset($_SESSION['cacheLocation']);
			}
			
			$_SESSION['current_search']['city'] = $destination;
			$results['title'] = $_SESSION['current_search']['destination'];
			$_SESSION['current_search']['check_in'] = $check_in;
			$_SESSION['current_search']['check_out'] = $check_out;
			
			//splited date range mearging with their hotelID retirned form API responce
                        foreach ($dateRange as $key => $value) {
//                            print_r($value) ;
                            $packageResult[$key]['hotelId'] = intval($search_results[$key]->HotelList->HotelSummary->hotelId);
                            $packageResult[$key]['name'] = strval($search_results[$key]->HotelList->HotelSummary->name);
                            $packageResult[$key]['checkIn'] = $value['checkIn'];
                            $packageResult[$key]['checkOut'] = $value['checkOut'];
                        }
//                        echo '<pre>';
//                        print_r($search_results);
//                        echo '</pre>';
//                        echo '<pre>';
//                        print_r($packageResult);
//                        echo '</pre>';
                        
                        //selecting same hotels for diffrent dates START--->
                        
                        foreach ($packageResult as $key => $value) {
                           if($packageResult[$key]['hotelId'] == $packageResult[$key+1]['hotelId'])
                           {
                             $packageResult[$key]['checkOut'] =  $packageResult[$key+1]['checkOut'];
                             $packageResult[$key+1]['checkIn'] = $packageResult[$key]['checkIn'];
                            //array_splice($packageResult, $key+1, 1);
                             //unset($packageResult[$key+1]);
                             //$removeIndex[] = $key+1;
                           }
                        }
//                        echo '<pre>';
//                        echo 'echo after sorting<br>';
//                        print_r($packageResult);
//                        echo '</pre>';
                        $removeIndex  = array();
                        foreach ($packageResult as $key => $value) {
                          if(($packageResult[$key]['hotelId'] == $packageResult[$key+1]['hotelId']) && ($packageResult[$key]['checkIn'] == $packageResult[$key+1]['checkIn']))
                           {
                             
                            //array_splice($packageResult, $key, 1);
                             //unset($packageResult[$key]);
                             $removeIndex[] = $key;
                           } 
                        }
                        foreach ($removeIndex as $key => $value) {
                            unset($packageResult[$value]);
                        }
//                        echo '<pre>';
//                        echo 'echo after slicing<br>';
//                        print_r($removeIndex);
//                        print_r($packageResult);
//                        echo '</pre>';

                        //selecting same hotels for diffrent dates END---X
                        $results['packageResult'] = array_values($packageResult);
                        $results['hotels'] = $search_results;
//                        echo '<pre>';
//                        print_r($packageResult);
//                        echo '</pre>';
//                        die();
			return $results;
	}
        public function getRooms($infoArray,$packageResult){
		$destination = $infoArray['city'];
		//$check_in = $infoArray['checkIn'];
		//$check_out = $infoArray['checkOut'];
		$rooms = $infoArray['numberOfRooms'];
		//$hotel_id = $infoArray['hotel_id'];
	
    // Check if the checkin and checkout dates are valid dates
//	  if(!preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/', $check_in) 
//	  || !preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/[0-9]{4}$/', $check_out)){
//		  $results['error'] = "Your dates could not be validated";
//		  print "error 2";
//		  return $results;
//	  }
	
    // Begin defining the XML request
    foreach ($packageResult as $key => $value) {
        $hotel_id = $value['hotelId'];
        $check_in = $value['checkIn'];
        $check_out = $value['checkOut'];
    
		$xml[$key] = '<HotelRoomAvailabilityRequest>';
		$xml[$key] .= '<hotelId>'. $hotel_id .'</hotelId>';
		$xml[$key] .= '<arrivalDate>'. $check_in .'</arrivalDate>';
		$xml[$key] .= '<departureDate>'. $check_out .'</departureDate>';
		$xml[$key] .= '<numberOfBedRooms>'. $rooms .'</numberOfBedRooms>';
		$xml[$key] .= '<supplierType>'.$infoArray['supplierType'].'</supplierType>';
                

		//if($infoArray['supplierType'])
		//	$xml[$key] .= '<supplierType>'.$infoArray['supplierType'].'</supplierType>';

    // If optional rateKey is passed in arg array
		if($infoArray['rateKey'])
			$xml[$key] .= '<rateKey>' . $infoArray['rateKey'] . '</rateKey>';
		
    // By default include hotel details
		$xml[$key] .= '<includeDetails>true</includeDetails>';
		
    // If optional rateCode or roomTypeCode is passed in arg array
		if($infoArray['rateCode'])
			$xml[$key] .= '<rateCode>'.$infoArray['rateCode'].'</rateCode>';
		if($infoArray['roomTypeCode'])
			$xml[$key] .= '<roomTypeCode>'.$infoArray['roomTypeCode'].'</roomTypeCode>';
		
		// Set Up Room object
		$xml[$key] .= '<RoomGroup>';
			for($i=0;$i<$rooms;$i++){
				$xml[$key] .= "<Room>";
				$xml[$key] .= "<numberOfAdults>" . $infoArray['room-'.$i.'-adult-total'] . "</numberOfAdults>";
				if(intval($infoArray['room-'.$i.'-child-total']) > 0){
					$xml[$key] .= "<numberOfChildren>" . $infoArray['room-'.$i.'-child-total'] . "</numberOfChildren>";
					$xml[$key] .= "<childAges>";
					for($j=0;$j<$infoArray['room-'.$i.'-child-total'];$j++){
						if($j == 0)
							$childAgesStr = $infoArray['room-'.$i.'-child-'.$j.'-age'];
						else
							$childAgesStr .= ','. $infoArray['room-'.$i.'-child-'.$j.'-age'];
					}
					$xml[$key] .= $childAgesStr;
					$xml[$key] .= "</childAges>";
				}
				if($infoArray['room-'.$i.'-bedType'])
					$xml[$key] .= "<bedTypeId>".$infoArray['room-'.$i.'-bedType']."</bedTypeId>";
				if($infoArray['room-'.$i.'-smokingPreference'])
					$xml[$key] .= "<smokingPreference>".$infoArray['room-'.$i.'-smokingPreference']."</smokingPreference>";
				
				$xml[$key] .= "</Room>";
			}
		$xml[$key] .= '</RoomGroup>';
		//-- Room Obj
		
		$xml[$key] .= '</HotelRoomAvailabilityRequest>';
    }
		$results = $this->make_xml_request_multi($key,'avail', $xml);
		return $results;
	}
}
