<?php

include_once( 'travelpayout.php' );

/**
 * Themeum Flight Search Plugin Init class
 */
class THM_FS_Init
{

    public $thm_flight;

    function __construct()
    {
        add_action( 'wp_enqueue_scripts', array($this,'themeum_fs_style') );

        add_action( 'wp_ajax_get_flight_data', array($this,'get_flight_data') );
        add_action( 'wp_ajax_nopriv_get_flight_data', array($this,'get_flight_data') );
        
        add_action( 'wp_ajax_get_flight_html', array($this,'get_flight_html') );
        add_action( 'wp_ajax_nopriv_get_flight_html', array($this,'get_flight_html') );

        add_action( 'wp_ajax_get_places_by_query', array($this,'get_places_by_query') );
        add_action( 'wp_ajax_nopriv_get_places_by_query', array($this,'get_places_by_query') );

        $apiKey = get_theme_mod( 'flight_api' );

        $this->thm_flight = new THM_Flight_Search($apiKey);
    }

    public function themeum_fs_style()
    {

        
    }

    public function get_places_by_query($value='')
    {
        $apiKey = get_theme_mod( 'flight_api' );
        if (!$apiKey) {
            header('Content-Type: application/json');
            echo 'false';
            die();
        }
        $query = $_GET['query'];

        $request = wp_remote_get("http://partners.api.skyscanner.net/apiservices/autosuggest/v1.0/GB/GBP/en-GB/?query={$query}&apiKey=".$apiKey);

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


    public function get_flight_data()
    {
        // var_dump(parse_str($_POST['search'])); die();

        $searchData = $_POST['search'];
        $searchData = $this->parse_str_to_array($searchData);

        $flightData = $this->thm_flight->getFlights($searchData);


        header('Content-Type: application/json');
        if (!empty($flightData)) {
            echo json_encode($flightData);
        } else {
            echo 'false';
        }
        

        die();
    }

    public function parse_str_to_array($str)
    {
        // result array
        $arr = array();

        // split on outer delimiter
        $pairs = explode('&', $str);

        foreach ($pairs as $i) {
            list($name,$value) = explode('=', $i, 2);

            // if name already exists
            if( isset($arr[$name]) ) {
                // stick multiple values into an array
                if( is_array($arr[$name]) ) {
                    $arr[$name][] = $value;
                }
                else {
                    $arr[$name] = array($arr[$name], $value);
                }
            }
            // otherwise, simply stick it in a scalar
            else {
                $arr[$name] = $value;
            }
        }

        return $arr;
    }
}

if (!get_theme_mod( 'tp_api' )) {
    new THM_FS_Init();
}


/**
 * Flight Search Class with SkyScanner API
 */
class THM_Flight_Search
{

    private $apiUri = "http://partners.api.skyscanner.net/apiservices/pricing/v1.0";

    private $apiKey = "";

    function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    public function getFlights($args)
    {
        // var_dump($args); die();
        $sessionUri = $this->createSession($args);
        $result = $this->getResult($sessionUri);

        return $result;
    }

    public function createSession($args = array())
    {
        $args['apikey'] = $this->apiKey;

        $request = wp_remote_post($this->apiUri, array(
            // 'httpversion' => 1.1,
            'headers' => array(
                'Content-Type' => "application/x-www-form-urlencoded; charset=UTF-8",
            ),
            'body' => $args
        ));

        $headers = array();

        if (isset($request['headers']) && !empty($request['headers'])) {
            $headers = $request['headers']->getAll();
        }

        $location = (isset($headers['location']) && !empty($headers['location'])) ? $headers['location'] : '';

        return $location;
    }

    public function getResult( $sessionUri = '')
    {
        $request = array();

        for ($i=0; $i < 22; $i++) {
            
            // var_dump($i);

            $request = wp_remote_get($sessionUri.'/?apikey='.$this->apiKey, array(
                'headers' => array(
                    'Accept' => "application/json",
                )
            ));
            $resp_code = isset($request['response']['code']) ? $request['response']['code'] : 404;

            

            if ($resp_code == 204 || $resp_code == 304 ||  $resp_code == 500 || ($resp_code == 200 && isset($request['body']['Status']) && $request['body']['Status'] == 'UpdatesPending')) {
                sleep(3);
            }

            if ($resp_code == 400 || $resp_code == 403 || $resp_code == 410 || $resp_code == 429 || $resp_code == 404) {
                return false;
            } elseif ( $resp_code == 200 && isset($request['body']['Status']) && $request['body']['Status'] != 'UpdatesPending' ){
                break;
            }

        }
        // var_dump(json_decode($request['body'], true));

        // die();

        $result_return = array();

        $searchData = json_decode($request['body'], true);
        $itinerariesData = $searchData['Itineraries'];
        $segmentsData = $searchData['Segments'];
        $CarriersData = $searchData['Carriers'];
        $AgentsData = $searchData['Agents'];
        $LegsData = $searchData['Legs'];
        $PlacesData = $searchData['Places'];

        // var_dump($searchData); die();

        foreach ($itinerariesData as $itinerarieData) {
            $thisItiData = $itinerarieData;


            $flightData = array();

            //////////////////////////////////////////
            // Outbound Data
            /////////////////////////////////////////
            if (isset($thisItiData['OutboundLegId']) && !empty($thisItiData['OutboundLegId'])) {
                $flightData['out'] = array();

                // Outbound ID
                $OutboundLegId = $thisItiData['OutboundLegId'];

                // Outbound Data
                $OutboundLegData = $this->getLegById($OutboundLegId, $LegsData);

                // Check this leg is Outbound
                if (isset($OutboundLegData['Directionality']) && !empty($OutboundLegData['Directionality']) && $OutboundLegData['Directionality'] == 'Outbound') {

                    // Arrival Date and Time
                    $flightData['out']['arrival'] = (isset($OutboundLegData['Arrival']) && !empty($OutboundLegData['Arrival'])) ? $OutboundLegData['Arrival'] : '';

                    // Departure Date and Time
                    $flightData['out']['departure'] = (isset($OutboundLegData['Departure']) && !empty($OutboundLegData['Departure'])) ? $OutboundLegData['Departure'] : '';

                    // Flight Duration for Outbound
                    $flightData['out']['duration'] = (isset($OutboundLegData['Duration']) && !empty($OutboundLegData['Duration'])) ? $OutboundLegData['Duration'] : '';

                    // Flight destination
                    $DestinationStationData = array();
                    if (isset($OutboundLegData['DestinationStation']) && !empty($OutboundLegData['DestinationStation'])) {
                        // Get Carrier data by ID
                        $DestinationStationData = $this->getDataById($OutboundLegData['DestinationStation'], $PlacesData);
                    }
                    $flightData['out']['destination'] = $this->processPlaceData($DestinationStationData);

                    // Flight destination
                    $OriginStationData = array();
                    if (isset($OutboundLegData['OriginStation']) && !empty($OutboundLegData['OriginStation'])) {
                        // Get Carrier data by ID
                        $OriginStationData = $this->getDataById($OutboundLegData['OriginStation'], $PlacesData);
                    }
                    $flightData['out']['origin'] = $this->processPlaceData($OriginStationData);

                    // Carriers Data
                    if(isset($OutboundLegData['Carriers']) && !empty($OutboundLegData['Carriers'])){
                        // Carriers data array
                        $flightData['out']['carriers'] = array();

                        // Carrier Data
                        foreach ($OutboundLegData['Carriers'] as $Carrier) {

                            // Get Carrier data by ID
                            $CarrierData = $this->getCarrierById($Carrier, $CarriersData);

                            // Check Carrier is not empty
                            if (!empty($CarrierData)) {
                                $flightData['out']['carriers'][] = $this->processCarrierData($CarrierData);
                            }
                        }
                    }

                    // Operating Carriers Data
                    if(isset($OutboundLegData['OperatingCarriers']) && !empty($OutboundLegData['OperatingCarriers'])){
                        // Carriers data array
                        $flightData['out']['operatingCarriers'] = array();

                        // Carrier Data
                        foreach ($OutboundLegData['OperatingCarriers'] as $Carrier) {

                            // Get Carrier data by ID
                            $CarrierData = $this->getCarrierById($Carrier, $CarriersData);

                            // Check Carrier is not empty
                            if (!empty($CarrierData)) {
                                $flightData['out']['operatingCarriers'][] = $this->processCarrierData($CarrierData);
                            }
                        }
                    }

                    // Segments Data
                    if(isset($OutboundLegData['SegmentIds']) && !empty($OutboundLegData['SegmentIds'])){
                        $flightData['out']['segments'] = array();
                        $OutboundSegmentsTemp = array();

                        foreach ($OutboundLegData['SegmentIds'] as $SegmentId) {
                            $OutboundSegmentData = $this->getLegById($SegmentId, $segmentsData);

                            if (!empty($OutboundSegmentData)) {
                                $CarrierData = array();
                                if (isset($OutboundSegmentData['OperatingCarrier']) && !empty($OutboundSegmentData['OperatingCarrier'])) {
                                    // Get Carrier data by ID
                                    $CarrierData = $this->getCarrierById($OutboundSegmentData['OperatingCarrier'], $CarriersData);
                                }

                                $DestinationStationData = array();
                                if (isset($OutboundSegmentData['DestinationStation']) && !empty($OutboundSegmentData['DestinationStation'])) {
                                    // Get Carrier data by ID
                                    $DestinationStationData = $this->getDataById($OutboundSegmentData['DestinationStation'], $PlacesData);
                                }

                                $OriginStationData = array();
                                if (isset($OutboundSegmentData['OriginStation']) && !empty($OutboundSegmentData['OriginStation'])) {
                                    // Get Carrier data by ID
                                    $OriginStationData = $this->getDataById($OutboundSegmentData['OriginStation'], $PlacesData);
                                }

                                // var_dump($DestinationStationData); die();

                                $OutboundSegmentsTemp[] = array(
                                    'arrival' => (isset($OutboundSegmentData['ArrivalDateTime']) && !empty($OutboundSegmentData['ArrivalDateTime'])) ? $OutboundSegmentData['ArrivalDateTime'] : '',
                                    'departure' => (isset($OutboundSegmentData['DepartureDateTime']) && !empty($OutboundSegmentData['DepartureDateTime'])) ? $OutboundSegmentData['DepartureDateTime'] : '',
                                    'carrier' => $this->processCarrierData($CarrierData),
                                    'duration' => (isset($OutboundSegmentData['Duration']) && !empty($OutboundSegmentData['Duration'])) ? $OutboundSegmentData['Duration'] : '',
                                    'number' => (isset($OutboundSegmentData['FlightNumber']) && !empty($OutboundSegmentData['FlightNumber'])) ? $OutboundSegmentData['FlightNumber'] : '',
                                    'destination' => $this->processPlaceData($DestinationStationData),
                                    'origin' => $this->processPlaceData($OriginStationData),
                                );
                            }
                        }

                        $flightData['out']['segments'] = $OutboundSegmentsTemp;
                    }

                    // Stops data
                    if(isset($OutboundLegData['Stops']) && !empty($OutboundLegData['Stops'])){
                        $flightData['out']['stops'] = array();
                        foreach ($OutboundLegData['Stops'] as $Stop) {
                            $StopData = $this->getDataById($Stop, $PlacesData);
                            $flightData['out']['stops'][] = $this->processPlaceData($StopData);
                        }
                    }

                }


            }


            //////////////////////////////////////////
            // Inbound Data
            /////////////////////////////////////////
            if (isset($thisItiData['InboundLegId']) && !empty($thisItiData['InboundLegId'])) {
                $flightData['in'] = array();

                // Inbound ID
                $InboundLegId = $thisItiData['InboundLegId'];

                // Inbound Data
                $InboundLegData = $this->getLegById($InboundLegId, $LegsData);

                // Check this leg is Inbound
                if (isset($InboundLegData['Directionality']) && !empty($InboundLegData['Directionality']) && $InboundLegData['Directionality'] == 'Inbound') {

                    // Arrival Date and Time
                    $flightData['in']['arrival'] = (isset($InboundLegData['Arrival']) && !empty($InboundLegData['Arrival'])) ? $InboundLegData['Arrival'] : '';

                    // Departure Date and Time
                    $flightData['in']['departure'] = (isset($InboundLegData['Departure']) && !empty($InboundLegData['Departure'])) ? $InboundLegData['Departure'] : '';

                    // Flight Duration for Inbound
                    $flightData['in']['duration'] = (isset($InboundLegData['Duration']) && !empty($InboundLegData['Duration'])) ? $InboundLegData['Duration'] : '';

                    // Flight destination
                    $DestinationStationData = array();
                    if (isset($InboundLegData['DestinationStation']) && !empty($InboundLegData['DestinationStation'])) {
                        // Get Carrier data by ID
                        $DestinationStationData = $this->getDataById($InboundLegData['DestinationStation'], $PlacesData);
                    }
                    $flightData['in']['destination'] = $this->processPlaceData($DestinationStationData);

                    // Flight destination
                    $OriginStationData = array();
                    if (isset($InboundLegData['OriginStation']) && !empty($InboundLegData['OriginStation'])) {
                        // Get Carrier data by ID
                        $OriginStationData = $this->getDataById($InboundLegData['OriginStation'], $PlacesData);
                    }
                    $flightData['in']['origin'] = $this->processPlaceData($OriginStationData);

                    // Carriers Data
                    if(isset($InboundLegData['Carriers']) && !empty($InboundLegData['Carriers'])){
                        // Carriers data array
                        $flightData['in']['carriers'] = array();

                        // Carrier Data
                        foreach ($InboundLegData['Carriers'] as $Carrier) {

                            // Get Carrier data by ID
                            $CarrierData = $this->getCarrierById($Carrier, $CarriersData);

                            // Check Carrier is not empty
                            if (!empty($CarrierData)) {
                                $flightData['in']['carriers'][] = $this->processCarrierData($CarrierData);
                            }
                        }
                    }

                    // Operating Carriers Data
                    if(isset($InboundLegData['OperatingCarriers']) && !empty($InboundLegData['OperatingCarriers'])){
                        // Carriers data array
                        $flightData['in']['operatingCarriers'] = array();

                        // Carrier Data
                        foreach ($InboundLegData['OperatingCarriers'] as $Carrier) {

                            // Get Carrier data by ID
                            $CarrierData = $this->getCarrierById($Carrier, $CarriersData);

                            // Check Carrier is not empty
                            if (!empty($CarrierData)) {
                                $flightData['in']['operatingCarriers'][] = $this->processCarrierData($CarrierData);
                            }
                        }
                    }

                    // Segments Data
                    if(isset($InboundLegData['SegmentIds']) && !empty($InboundLegData['SegmentIds'])){
                        $flightData['in']['segments'] = array();
                        $InboundSegmentsTemp = array();

                        foreach ($InboundLegData['SegmentIds'] as $SegmentId) {
                            $InboundSegmentData = $this->getLegById($SegmentId, $segmentsData);

                            if (!empty($InboundSegmentData)) {
                                $CarrierData = array();
                                if (isset($InboundSegmentData['OperatingCarrier']) && !empty($InboundSegmentData['OperatingCarrier'])) {
                                    // Get Carrier data by ID
                                    $CarrierData = $this->getCarrierById($InboundSegmentData['OperatingCarrier'], $CarriersData);
                                }

                                $DestinationStationData = array();
                                if (isset($InboundSegmentData['DestinationStation']) && !empty($InboundSegmentData['DestinationStation'])) {
                                    // Get Carrier data by ID
                                    $DestinationStationData = $this->getDataById($InboundSegmentData['DestinationStation'], $PlacesData);
                                }

                                $OriginStationData = array();
                                if (isset($InboundSegmentData['OriginStation']) && !empty($InboundSegmentData['OriginStation'])) {
                                    // Get Carrier data by ID
                                    $OriginStationData = $this->getDataById($InboundSegmentData['OriginStation'], $PlacesData);
                                }

                                $InboundSegmentsTemp[] = array(
                                    'arrival' => (isset($InboundSegmentData['ArrivalDateTime']) && !empty($InboundSegmentData['ArrivalDateTime'])) ? $InboundSegmentData['ArrivalDateTime'] : '',
                                    'departure' => (isset($InboundSegmentData['DepartureDateTime']) && !empty($InboundSegmentData['DepartureDateTime'])) ? $InboundSegmentData['DepartureDateTime'] : '',
                                    'carrier' => $this->processCarrierData($CarrierData),
                                    'duration' => (isset($InboundSegmentData['Duration']) && !empty($InboundSegmentData['Duration'])) ? $InboundSegmentData['Duration'] : '',
                                    'number' => (isset($InboundSegmentData['FlightNumber']) && !empty($InboundSegmentData['FlightNumber'])) ? $InboundSegmentData['FlightNumber'] : '',
                                    'destination' => $this->processPlaceData($DestinationStationData),
                                    'origin' => $this->processPlaceData($OriginStationData),
                                );
                            }
                        }

                        $flightData['in']['segments'] = $InboundSegmentsTemp;
                    }

                    // Stops data
                    if(isset($InboundLegData['Stops']) && !empty($InboundLegData['Stops'])){
                        $flightData['in']['stops'] = array();
                        foreach ($InboundLegData['Stops'] as $Stop) {
                            $StopData = $this->getDataById($Stop, $PlacesData);
                            $flightData['in']['stops'][] = $this->processPlaceData($StopData);
                        }
                    }

                }


            }

            // Pricing Data
            if(isset($thisItiData['PricingOptions']) && !empty($thisItiData['PricingOptions'])){
                $flightData['prices'] = array();

                foreach ($thisItiData['PricingOptions'] as $PricingOption) {
                    $priceDataTemp = array();

                    // Add Price Data
                    if (isset($PricingOption['Price']) && !empty($PricingOption['Price'])) {
                        $priceDataTemp['price'] = $PricingOption['Price'];
                    }

                    // Deeplink
                    if (isset($PricingOption['DeeplinkUrl']) && !empty($PricingOption['DeeplinkUrl'])) {
                        $priceDataTemp['link'] = $PricingOption['DeeplinkUrl'];
                    }

                    // Agents Data
                    if (isset($PricingOption['Agents']) && !empty($PricingOption['Agents'])) {
                        $priceDataTemp['agents'] = array();

                        // Agent Data
                        foreach ($PricingOption['Agents'] as $Agent) {
                            // Get Agent Data by ID
                            $agentData = $this->getAgentById($Agent, $AgentsData);

                            if (!empty($agentData)) {
                                $priceDataTemp['agents'][] = array(
                                    'name' => (isset($agentData['Name']) && !empty($agentData['Name'])) ? $agentData['Name'] : '',
                                    'logo' => (isset($agentData['ImageUrl']) && !empty($agentData['ImageUrl'])) ? $agentData['ImageUrl'] : '',
                                    'type' => (isset($agentData['Type']) && !empty($agentData['Type'])) ? $agentData['Type'] : '',
                                );
                            }

                        }

                    }

                    // Add Price Data to Flight Data
                    $flightData['prices'][] = $priceDataTemp;
                }

            }

            // $flightData['prices'] =

            $result_return[] = $flightData;

        }

        // for ($i=0; $i < 5; $i++) {
        //   var_dump($searchData['Legs'][$i]);
        // }

        // die();
        // 
        return $result_return;
    }

    public function processCarrierData($data)
    {
        $returnData = array(
            'code' => (isset($data['Code']) && !empty($data['Code'])) ? $data['Code'] : '',
            'name' => (isset($data['Name']) && !empty($data['Name'])) ? $data['Name'] : '',
            'displayCode' => (isset($data['displayCode']) && !empty($data['displayCode'])) ? $data['displayCode'] : '',
            'logo' => (isset($data['ImageUrl']) && !empty($data['ImageUrl'])) ? $data['ImageUrl'] : '',
        );

        return $returnData;

    }

    public function processPlaceData($data)
    {
        $returnData = array(
            'code' => (isset($data['Code']) && !empty($data['Code'])) ? $data['Code'] : '',
            'name' => (isset($data['Name']) && !empty($data['Name'])) ? $data['Name'] : '',
            'type' => (isset($data['Type']) && !empty($data['Type'])) ? $data['Type'] : '',
        );

        return $returnData;

    }

    public function getLegById($id, $legs)
    {
        $legData = array();

        foreach ($legs as $leg) {
            if ($leg['Id'] == $id) {
                $legData = $leg;

                break;
            }
        }

        return $legData;
    }

    public function getSegmentById($id, $segments)
    {
        $segmentData = array();

        foreach ($segments as $segment) {
            if ($segment['Id'] == $id) {
                $segmentData = $segment;

                break;
            }
        }

        return $segmentData;
    }

    public function getAgentById($id, $agents)
    {
        $agentData = array();

        foreach ($agents as $agent) {
            if ($agent['Id'] == $id) {
                $agentData = $agent;

                break;
            }
        }

        return $agentData;
    }

    public function getCarrierById($id, $carriers)
    {
        $carrierData = array();

        foreach ($carriers as $carrier) {
            if ($carrier['Id'] == $id) {
                $carrierData = $carrier;

                break;
            }
        }

        return $carrierData;
    }

    public function getDataById($id, $allData)
    {
        $returnData = array();

        foreach ($allData as $data) {
            if ($data['Id'] == $id) {
                $returnData = $data;
                break;
            }
        }

        return $returnData;
    }
}

/**
 * Single Flight
 */
class THM_Flight
{

    private $flight;
    private $position;

    private $returnFlight = false;

    function __construct($flight, $position)
    {
        $this->flight = $flight;
        $this->position = $position;

        if (isset($this->flight['in']) && !empty($this->flight['in'])) {
            $this->returnFlight = true;
        }


        $this->flightSlingeView();
    }

    public function flightSlingeView()
    {


        // Outbound Data

        ?>
        <div class="thm-flight" data-pos="<?php echo esc_attr( $this->position ); ?>">
            <div class="thm-flight-summery clearfix">
                <div class="thm-flight-segment">
                    <div class="row">
                        <?php
                            $this->flightViewOne('out');
                            $this->flightViewOne('in');
                        ?>
                    </div>
                    <div class="thm-flight-more-data">
                        <div class="thm-flight-more-data-inner clearfix">
                            <a href="#" class="thm-show-more thm-show-more-main"><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php esc_html_e('Flight Details', 'themeum-core') ?> <i class="fa fa-caret-right" aria-hidden="true"></i></a>
                            <!-- <a href="#" class="pull-right"><i class="fa fa-share-alt" aria-hidden="true"></i> <?php esc_html_e('Share', 'themeum-core') ?></a> -->
                        </div>
                    </div>
                </div>
                <div class="thm-flight-price">
                    <?php
                        $cheapPrice = $this->getCheapPrice();
                        $agent = count($cheapPrice['agents']) ? $cheapPrice['agents'][0] : array();
                        $agentName = isset($agent['name']) ? $agent['name'] : '' ;

                        $totalAgents = count($this->flight['prices']);

                    ?>
                    <?php if (get_theme_mod( 'tp_api' )): ?>
                        <div class="thm-flight-price-cheap"><?php echo $this->getCurrencySymbol($cheapPrice['currency']); ?><?php echo round($cheapPrice['price']); ?></div>
                    <?php else: ?>
                        <div class="thm-flight-price-cheap">$<?php echo round($cheapPrice['price']); ?></div>
                    <?php endif; ?>
                    <div class="thm-flight-agent-cheap"><?php echo esc_html($agentName); ?></div>
                    <?php if($totalAgents >= 2): ?>
                        <a href="#" class="thm-flight-more-price thm-show-more"><?php echo esc_html( ($totalAgents - 1) ); ?> <?php esc_html_e('More +', 'themeum-core'); ?></a>
                    <?php endif; ?>
                    <?php if (get_theme_mod( 'tp_api' )): ?>
                        <a href="<?php echo esc_url( $cheapPrice['link'] ); ?>" class="btn btn-primary tp-click-event" target="_blank"><?php esc_html_e('Book Now', 'themeum-core'); ?></a>
                    <?php else: ?>
                        <a href="<?php echo esc_url( $cheapPrice['link'] ); ?>" class="btn btn-primary"><?php esc_html_e('Book Now', 'themeum-core'); ?></a>
                    <?php endif; ?>
                </div>
                
            </div>
            <div class="thm-flight-details">
                <?php
                    $this->getFullSegmentMap('out');
                    $this->getFullSegmentMap('in');
                ?>
                <div class="thm-flight-all-prices-outer">
                    <div class="thm-flight-all-prices">
                        <?php foreach($this->flight['prices'] as $price):
                            $agent = count($price['agents']) ? $price['agents'][0] : array();
                            $agentName = isset($agent['name']) ? $agent['name'] : '' ;
                            ?>
                            <div class="thm-flight-all-price">
                                <?php if (get_theme_mod( 'tp_api' )): ?>
                                    <a class="tp-click-event" href="<?php echo esc_url( $price['link'] ); ?>"><span><?php echo esc_html($agentName); ?></span> <span><?php echo $this->getCurrencySymbol($price['currency']); ?><?php echo round($price['price']); ?></span></a>
                                <?php else: ?>
                                    <a href="<?php echo esc_url( $price['link'] ); ?>"><span><?php echo esc_html($agentName); ?></span> <span>$<?php echo round($price['price']); ?></span></a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }

    public function getCurrencySymbol( $currency_name = 'usd' )
    {
        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
        );

        $currency_name = strtoupper($currency_name);

        if (isset($currency_symbols[$currency_name])) {
            return $currency_symbols[$currency_name];
        } else {
            return $currency_symbols['USD'];
        }
    }

    public function getCheapPrice() 
    {
        $prices = $this->flight['prices'];
        $cheapPrice = array();
        $i == 0;
        foreach ($prices as $price) {
            if($i){
                if ($cheapPrice['price'] > $price['price']) {
                    $cheapPrice = $price;
                }
            } elseif ($i == 0) {
                $cheapPrice = $price;
            }
        }

        return $cheapPrice;
    }

    public function flightViewOne($type='out')
    {
        $column_class = $this->returnFlight ? 'col-md-6' : 'col-md-12';

        $main_carrier = (isset($this->flight[$type]['carriers'][0]) && !empty($this->flight[$type]['carriers'][0])) ? $this->flight[$type]['carriers'][0] : array();
        if(isset($this->flight[$type]) && !empty($this->flight[$type])):

            $logo_file = str_replace('http://s1.apideeplink.com/images/airlines/', '', $main_carrier['logo']);
            ?>
            <div class="<?php echo esc_attr($column_class); ?>">
                <div class="clearfix">
                    <?php if (get_theme_mod( 'tp_api' )): ?>
                        <img class="thm-flight-carrier-icon tp-img" src="<?php echo esc_url($logo_file); ?>" alt="<?php echo esc_attr(isset($main_carrier['name']) ? $main_carrier['name'] : ''); ?>">
                    <?php else: ?>
                        <img class="thm-flight-carrier-icon" src="http://logos.skyscnr.com/images/airlines/favicon/<?php echo esc_attr($logo_file); ?>" alt="<?php echo esc_attr(isset($main_carrier['name']) ? $main_carrier['name'] : ''); ?>">
                    <?php endif; ?>
                    <strong class="thm-flight-carrier-name"><?php echo esc_attr(isset($main_carrier['name']) ? $main_carrier['name'] : ''); ?></strong>
                    <p>
                        <?php
                        if(isset($this->flight[$type]['stops']) && !empty($this->flight[$type]['stops'])){
                            $stops_num = count($this->flight[$type]['stops']);
                            echo $stops_num;
                            echo esc_html(_n( ' Stop', ' Stops', $stops_num, 'themeum-flight' ));

                            $stops_code = array();
                            foreach ($this->flight[$type]['stops'] as $stop) {
                                $stops_code[] = $stop['code'];

                            }
                            echo " (".implode(', ', $stops_code).")";
                        } else {
                            esc_html_e('Direct', 'themeum-flight');
                        }
                        ?>

                        <?php echo $this->durationToString($this->flight[$type]['duration']); ?>
                    </p>
                </div>
                <div class="thm-flight-segment-map clearfix">
                    <?php
                    $col_size = count($this->flight[$type]['segments']);

                    $last_item_key = $col_size - 1;

                    foreach($this->flight[$type]['segments'] as $key => $segment):

                        $step_class = array();

                        $step_class[] = "thm-fsm-size-{$col_size}-1";

                        if ($col_size > 1 && $key == 0) {
                            $step_class[] = "stop-to-right";
                        }

                        if ($col_size > 1 && $key == $last_item_key) {
                            $step_class[] = "stop-to-left";
                        }

                        if ($col_size > 1 && $key != $last_item_key && $key != 0) {
                            $step_class[] = "stop-to-left";
                            $step_class[] = "stop-to-right";
                        }

                        $arrival_date = date_create($segment['arrival']);
                        $arrival_time = date_format($arrival_date, 'H:i');

                        $departure_date = date_create($segment['departure']);
                        $departure_time = date_format($departure_date, 'H:i');


                        ?>
                        <div class="thm-fs-map-step <?php echo esc_attr(implode(' ', $step_class));  echo " last-key".$key; ?>"> <!-- stop-to-left stop-to-right -->
                            <span class="thm-fs-map-step-line"></span>
                            <span class="thm-fs-map-step-origin"><?php echo esc_html($segment['origin']['code']); ?></span>
                            <span class="thm-fs-map-step-destination"><?php echo esc_html($segment['destination']['code']); ?></span>
                            <span class="thm-fs-map-step-departure"><?php echo esc_html($departure_time); ?></span>
                            <span class="thm-fs-map-step-arrival"><?php echo esc_html($arrival_time); ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                </div>

            </div>
            <?php
        endif;
    }

    public function getFullSegmentMap($type='out')
    {
        if(!isset($this->flight[$type]) && empty($this->flight[$type])){
            return;
        }
        ?>
        <div class="thm-flight-segment-map clearfix">
            <?php
            $col_size = count($this->flight[$type]['segments']);

            $last_item_key = $col_size - 1;

            foreach($this->flight[$type]['segments'] as $key => $segment):

                $step_class = array();

                $step_class[] = "thm-fsm-size-{$col_size}-1";

                if ($col_size > 1 && $key == 0) {
                    $step_class[] = "stop-to-right";
                }

                if ($col_size > 1 && $key == $last_item_key) {
                    $step_class[] = "stop-to-left";
                }

                if ($col_size > 1 && $key != $last_item_key && $key != 0) {
                    $step_class[] = "stop-to-left";
                    $step_class[] = "stop-to-right";
                }

                $arrival_date = date_create($segment['arrival']);
                $arrival_time = (count($this->flight[$type]['segments']) >= 4) ? date_format($arrival_date, 'H:i') : date_format($arrival_date, 'H:i, d F Y');

                $departure_date = date_create($segment['departure']);
                $departure_time = (count($this->flight[$type]['segments']) >= 4) ? date_format($departure_date, 'H:i') : date_format($departure_date, 'H:i, d F Y');

                $long_wait = false;
                $wait_in_str = '';

                if ($col_size > 1 && ($key == 0 || $key != $last_item_key)) {
                    $time_stamp = strtotime($this->flight[$type]['segments'][($key+1)]['departure']) - strtotime($segment['arrival']);

                    if (18000 <= $time_stamp) {
                        $long_wait = true;
                    }
                    $wait_in_str_hrs = date('H', $time_stamp);
                    $wait_in_str_min = date('i', $time_stamp);

                    $wait_in_str = $wait_in_str_hrs.' hrs '.$wait_in_str_min.' min';
                }

                $wait_cls = '';
                if ($long_wait) {
                    $wait_cls = 'thm-long-wait';
                }

                $origin_txt = (count($this->flight[$type]['segments']) >= 4) ? $segment['origin']['code'] : $segment['origin']['name'].' ('.$segment['origin']['code'].')';
                $destination_txt = (count($this->flight[$type]['segments']) >= 4) ? $segment['destination']['code'] : $segment['destination']['name'].' ('.$segment['destination']['code'].')';

                ?>
                <div class="thm-fs-map-step <?php echo esc_attr(implode(' ', $step_class));  echo " last-key".$key; ?>"> <!-- stop-to-left stop-to-right -->
                    <span class="thm-fs-map-step-line"></span>
                    <span class="thm-fs-map-step-origin"><?php echo esc_html($origin_txt); ?></span>
                    <span class="thm-fs-map-step-destination"><?php echo esc_html($destination_txt); ?></span>
                    <span class="thm-fs-map-step-departure"><?php echo esc_html($departure_time); ?></span>
                    <span class="thm-fs-map-step-arrival"><?php echo esc_html($arrival_time); ?></span>

                    <span class="thm-fs-map-step-wait <?php echo esc_html( $wait_cls ); ?>"><?php echo esc_html($wait_in_str); ?></span>
                </div>
            <?php endforeach; ?>
            
        </div>

        <?php
    }


    public function durationToString($duration)
    {
        $mins = $duration % 60;
        $hrs = ($duration - $mins) / 60;

        return $hrs.' hrs '.$mins.' mins';
    }
}






function thm_flight_cb($atts)
{
    $atts = shortcode_atts( array(
    ), $atts );



    ob_start();
    ?>
    <form id="flight-search">
        <input type="text" name="country" value="BD">
        <input type="text" name="currency" value="BDT">
        <input type="text" name="locale" value="en-US">
        <input type="text" name="originplace" value="DAC-iata">
        <input type="text" name="destinationplace" value="DEL-iata">
        <input type="text" name="outbounddate" value="2016-10-04">
        <input type="text" name="inbounddate" value="2016-10-11">
        <input type="text" name="adults" value="1">
        <input type="text" name="cabinclass" value="Economy">
        <input type="submit" name="name" value="Search">
    </form>
    <ul id="flights"></ul>
    <div class="flight-pagination"></div>
    <?php
    $output = ob_get_clean();
    return $output;
}

add_shortcode( 'thm_flight', 'thm_flight_cb' );
