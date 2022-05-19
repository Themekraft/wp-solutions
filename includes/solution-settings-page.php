<?php
class SolutionsSettingsPage {
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page( 'Settings Admin', 'TK Solutions', 'manage_options', 'tk-solutions-setting-admin', array( $this, 'create_admin_page' ) );

    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option( 'tk_solution_options' );
        ?>
        <div class="wrap">
            <h1>TK Solutions</h1>

---


<script>
jQuery(document).ready(function()
{

    // Smart Wizard
    jQuery('#smartwizard').smartWizard();
    var ajax_url = '<?php echo admin_url( 'admin-ajax.php' );?>';     

    jQuery(document).on("click", '#validate',  function(event)
    {            
            
            var license_number = jQuery('#wiz_license_number').val();
            var bundle_id      = jQuery('#wiz_bundle_id').val();
            var plan           = jQuery( "#select-solution option:selected" ).val();
            
            if(plan == '' && plan == '')
            {
                jQuery('.solution-error').show();
            }
            else if(license_number == '' && bundle_id == '')
            {
                jQuery('.license-error').show();
                jQuery('.bundle-error').show();
            }
            else if(license_number == '')
            {
                jQuery('.license-error').show();
            }
            else if(bundle_id == '')
            {
                jQuery('.bundle-error').show();
            }
            else
            {     
               
                jQuery('.loader').show();
                jQuery('.license-error').hide();
                jQuery('.bundle-error').hide(); 
                jQuery('.invalid-error').hide();  
                jQuery('.solution-error').hide();                       
                jQuery.ajax({
                url : ajax_url,
                data : {action: "tk_solutions_wizzard_ajax", license : license_number, bundle_id: bundle_id},
                beforeSend: function() 
                {
                    jQuery('.loader').show();   
                },
                success: function(response) 
                {
        
                    if(response.data.message == 'failed')
                    {
                        jQuery('.loader').hide();   
                        jQuery(".invalid-error").show();
                    }
                    else
                    {
                        jQuery('.loader').hide();   
                        jQuery('.license-success').show();
                        setTimeout(function () 
                        {
                            jQuery('#smartwizard').smartWizard("goToStep", 1);
                        }, 2000);
                    }
                 }
                });   
            } 
        
    });

    jQuery(document).on("click", '#go-step-one',  function(event)
    { 
        jQuery('#smartwizard').smartWizard("goToStep", 0);
    });

    // Ajax content loading with "stepContent" event
    jQuery("#smartwizard").on("stepContent", function(e, anchorObject, stepIndex, stepDirection) {
        
        var wizard_step = stepIndex;        
        if(wizard_step == 1)
        {
            
            jQuery('#smartwizard').smartWizard("loader", "show");
            var data = 
            {
              'action'   : 'wgt_packageinfo_ajax'
            };

            jQuery.post(ajax_url, data, function(response) 
            {
                jQuery('.Plugin-data').empty();
                jQuery('.Plugin-data').append(response.data.data);
                jQuery('#smartwizard').smartWizard("loader", "hide");

            }); 
             
        }

    });
    
    jQuery(document).on("click", '#install-activate',  function(event) { 

        jQuery('#smartwizard').smartWizard("loader", "show");           
        jQuery.ajax({
        url : ajax_url,
        async: false, 
        data : {action: "wgt_install_ajax"},
        beforeSend: function() {
            jQuery('#smartwizard').smartWizard("loader", "show");
        },
        success: function(response) 
        {
            if(response.data.message == 'failed') {
                jQuery('#smartwizard').smartWizard("loader", "hide");
                jQuery(".plugin-invalid").show();
            }
            else {
                jQuery('#smartwizard').smartWizard("loader", "hide");
                jQuery('.plugin-success').css('display','block');
                setTimeout(function () {
                    jQuery('#smartwizard').smartWizard("goToStep", 2);
                }, 2000);
            }
        }
        });

    });

    jQuery(document).on("click", '#go-step-two',  function(event)
    { 
        jQuery('#smartwizard').smartWizard("goToStep", 1);
    });

    jQuery(document).on("click", '#go-step-four',  function(event)
    { 
        jQuery('#smartwizard').smartWizard("goToStep", 3);
    });

window.setTimeout(function(){
    
    tb_show("", "#TB_inline?height=450&width=650&inlineId=solutions-content", "");},300); 
});
</script>

<?php add_thickbox(); ?>

<div id="solutions-content" style="display:none;">
    <div>
        <p style="text-align:center;"><img src="<?php echo dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/tk_solutions_logo.png'; ?>" alt="" width="80"></p>
    </div>

  <div id="smartwizard">
      <ul class="nav">
         <li>
             <a class="nav-link" href="#step-1">
                Dependencies
             </a>
         </li>
         <li>
             <a class="nav-link" href="#step-2">
                Options
             </a>
         </li>
         <li>
             <a class="nav-link" href="#step-3">
                Documentation
             </a>
         </li>
      </ul>

      

      <div class="tab-content">

         <div id="step-1" class="tab-pane" role="tabpanel">
            <div class="form-col">
                <p>
                    <span>Themekraft Solution: </span>
                <select id="select-solution">
                    <?php
                            $data = $this->get_bundle_data();
                            if( ! empty( $data ) ){
                                $solutions = json_decode( $data, true );
                                echo '<option value=" ">Please select</option>';
                                foreach( $solutions as $solution ){
                                    $s_name = $solution['metadata']['freemius_name'][0];
                                    $s_id   = $solution['metadata']['freemius_plugin_id'][0];
                                    $s_link = $solution['metadata']['freemius_bundle'][0];
                                    echo '<option data-bundle-id="' . $s_id . '" data-bundle-url="' . $s_link . '" value="' . str_replace( ' ', '-', strtolower( $s_name ) ) . '"> '. $s_name .'</option>';
                                }
                            }
                    ?>
                </select>
                <span class="solution-error" style="display: none;">Please Select Solution.</span>
                </p>
            </div>
            <div class="key-validate-form">
               
                <div class="form-col solution-key">
                    <input type="text" id="wiz_license_number" name="wiz_license_number" placeholder="Enter License Key" value="" />
                    <span class="license-error" style="display: none;">Please Fill License Key.</span>
                </div>

                <div class="form-col solution-key">
                    <input type="hidden" id="wiz_bundle_id" name="wiz_bundle_id" placeholder="Enter Bundle Id" value="" />
                    <span class="bundle-error" style="display: none;">Please Fill Bundle ID.</span>
                </div>
                <span class="invalid-error" style="display: none;">Invalid License Key.</span>
                <span class="license-success" style="display: none;">License Verified Successfully.</span>

                <div class="buy-info">
                    <p><b>Not Purchased Yet?</b> Get a new license of this Solution here:<a class="bundle-link" href="#" target="blank"></a></p>
                </div>

                <img class="loader" style="display: none" src="<?php echo home_url(); ?>/wp-includes/js/tinymce/skins/lightgray/img/loader.gif">
                <input type="button" id="validate" value="Next">
                
            </div>

         </div>

         <div id="step-2" class="tab-pane" role="tabpanel">
            <div class="Plugin-data">
                <?php
                echo '<h2>Your Package Includes Following Plugins : </h2>';
                $plugin_names = get_option( 'wgt_plugin_name' );

                if( ! empty( $plugin_names ) )
                {   
                    echo '<ul>';
                    foreach( $plugin_names as $plugin_name ) {
                        echo '<li>'.$plugin_name['name'].'</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>

            <span class="plugin-invalid" style="display: none;">Something is Wrong.Please try again.</span>
            <span class="plugin-success" style="display: none;">Plugin Installed Successfully.</span>

            <input type="button" id="go-step-one" value="Back">
            <input type="button" id="install-activate" value="Next">
         </div>

         <div id="step-3" class="tab-pane" role="tabpanel">
            <h2>Well done!</h2>
            <?php 
                $test = get_option('wgt_plugin_id');
                $data = $this->get_bundle_data();
                if( ! empty( $data ) ){
                    $solutions = json_decode( $data, true );
                    foreach( $solutions as $solution ){
                        if( $test == $solution['metadata']['freemius_plugin_id'][0] ){
                            echo '<p>Check the following link for more information: <a href="' . $solution['metadata']['documentation'][0] . '">See More</a></p>';
                            echo '<p>Or visit our <a href="' . $solution['metadata']['documentation_youtube'][0] . '">Youtube</a> channel.</p>';
                            break;
                        }
                    }
                }
                
            ?>
            <input type="button" id="go-step-two" value="Back">
            <!-- <input type="button" id="go-step-four" value="Next"> -->
         </div>

      </div>

  </div>
</div>

<a href="#TB_inline?&inlineId=solutions-content" class="thickbox">View my inline content!</a>
---



            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'tk_solutions_group' );
                do_settings_sections( 'tk-solutions-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'tk_solutions_group', // Option group
            'tk_solution_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );        

        add_settings_section(
            'setting_section_id', // ID
            'TK Solutions Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'tk-solutions-setting-admin' // Page
        );

        add_settings_field(
            'tk_solution', // ID
            'Themekraft Solution', // Title
            array( $this, 'tk_solution_callback' ), // Callback
            'tk-solutions-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'license_number', // ID
            'License Key', // Title
            array( $this, 'license_number_callback' ), // Callback
            'tk-solutions-setting-admin', // Page
            'setting_section_id', // Section
            array(
            'class'             => 'license-row',
            ),
        );

        add_settings_field(
            'bundle_id',
            'Bundle ID',
            array( $this, 'bundle_id_callback' ),
            'tk-solutions-setting-admin',
            'setting_section_id',
            array(
            'class'             => 'bundle-row',
            ),
        );


        if ( isset( $_GET['settings-updated'] ) ) {
            $wgt_keys = get_option( 'tk_solution_options');
            if( ! empty( $wgt_keys ) )
            {
                
                $names = get_option( 'wgt_plugin_name' );

                WP_Filesystem();

                if ( ! function_exists( 'get_plugins' ) ) 
                {
                    require_once ABSPATH . 'wp-admin/includes/plugin.php';
                    include_once ( ABSPATH . 'wp-admin/includes/file.php' );
                }

                $new_names = array();
                foreach( $names as $name )
                {
                    $new_names[] = $name['slug'];
                }

                $all_plugins = get_plugins();

                foreach( $all_plugins as $key => $all_plugin )
                {
                    
                    $plugin_handler = explode( '/', $key );        
                    if( in_array($plugin_handler[0], $new_names ) ) {
                        $result = activate_plugin( $key );
                    }
                    
                }

                $url = get_admin_url().'/plugins.php';
                wp_redirect( $url );
                exit;    
            }
            
        }

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        if( isset( $_POST['submit'] ) ) {
            $new_input = array();
            if( empty( $input['license_number'] ) ) {
                add_settings_error( 'tk_solution_notice', 'invalid_field_1', 'You need to enter a valid license.' );
            }

            if( ! empty( $input['license_number'] ) && ! empty( $input['bundle_id'] ) ) {
            
                $license_key = $input['license_number'];
                $plugin_id   = $input['bundle_id'];
                $api         = new Freemius_Apii(FS__API_SCOPE, FS__API_DEV_ID, FS__API_PUBLIC_KEY, FS__API_SECRET_KEY);
                $licenses    = $api->Api( "/plugins/{$plugin_id}/licenses.json?search=" . urlencode( $license_key ) ); 
                
                if( isset( $licenses->licenses ) ) {
        
                    $is_valid_license_key = ( 1 === count( $licenses->licenses ) ); 

                } else {
                $is_valid_license_key =  '';
                }
                
                
                if( $is_valid_license_key == 1 ) {
                    
                    $plan_data = $api->Api( "/plugins/{$plugin_id}/plans.json" );
                    $plugins   = $plan_data->plans[0]->plugin_plans;
                    $tgmpluginsFinal = array();
                    foreach ( $plugins as $key => $value ) {
                    
                        $plugin_tags_data   = $api->Api( "/plugins/{$key}/tags.json" );
                        $plugin_tag_id      = $plugin_tags_data->tags[0]->id;
                        $plugin_has_premium = $plugin_tags_data->tags[0]->has_premium;
                        $tkplugin_id        = $plugin_tags_data->tags[0]->plugin_id; 

                        if( ! empty( $plugin_tag_id ) && empty( $plugin_has_premium ) ) {
                            $zip = $api->GetSignedUrl( 'plugins/'.$key.'/tags/'.$plugin_tag_id.'.zip?is_premium=false' );
                            
                            $plugin_info = $api->Api( "/plugins/{$tkplugin_id}.json" );

                            $stream = stream_context_create(array( 
                            "ssl"=>array(     
                            "verify_peer"=> false,     
                            "verify_peer_name"=> false, ),
                            'http' => array(     
                            'timeout' => 30     ) )     );

                            $new_file_content = file_get_contents( $zip, 0, $stream );
                            $destination_path = trailingslashit( wp_upload_dir()['basedir'] ) . 'tkwps/';
                            $destination      = $destination_path.$plugin_info->slug .".zip";
                            $file             = fopen( $destination, "w" );
                            fputs( $file, $new_file_content );
                            fclose( $file );
                        
                            $tgmplugins           = array();
                            $tgmplugins['name']   = $plugin_info->slug;
                            $tgmplugins['slug']   = $plugin_info->slug;
                            $tgmplugins['source'] = $destination;
                        }
                        else {
                            $zip = $api->GetSignedUrl( 'plugins/'.$key.'/tags/'.$plugin_tag_id.'.zip?is_premium=true' );
                        
                            $stream = stream_context_create(array( 
                            "ssl"=>array(     
                            "verify_peer"=> false,     
                            "verify_peer_name"=> false, ),
                            'http' => array(     
                            'timeout' => 30     ) )     );

                            $new_file_content = file_get_contents( $zip, 0, $stream );
                            $destination_path = trailingslashit( wp_upload_dir()['basedir'] ) . 'tkwps/';
                            $destination      = $destination_path.$plugin_tags_data->tags[0]->premium_slug .".zip";
                            $file             = fopen( $destination, "w" );
                            fputs( $file, $new_file_content );
                            fclose( $file );
                        
                            $tgmplugins           = array();
                            $tgmplugins['name']   = $plugin_tags_data->tags[0]->premium_slug;
                            $tgmplugins['slug']   = $plugin_tags_data->tags[0]->premium_slug;
                            $tgmplugins['source'] = $destination;
                        }                    
                        
                        $tgmpluginsFinal[] = $tgmplugins;
                    }               
                    
                    update_option( 'wgt_plugin_name', $tgmpluginsFinal );
                    
                    $names = get_option( 'wgt_plugin_name' );

                    WP_Filesystem();

                    if ( ! function_exists( 'get_plugins' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                        include_once ( ABSPATH . 'wp-admin/includes/file.php' );
                    }

                    $new_names = array();
                    foreach( $names as $name ) {
                        $new_names[] = $name['slug'];
                        $path    = WP_CONTENT_DIR.'/plugins/';
                        WP_Filesystem();
                        $unzipfile = unzip_file(  $name['source'], $path );
                    }

                    if( isset( $input['license_number'] ) )
                    $new_input['license_number'] = sanitize_text_field( $input['license_number'] );

                    if( isset( $input['bundle_id'] ) )
                    $new_input['bundle_id'] = sanitize_text_field( $input['bundle_id'] );
                
                }
                else {                
                    add_settings_error( 'tk_solution_options', 'Missing value error', 'Something is Wrong.' );           
                
                }
            
            }        

            return $new_input;
        }
    }

    /**
     * Print the Section text
     */
    public function print_section_info() {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */

     public function tk_solution_callback() {
        
        echo '<select id="tk-select-solution">';
        $data = $this->get_bundle_data();
        if( ! empty( $data ) ){
            $solutions = json_decode( $data, true );
            echo '<option value=" ">Please select</option>';
            foreach( $solutions as $solution ){
                $s_name = $solution['metadata']['freemius_name'][0];
                $s_id = $solution['metadata']['freemius_plugin_id'][0];
                $s_link = $solution['metadata']['freemius_bundle'][0];
                echo '<option data-bundle-id="' . $s_id . '" data-bundle-url="' . $s_link . '" value="' . str_replace( ' ', '-', strtolower( $s_name ) ) . '"> '. $s_name .'</option>';
            }
        }
        echo '</select>';
        
        
    }

    public function license_number_callback() {
        
        printf(
            '<input type="text" id="license_number" name="tk_solution_options[license_number]" value="%s" />',
            isset( $this->options['license_number'] ) ? esc_attr( $this->options['license_number']) : ''
        );
        echo '<div class="buy-text"><p><b>Not Purchased Yet?</b> Get a new license of this Solution here:<a class="bundle-link" href="#" target="blank"></a></p></div>';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function bundle_id_callback() {
        
        printf(
            '<input type="text" id="bundle_id" name="tk_solution_options[bundle_id]" value="%s" />',
            isset( $this->options['bundle_id'] ) ? esc_attr( $this->options['bundle_id']) : ''
        );
    
    }

    public function get_bundle_data(){
        $test = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/tk_solutions_logo.png';
        $url='https://themekraft.com/tk-solutions.json';
        $ch = curl_init();
        $timeout = 10;
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        $data = curl_exec( $ch );
        $response_info = curl_getinfo( $ch );
        curl_close( $ch );
        if ( isset( $response_info['http_code'] ) && $response_info['http_code'] === 200 ) {
            return $data;
        } else{
            return false;
        }
    }
}

if( is_admin() ) {
  $solutions_settings_page = new SolutionsSettingsPage();
}



// wizzard

add_action( 'wp_ajax_tk_solutions_wizzard_ajax', 'tk_solutions_wizzard_ajax' );
add_action( 'wp_ajax_nopriv_tk_solutions_wizzard_ajax', 'tk_solutions_wizzard_ajax' ); // if available to non logged-in users

function tk_solutions_wizzard_ajax() {
  
  $license   = $_REQUEST['license'];
  $plugin_id = $_REQUEST['bundle_id'];

  $api         = new Freemius_Apii( FS__API_SCOPE, FS__API_DEV_ID, FS__API_PUBLIC_KEY, FS__API_SECRET_KEY );
  $licenses    = $api->Api( "/plugins/{$plugin_id}/licenses.json?search=" . urlencode( $license ) );

  if( isset( $licenses->licenses ) ) {
     $is_valid_license_key = ( 1 === count( $licenses->licenses ) );    
  }
  else {
    $is_valid_license_key =  '';
  }

  if ( $is_valid_license_key == 1 ) {

    $plan_data = $api->Api( "/plugins/{$plugin_id}/plans.json" );
    $plugins   = $plan_data->plans[0]->plugin_plans;
    $tgmpluginsFinal = array();
    foreach ( $plugins as $key => $value ) {
       
        $plugin_tags_data   = $api->Api("/plugins/{$key}/tags.json");
        $plugin_tag_id      = $plugin_tags_data->tags[0]->id;
        $plugin_has_premium = $plugin_tags_data->tags[0]->has_premium;
        $tkplugin_id        = $plugin_tags_data->tags[0]->plugin_id; 

        if( ! empty( $plugin_tag_id ) && empty( $plugin_has_premium ) ) {
            $zip = $api->GetSignedUrl( 'plugins/'.$key.'/tags/'.$plugin_tag_id.'.zip?is_premium=false' );
            
            $plugin_info = $api->Api( "/plugins/{$tkplugin_id}.json" );

            $stream = stream_context_create(array( 
            "ssl"=>array(     
            "verify_peer"=> false,     
            "verify_peer_name"=> false, ),
            'http' => array(     
            'timeout' => 30     ) ) );

            $new_file_content = file_get_contents( $zip, 0, $stream );
            $destination_path = trailingslashit( wp_upload_dir()['basedir'] ) . 'tkwps/';
            $destination      = $destination_path.$plugin_info->slug .".zip";
            $file             = fopen( $destination, "w" );
            fputs( $file, $new_file_content );
            fclose( $file );
           
            $tgmplugins           = array();
            $tgmplugins['name']   = $plugin_info->slug;
            $tgmplugins['slug']   = $plugin_info->slug;
            $tgmplugins['source'] = $destination;
        }
        else {
            $zip = $api->GetSignedUrl( 'plugins/'.$key.'/tags/'.$plugin_tag_id.'.zip?is_premium=true' );
           
            $stream = stream_context_create( array( 
            "ssl"=>array(     
            "verify_peer"=> false,     
            "verify_peer_name"=> false, ),
            'http' => array(     
            'timeout' => 30     ) )     );

            $new_file_content = file_get_contents( $zip, 0, $stream );
            $destination_path = trailingslashit( wp_upload_dir()['basedir'] ) . 'tkwps/';
            $destination      = $destination_path.$plugin_tags_data->tags[0]->premium_slug .".zip";
            $file             = fopen($destination, "w");
            fputs( $file, $new_file_content );
            fclose( $file );
           
            $tgmplugins           = array();
            $tgmplugins['name']   = $plugin_tags_data->tags[0]->premium_slug;
            $tgmplugins['slug']   = $plugin_tags_data->tags[0]->premium_slug;
            $tgmplugins['source'] = $destination;
        }                    
        
        $tgmpluginsFinal[] = $tgmplugins;
    }     
    
    update_option( 'wgt_plugin_name', $tgmpluginsFinal );
    update_option( 'wgt_plugin_id', $plugin_id );

    $return = array(
        'message' => __( 'Success' )
    );

    wp_send_json_success( $return );

  } else {

    $return = array(
        'message' => __( 'failed' )
    );
    wp_send_json_error( $return );

  }

}


add_action( 'wp_ajax_wgt_install_ajax', 'wgt_install_ajax' );
add_action( 'wp_ajax_nopriv_wgt_install_ajax', 'wgt_install_ajax' ); 
function wgt_install_ajax() {

    WP_Filesystem();

    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        include_once ( ABSPATH . 'wp-admin/includes/file.php' );
    }

    $names = get_option( 'wgt_plugin_name' );

    if( empty( $names ) ) {
        $return = array(
        'message' => __( 'Failed')
        );
    } else {
        $new_names = array();
        foreach( $names as $name ) {
            $new_names[] = $name['slug'];
            $path    = WP_CONTENT_DIR.'/plugins/';
            WP_Filesystem();
            $unzipfile = unzip_file(  $name['source'], $path );
        }

        $all_plugins = get_plugins();

        foreach( $all_plugins as $key => $all_plugin ) {
            
            $plugin_handler = explode( '/', $key );        
            if( in_array( $plugin_handler[0], $new_names ) ) {
                $result = activate_plugin( $key );
            }
            
        }
        $return = array(
        'message' => __( 'Success')
        );
    }    

    wp_send_json_success( $return );   

}

add_action( 'wp_ajax_wgt_packageinfo_ajax', 'wgt_packageinfo_ajax' );
add_action( 'wp_ajax_nopriv_wgt_packageinfo_ajax', 'wgt_packageinfo_ajax' ); 
function wgt_packageinfo_ajax() 
{
    
    $package_data = '';
    $package_data.='<h2>Your Package Includes Following Plugins : </h2>';
    $plugin_names = get_option( 'wgt_plugin_name' );

    if(!empty($plugin_names)) {   
        $package_data.= '<ul>';
        foreach( $plugin_names as $plugin_name ) {
            $package_data.= '<li>'.$plugin_name['name'].'</li>';
        }
        $package_data.= '</ul>';
    }
    
    $return = array(
        'data' => $package_data
    );

    wp_send_json_success( $return );   

}
/**
 * Enqueue a script in the WordPress admin on edit.php.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function wpdocs_selectively_enqueue_admin_script( $hook ) {
    wp_enqueue_script( 'jquery-smartwizard-js', TKWPS_ASSETS . '/jquery-smartwizard/js/jquery.smartWizard.js', array(), '1.0' );
    wp_enqueue_style( 'jquery-smartwizard-', TKWPS_ASSETS . '/jquery-smartwizard/css/smart_wizard_all.css' );
}
add_action( 'admin_init', 'wpdocs_selectively_enqueue_admin_script' );




