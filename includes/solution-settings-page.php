<?php
class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'My Settings',
            'manage_options',
            'my-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h1>WP Solutions</h1>

---


<script>
jQuery(document).ready(function(){
  // Smart Wizard
  jQuery('#smartwizard').smartWizard({
    onFinish:onFinishCallback
  });

  function onFinishCallback(objs, context){
    if(validateAllSteps()){
      jQuery('#wizzard-form').submit(function(event){
        event.preventDefault();

        var data = {
          'action': 'my_wizzard_ajax',
          // form data
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert('You are a wizzard ' + response);
        });
      });
    }
  }
});
</script>











<?php add_thickbox(); ?>

<script type="text/javascript">

//TB_height = jQuery(window).height();

</script>
<div id="my-content-id" style="display:none;">
  <div id="smartwizard">
      <ul class="nav">
         <li>
             <a class="nav-link" href="#step-1">
                Depandancies
             </a>
         </li>
         <li>
             <a class="nav-link" href="#step-2">
                Options
             </a>
         </li>
         <li>
             <a class="nav-link" href="#step-3">
                documentation
             </a>
         </li>
         <li>
             <a class="nav-link" href="#step-4">
                what else
             </a>
         </li>
      </ul>

      <div class="tab-content">
         <div id="step-1" class="tab-pane" role="tabpanel">
            Step content
         </div>
         <div id="step-2" class="tab-pane" role="tabpanel">
            Step content
         </div>
         <div id="step-3" class="tab-pane" role="tabpanel">
            Step content
         </div>
         <div id="step-4" class="tab-pane" role="tabpanel">
            Step content
         </div>
      </div>
  </div>
</div>

<a href="#TB_inline?&inlineId=my-content-id" class="thickbox">View my inline content!</a>
---


            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'my-setting-admin' );
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
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title
            array( $this, 'id_number_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'title',
            'Title',
            array( $this, 'title_callback' ),
            'my-setting-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
}

if( is_admin() ) {
  $my_settings_page = new MySettingsPage();
}



// wizzard

add_action( 'wp_ajax_my_wizzard_ajax', 'my_wizzard_ajax' );
add_action( 'wp_ajax_nopriv_my_wizzard_ajax', 'my_wizzard_ajax' ); // if available to non logged-in users

function my_wizzard_ajax() {

  // nonce check

  // capabilities check

  // required data exists checks, get data from $_POST super global variable

  if ( $all_good ) {

    // do something
    // wp_insert_post
    // wp_update_post
    // wp_update_meta
    // wp_update_user
    // update_user_meta

    wp_send_json_success( 'Harry' );

  } else {

    wp_send_json_error( 'Rincewind' );

  }

}

/**
 * Enqueue a script in the WordPress admin on edit.php.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function wpdocs_selectively_enqueue_admin_script( $hook ) {
    wp_enqueue_script( 'jquery-smartwizard-js', TKWPS_ASSETS . '/jquery-smartwizard/js/jquery.smartWizard.js', array(), '1.0' );
    wp_enqueue_style( 'jquery-smartwizard-', TKWPS_ASSETS . '/jquery-smartwizard/css/smart_wizard_all.css');
}
add_action( 'admin_init', 'wpdocs_selectively_enqueue_admin_script' );
