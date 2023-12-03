<?php
class wp_v_handle_version{

    /**
     * Latest version
     *
     * Returns an HTML string containg the version given in parameters
     *
     * @since 1.0.0
     *
     * @param string The version number in numeric format to be inserted in HTML
     * @return String.
     */

    public function latest_version($versions){
        return "La dernière version est: <span class='vw_etat latest'>" . $versions['list'][0]['latest'] . "</span>";
    }


    /**
     * Validate Version
     *
     * Compares the version number given as parameter in the second position
     * to insecure version and outdated and latest
     * and retrurns an HTML string accordingly
     *
     * @since 1.0.0
     *
     * @param array  An nested array containg the list of wordpress versions and subversions
     * @param string A numeric version of wordpress
     * @return string An HTML string
     */

    public function validate_version($versions, $version){

        $indice_version_demandee = array_search($version, array_column($versions['list'], 'cycle'));
        $vulnerable_version_index = array_search('4.0', array_column($versions['list'], 'cycle'));

        if($indice_version_demandee === false){
            $css_class = "vw_erreur";
            $string = "Version inéxistante ou mal formée";

        }else if($indice_version_demandee === 0){
            $string = "latest";

        }elseif($indice_version_demandee >= $vulnerable_version_index){
            $string = "insecure";

        }else{
            $string = "outdated";

        }

        $css_class = $css_class ?? $string;

        return "La version ($version) est: <span class='vw_etat $css_class'>$string</span>";
        
    }

    /**
     * Subversion
     *
     * Return the list of subversions of a given numeric version as second parameter
     *
     * @since 1.0.0
     *
     * @param array  An nested array containg the list of wordpress versions and subversions
     * @param string A numeric version of wordpress
     * @return string An HTML string
     */

    public function subversion($versions, $version){

        //declare and fill a subversions array
        $subversions_table = [];
        foreach ($versions['list'] as $line) {
            if($line['cycle'] == $version) $subversions_table[] = $line;
        }

        if(empty($subversions_table)){
            return "<span class='vw_erreur'>Version mal formée ou inéxistante, corriger svp</span>";
        }

        $html = '<table><tr><th>Branch ' . $subversions_table[0]['cycle'] . '</th></tr>';
        foreach ($subversions_table as $key => $value) {
            $html .= '<tr><td>' . $value['latest'] . '</td></tr>';
        }
        $html .= '</table>';

        return $html;

    }

    /**
     * My Version
     *
     * Return my current version of wordpress
     *
     * @since 1.0.0
     *
     * @param array  An nested array containg a list of wordpress versions and subversions
     * @return string An HTML string containing the wordpress version running on the server
     */

    public function my_version($versions){

        //obtain the wordpress server version and lookup it's index in
        //the locally stored data
        $my_version = get_bloginfo('version');
        $my_version_index = array_search($my_version, array_column($versions['list'], 'latest'));
        
        $vulnerable_version_index = array_search('4.0', array_column($versions['list'], 'cycle'));
        
        if($my_version_index === false){
            $css_class = "vw_erreur";

        }else if($my_version_index === 0){
            $css_class = "latest";

        }elseif($my_version_index >= $vulnerable_version_index){
            $css_class = "insecure";

        }else{
            $css_class = "outdated";

        }
        
        return "Votre version est: <span class='vw_etat $css_class'>$my_version</span>";
        
    }

}