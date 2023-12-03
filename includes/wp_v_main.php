<?php
class wp_v_main {

    private $handle_api;
    private $handle_version;
    private $versions = [];

    //method map pattern so not use multiple if statements
    private $method_map = [
        'latest'    => 'latest_version',
        'validate'  => 'validate_version',
        'subversion'  => 'subversion',
        'mine'  => 'my_version',
    ];

    public function __construct($wp_v_handle_api, $wp_v_handle_version) {

        $this->handle_api = $wp_v_handle_api;

        $this->handle_version = $wp_v_handle_version;
        $this->versions = $this->handle_api->read_data();

        // Register shortcode
        add_shortcode('wordpress-version', [$this, 'wp_version_shortcode']);

        // fetch logic
        add_action('wp_ajax_manual_data_update', [$this, 'my_manual_update']);
    }


    /**
     * Version Shortcode
     *
     * Handles the HTML string to be displayed according to parameters.
     * 
     * @since 1.0.0
     *
     * @param array associative array of parameters
     * @return mixed Description of the return value.
     */
    public function wp_version_shortcode($atts) {

        //there's a shorter way to write these declarations but it requires PHP 8 
        $type = (isset($atts['type']) and key_exists($atts['type'], $this->method_map)) ? $atts['type'] : array_keys($this->method_map)[0];

        $version = isset($atts['version']) ? $atts['version'] : '';

        $no_color_attr = (isset($atts['color']) and in_array($atts['color'], ['yes', 'no'])) ? $atts['color'] : 'yes';

        if(empty($this->versions['list'])){
            return "<article class='vw_container _vw_container'>erreur de données</article>";

        } else {

            //generate the HTML
            //Call a version manipulation method depending on the type parameter
            $method_name = $this->method_map[$type];
            
            //Call appropriate method dynamically
            $string = $this->handle_version->$method_name($this->versions, $version);

            return "<article class='vw_container _vw_container' color='$no_color_attr'>
                <h2 class='vw_entete'>Version Wordpress</h2>
                <div class='vw_date'>dernière mise a jour: " . $this->versions['last_updated_on'] . " <label class='vw_mettre_a_jour _vw_mettre_a_jour'>mettre à jour</label></div>
                <div class='vw_version'>$string</div> 
                </article>";

        }
        
    }

    /**
     * Manuel Update
     *
     * Handles the update of the api data and sending the rsults to front end.
     * Returns a json object to the browser containg the HTML string generated 
     * by wp_version_shortcode
     *
     * @since 1.0.0
     *
     * @return void
     */

    public function my_manual_update() {

        //delete existing API data.
        delete_transient('wp_v_api_data');

        //reload data from the API th the server
        $this->versions = $this->handle_api->read_data();

        //sanitize the data
        $atts['type'] = sanitize_text_field($_GET['type']);
        $atts['version'] = sanitize_text_field($_GET['version']);
        $atts['color'] = sanitize_text_field($_GET['color']);

        //generate the HTML
        $html = $this->wp_version_shortcode($atts);
        $data_to_send = ['html' => $html];

        // Send the html as part of a JSON object
        wp_send_json($data_to_send);
    }

}
