<?php

class wp_v_enqueue {

    private $shortcode_instance;

    public function __construct($shortcode_instance) {

        $this->shortcode_instance = $shortcode_instance;

        //enqueue CSS
        add_action('wp_enqueue_scripts', [$this, 'enqueue_wp_v_my_style']);

        //enqueueu JS
        add_action('wp_enqueue_scripts', [$this, 'enqueue_wp_v_script']);

    }

     /**
     * Enqueue Script
     *
     * Appends CSS to webpage in the head section
     *
     * @since 1.0.0
     *
     * @return mixed void
     */
    public function enqueue_wp_v_my_style() {
        wp_enqueue_style('wp_v_style', plugins_url('../assets/css/style.css', __FILE__), [], '1.1', 'all');

    }

    /**
     * Enqueue Script
     *
     * Appends script tag to webpage in the head section
     *
     * @since 1.0.0
     *
     * @return mixed void
     */
    public function enqueue_wp_v_script() {
        wp_enqueue_script('wp_v_script', plugins_url('../assets/js/script.js', __FILE__), [], '1.1', true);

        // Localize data to pass to the script
        $localized_data = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'type' => $this->shortcode_instance->type,
            'version' => $this->shortcode_instance->version,
            'color' => $this->shortcode_instance->color,
        ];
        
        wp_localize_script('wp_v_script', 'my_script_data', $localized_data);
    
    }

}