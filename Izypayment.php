<?php

class Izypayment {
    protected $key;
    const END_POINT = 'https://www.izypayment.com/api/v1';
    
    public function __construct($key) {
        $this->key = $key;   
    }
    
    /**
    * Make a payment 
    *
    * @param $params array of POST parameters
    *
    */
    public function pay($params = []) {
        $data = $this->apiCurl('/pay', $params);
        $response_json = (array)json_decode($data['response'], true);
        return $response_json;
    }


    /**
     * Curl POST
     * @param  [type] $headers         [description]
     * @param  [type] $curl_postfields [description]
     * @param  [type] $url             [description]
     * @return [type]                  [description]
     */
    private function apiCurl($url, $params)
    {
        $url = self::END_POINT . $url;
        $curl = curl_init();

        $curl_params = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "key: " . $this->key
            ),
        );
        curl_setopt_array($curl, $curl_params);

        $data['response'] = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $data['status'] = $httpcode;
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $data;
        }
    }

?>
