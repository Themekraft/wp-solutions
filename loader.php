<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Plugin Name: WP Solutions
 * Plugin URI:  https://themekraft.com/wordpress-solutions
 * Description: Create Supported solutions exectly covering your needs and get a solide core for your buissnes.
 * Version: 0.1
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/wordpress-solutions
 * Licence: GPLv3
 * Network: false
 * Text Domain: wp-solutions
 * Domain Path: /languages
 *
 *
 * ****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA    02111-1307    USA
 *
 ****************************************************************************
 */

require_once dirname(__FILE__) . '/freemius-php-sdk-master/freemius/Freemius.php';

require_once dirname(__FILE__) . '/example.php';

add_action('admin_init', 'tkwps_redirect');

function tkwps_redirect() {
     if (get_option('tkwps_do_activation_redirect', false)) {
         delete_option('tkwps_do_activation_redirect');
         wp_redirect( admin_url( '/options-general.php?page=my-setting-admin' ) );
     }
 }

if ( !class_exists( 'TKWPS' ) ) {
    /**
     * Class TKWPS
     */
    class TKWPS
    {
        /**
         * @var string
         */
        public  $version = '0.1' ;

        /**
         * Initiate the class
         *
         * @package TKWPS
         * @since 0.1
         */
        public function __construct()
        {
            global  $wp_session ;

            register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

            $this->load_constants();
            add_action(
                'init',
                array( $this, 'init_hook' ),
                1,
                1
            );

            require_once TKWPS_INCLUDES_PATH . 'solution-settings-page.php';

            register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivation' ) );
        }

        /**
         * Defines constants needed throughout the plugin.
         *
         * These constants can be overridden in bp-custom.php or wp-config.php.
         *
         * @package TKWPS
         * @since 0.1
         */
        public function load_constants()
        {
            /**
             * Define the plugin version
             */
            define( 'TKWPS_VERSION', $this->version );
            if ( !defined( 'TKWPS_PLUGIN_URL' ) ) {
                /**
                 * Define the plugin url
                 */
                define( 'TKWPS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
            }
            if ( !defined( 'TKWPS_INSTALL_PATH' ) ) {
                /**
                 * Define the install path
                 */
                define( 'TKWPS_INSTALL_PATH', dirname( __FILE__ ) . '/' );
            }
            if ( !defined( 'TKWPS_INCLUDES_PATH' ) ) {
                /**
                 * Define the include path
                 */
                define( 'TKWPS_INCLUDES_PATH', TKWPS_INSTALL_PATH . 'includes/' );
            }
            if ( !defined( 'TKWPS_TEMPLATE_PATH' ) ) {
                /**
                 * Define the template path
                 */
                define( 'TKWPS_TEMPLATE_PATH', TKWPS_INSTALL_PATH . 'templates/' );
            }
            if ( !defined( 'TKWPS_ADMIN_VIEW' ) ) {
                /**
                 * Define the template path
                 */
                define( 'TKWPS_ADMIN_VIEW', TKWPS_INCLUDES_PATH . 'admin/view/' );
            }
            if ( !defined( 'TKWPS_ASSETS' ) ) {
                /**
                 * Define the template path
                 */
                define( 'TKWPS_ASSETS', plugins_url( 'assets/', __FILE__ ) );
            }

            define( 'FS__API_SCOPE', 'developer' );
            define( 'FS__API_DEV_ID', 13700 );
            define( 'FS__API_PUBLIC_KEY', 'pk_d6125cf2086c92d8c3e0f027b1cb8' );
            define( 'FS__API_SECRET_KEY', 'sk_QfJceV^#Z+VlW)dEY]09oqtr<{x%X' );

        }

        /**
         * Defines TKWPS_init action
         *
         * This action fires on WP's init action and provides a way for the rest of WP,
         * as well as other dependent plugins, to hook into the loading process in an
         * orderly fashion.
         *
         * @package TKWPS
         * @since 0.1-beta
         */
        public function init_hook()
        {

            do_action( 'TKWPS_init' );

        }

        /**
         * Plugin activation
         * @since  0.1
         */
        function plugin_activation()
        {
          //exit( wp_redirect( admin_url( '/options-general.php?page=my-setting-admin' ) ) );
          $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'tkwps';
          wp_mkdir_p( $uploads_dir );
          add_option('tkwps_do_activation_redirect', true);
        }

         /**
         * Plugin deactivation
         * @since  0.1
         */
        function plugin_deactivation()
        {
          //exit( wp_redirect( admin_url( '/options-general.php?page=my-setting-admin' ) ) );

          //add_option('tkwps_do_activation_redirect', true);
        }

    }
}

function activate_tkwps_at_plugin_loader()
{
    // tkwps requires php version 5.3 or higher.

    if ( PHP_VERSION < 5.3 ) {
        add_action( 'admin_notices', 'tkwps_php_version_admin_notice' );
    } else {
        // Init tkwps.
        $GLOBALS['tkwps_new'] = new tkwps();
    }

}

activate_tkwps_at_plugin_loader();


