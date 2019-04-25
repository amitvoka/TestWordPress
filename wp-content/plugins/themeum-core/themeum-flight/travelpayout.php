<?php

/**
* 
*/
class THM_TP_Flights
{
	private $_key = '';
	
	function __construct()
	{

		$this->_key = get_theme_mod( 'tp_api_key' );

		add_action( 'wp_ajax_get_search_id', array($this,'get_search_id') );
        add_action( 'wp_ajax_nopriv_get_search_id', array($this,'get_search_id') );

        add_action( 'wp_ajax_get_tp_search_result', array($this,'get_tp_search_result') );
        add_action( 'wp_ajax_nopriv_get_tp_search_result', array($this,'get_tp_search_result') );

        add_action( 'wp_ajax_get_flight_html', array($this,'get_flight_html') );
        add_action( 'wp_ajax_nopriv_get_flight_html', array($this,'get_flight_html') );

        add_action( 'wp_ajax_get_places_by_query', array($this,'get_places_by_query') );
        add_action( 'wp_ajax_nopriv_get_places_by_query', array($this,'get_places_by_query') );

        add_action( 'wp_ajax_get_tp_deeplink', array($this,'get_tp_deeplink') );
        add_action( 'wp_ajax_nopriv_get_tp_deeplink', array($this,'get_tp_deeplink') );
	}

	public function get_tp_deeplink()
	{
		$url = $_POST['url'];

        $request = wp_remote_get($url);

        header('Content-Type: application/json');
        echo $request['body'];

        die();
	}

	public function get_places_by_query()
    {
        $query = $_GET['query'];

        $request = wp_remote_get("http://www.jetradar.com/autocomplete/places?q={$query}");

        // var_dump($request); die();

        header('Content-Type: application/json');
        echo $request['body'];

        die();
    }

	public function get_flight_html()
    {
        $flight = $_POST['flight']['data'];
        $position = $_POST['flight']['position'];

        $flightObj =  new THM_Flight($flight, $position);

        die();
    }

	public function get_tp_search_result()
	{
		$search_id = $_POST['search_id'];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://api.travelpayouts.com/v1/flight_search_results?uuid=".$search_id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

		$headers = array();
		$headers[] = "Accept-Encoding: gzip,deflate,sdch";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		$info = curl_getinfo($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		curl_close ($ch);

		echo $result;
		die();
	}

	public function get_search_id()
	{

		if (empty($this->_key)) {
			die();
		}

		$data = $_POST['search'];

		$data['marker'] = get_theme_mod( 'tp_api_marker' );


		$signature = $this->makeSignature($data);

		$data['signature'] = $signature;
		
		$ch = curl_init();


		curl_setopt($ch, CURLOPT_URL, "http://api.travelpayouts.com/v1/flight_search");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_POST, 1);

		$headers = array();
		$headers[] = "Content-Type: application/json";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		$info = curl_getinfo($ch);

		curl_close ($ch);

		if (isset($info['http_code']) && $info['http_code'] == 200) {
			$result = json_decode($result, true);

			if (is_array($result) && !empty($result) && isset($result['search_id'])) {
				echo $result['search_id'];
			}
		}
		die();
	}

	private function recursiveSort($array)
    {
        $clonedArray = $array;
        ksort($clonedArray, SORT_NATURAL);
        foreach ($clonedArray as $key => $value) {
            if (is_array($clonedArray[$key])) {
                $clonedArray[$key] = $this->recursiveSort($clonedArray[$key]);
            }
        }
        return $clonedArray;
    }

     private function getFlatternedValues($array)
    {
        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $return = array_merge($return, $this->getFlatternedValues($value));
            } else {
                $return[] = $value;
            }
        }
        return $return;
    }

    private function makeSignature($params)
    {
        $parameters = $this->recursiveSort($params);
        $values = $this->getFlatternedValues($parameters);
        $signatureString = implode(':', $values);
        $signatureString = $this->_key . ':' . $signatureString;  
        $signature = md5($signatureString);
        return $signature;
    }
}

if (get_theme_mod( 'tp_api' )) {
	new THM_TP_Flights();
}

