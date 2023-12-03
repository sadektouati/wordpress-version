<?php
class wp_v_handle_api{

    /**
     * Call Api
     *
     * A method that fetch data from the remote API
     * and save it to wordpress native transient API storage.
     *
     * @since 1.0.0
     *
     * @return void
     */

     private function call_api(){

        // Make the API request using wp_remote_get
        $response = wp_remote_get(API_URL);

        // Check for errors
        if (is_wp_error($response)) {

            $error_message = $response->get_error_message();

            // You can log the error, display a user-friendly message, or take other actions
            error_log('API request error: ' . $error_message);
            return;

        } else {

            $data = [];
            $data['last_updated_on'] = date_i18n('Y-m-d H:i:s', current_time('timestamp'));

                if(empty(wp_remote_retrieve_body($response)['message']) == false){
                    $data['list'] = [];

                }else{
                    $data['list'] = json_decode(wp_remote_retrieve_body($response), true);

                }

            $json_data = json_encode($data);

            set_transient('wp_v_api_data', $json_data, DAY_IN_SECONDS);

        }

    }

    /**
     * Read Data
     *
     * Handles the logic of retrieving data from wordpress's transient API.
     * If the data is expired, it calls Call API methode above
     *
     * @since 1.0.0
     *
     * @return mixed Description of the return value.
     */

    public function read_data(){

        //try to read data from wp transient API
        $retrieved_data = get_transient('wp_v_api_data');

        //if data found expired or never loaded
        //call the call_api method
        if( $retrieved_data == null ){
            $this->call_api();
            $retrieved_data = get_transient('wp_v_api_data');

        }

        return json_decode($retrieved_data, true);
    }

}
