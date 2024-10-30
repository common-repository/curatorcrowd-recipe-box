<?php

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'This plugin requires WordPress' );
}

if ( !class_exists('CuratorCrowdRecipeBox') ) {

    /**
     * Primary Class for CuratorCrowd Recipe Box
     *
     * @category     WordPress_Plugin
     * @package      CuratorCrowdRecipeBox
     * @author       American Hometown Media, Inc.
     * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
     * @link         https://console.americanhometownmedia.com
     */

    class CuratorCrowdRecipeBox {

        /**
         * @var string $instance_id
         */
        private $instance_id   = 'curatorcrowd-recipe-box';

        /**
         * @var string $instance_name
         */
        private $instance_name = 'CuratorCrowd Recipe Box';

        /**
         * @var string $user_token
         */
        private $user_token = null;

        /**
         * @var array $settings
         */
        private $settings = array();

        /**
         * @var bool recipe box plugin is enabled
         */
        private $enabled = false;

        /**
         * @var array sites verified
         */
        private $verified_sites = [];

        /**
         * @var string $options_tab_position variable name
         */
        private $options_tab_position = 'curatorcrowd_tab_position';

        /**
         * @var string $options_recipe_box_plugin_enabled variable name
         */
        private $options_recipe_box_plugin_enabled = 'curatorcrowd_recipe_box_plugin_enabled';

        /**
         * @var string $options_verified_sites variable name
         */
        private $options_verified_sites = 'curatorcrowd_verified_sites';

        /**
         * @var string $options_recipe_box_plugin_top where tab lives from top of page
         */
        private $options_recipe_box_plugin_top = 'curatorcrowd_recipe_box_plugin_top';

        private $top_options = [
            'top' => 'Top Left',
            'middle' => 'Middle (Default)',
            'bottom' => 'Bottom Left'
        ];

        /**
         * @var string $top_default default top value
         */
        private $top_default = 'middle';

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * Returns an instance of this class.
         */
        public static function get_instance() {
            if( null === self::$instance ) {
                self::$instance = new CuratorCrowdRecipeBox();
            }
            return self::$instance;
        }

        /**
         * Initializes the plugin by setting filters, actions, and administration functions.
         */
        private function __construct() {
            $this->enabled = get_option( $this->options_recipe_box_plugin_enabled );
            $verified_sites = get_option( $this->options_verified_sites );

            if ($verified_sites) $this->verified_sites = json_decode($verified_sites, true);

            //add_action( 'wp_footer', array( $this, 'curatorCrowdAddScript' ), 100 );

            add_action( 'wp_enqueue_scripts', array( $this, 'curatorCrowdAddScript' ));
            add_action( 'wp_head', array( $this, 'curatorCrowdAddSiteMetaTags' ) , 10 );
            add_filter( 'script_loader_tag', array( $this, 'curatorCrowdAsync' ), 10, 3 );

            if ( is_admin() ) {
                add_action( 'admin_menu', array($this, 'curatorCrowdMenu') );
                add_filter( 'plugin_action_links', array($this, 'curatorCrowdActionLinks'), 10, 2 );
                add_action( 'admin_enqueue_scripts', array($this, 'curatorCrowdAdminStyles') );
            }
        }

        /**
         * Enable/Disabled Recipe Box Plugin
         *
         * @param bool $val
         * @return $this
         */
        private function setEnabled($val) {
            $this->enabled = $val ? 1 : 0;
            update_option( $this->options_recipe_box_plugin_enabled, $this->enabled );
            return $this;
        }

        /**
         * Add Site Verification Code and Name
         *
         * @param $params
         * @return $this
         */
        private function addSiteVerificationCode($params) {
            if (!isset($params['code']) || !isset($params['name'])) return $this;
            array_push($this->verified_sites, $params);
            update_option( $this->options_verified_sites, json_encode($this->verified_sites) );
            return $this;
        }

        /**
         * remove a site verification code
         *
         * @param $code
         * @return $this
         */
        private function removeSiteVerificationCode($index, $code) {
            if ( !isset( $this->verified_sites[$index] ) || $this->verified_sites[$index] === $code ) return $this;
            unset($this->verified_sites[$index]);
            update_option( $this->options_verified_sites, json_encode($this->verified_sites) );
            return $this;
        }

        private function setPlacementTop($top) {
            $top = key_exists($top, $this->top_options) ? $top : $this->top_default;
            update_option( $this->options_recipe_box_plugin_top, $top );
            return $this;
        }

        /**
         * Get Recipe Box Plugin enabled status
         * Since this is now stand-alone plugin, if plugin is active, the recipe box is enabled.
         *
         * @return bool is enabled
         */
        public function getEnabled() {
            return true; //$this->enabled;
        }

        /**
         * Create Admin Settings Link
         *
         * @param $links
         * @param $file
         * @return mixed $links
         */
        public function curatorCrowdActionLinks($links, $file){
            if ( $file == plugin_basename( CURATORCROWD_RECIPE_BOX_PLUGIN_DIR . '/curatorcrowd-recipe-box.php' ) ) {
                $support_link = '<a href="' . esc_url('https://console.americanhometownmedia.com/help') . '">' . __('Support') . '</a>';
                array_unshift($links, $support_link);

                $settings_link = '<a href="'. admin_url('options-general.php?page=' . $this->instance_id) .'">' . __('Settings') . '</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

        /**
         * Create manage options link in Plugin
         */
        public function curatorCrowdMenu() {
            // Add a new submenu under Settings:
            add_options_page(
                __($this->instance_name, $this->instance_id),
                __($this->instance_name, $this->instance_id),
                'manage_options',
                $this->instance_id,
                array($this, 'curatorCrowdSettings')
            );
        }

        /**
         * Output required script to instantiate Recipe Box Plugin on user's page
         */
        public function curatorCrowdAddScript() {
            if ( !$this->getEnabled() ) return;
            $ver = substr(time(),0, 7);
            $top = get_option( $this->options_recipe_box_plugin_top );

            switch($top) {
                case 'top':
                    wp_enqueue_script( 'curatorcrowd-vars', plugin_dir_url( __FILE__ ) . '_inc/vars_top.js', array(), CURATORCROWD_RECIPE_BOX_VERSION, true );
                    break;
                case 'bottom':
                    wp_enqueue_script( 'curatorcrowd-vars', plugin_dir_url( __FILE__ ) . '_inc/vars_bottom.js', array(), CURATORCROWD_RECIPE_BOX_VERSION, true );
                    break;
                default:
                    wp_enqueue_script( 'curatorcrowd-vars', plugin_dir_url( __FILE__ ) . '_inc/vars.js', array(), CURATORCROWD_RECIPE_BOX_VERSION, true );
            }

            wp_enqueue_script( 'curatorcrowd_pinchlet', 'https://www.justapinch.com/scripts/pinchlet.js', array('curatorcrowd-vars'), $ver, true );
        }

        public function curatorCrowdAsync( $tag, $handle ) {
            if ( 'curatorcrowd_pinchlet' != $handle ) {
                return $tag;
            }

            return str_replace( '<script', '<script async', $tag );
        }

        public function curatorCrowdAddSiteMetaTags() {
            if (!$this->verified_sites || count($this->verified_sites) === 0 ) return;

            foreach ($this->verified_sites as $site) {
                if (isset($site['code'])) {
                    if ($site['name'] === 'tcx'){
?>
<script>_snup=window._snup||{};_snup.siteid="<?=$site['code']?>";</script>
<?php
                        wp_enqueue_script( 'curatorcrowd-tcx', plugin_dir_url( __FILE__ ) . '_inc/tcx.js', array(), CURATORCROWD_RECIPE_BOX_VERSION, true );
                    } elseif ($site['name'] === 'tcx-target') {
                        ?>
<script>_snup=window._snup||{};_snup.target="<?=$site['code']?>";</script>
<?php
                    } else {
?>

<meta name="japfg-site-verification" content="<?php echo esc_attr($site['code'])?>" />

<?php
                    }
                }
            }

        }

        public function curatorCrowdAdminStyles($hook) {
            if($hook != 'settings_page_' . $this->instance_id) {
                return;
            }

            wp_enqueue_style(
                'curatorcrowd-style',
                plugins_url('/admin/_inc/cc.css', __FILE__)
            );

        }

        /**
         * Configuration Admin Page
         */
        public function curatorCrowdSettings() {
            //must check that the user has the required capability
            if (!current_user_can('manage_options'))
            {
                wp_die( __('You do not have sufficient permissions to access this page.') );
            }

            // variables for the field and option names
            $selectedTab = key_exists('selectedTab', $_GET) ? sanitize_text_field($_GET[ 'selectedTab' ]) : 'recipeBoxPlugin';
            $enable = 'curatorcrowd-recipe-box-plugin-enable';
            $disable = 'curatorcrowd-recipe-box-plugin-disable';
            $add_site_code = 'curatorcrowd-add-site-code';
            $new_site_code = 'curatorcrowd-new-site-code';
            $new_site_name = 'curatorcrowd-new-site-name';
            $remove_site_code = 'curatorcrowd-remove-site-code';
            $remove_site_index = 'curatorcrowd-remove-site-index';
            $remove_site_code_button = 'curatorcrowd-remove-site-code-button';
            $placement = 'curatorcrowd-placement-top';
            $placement_button = 'curatorcrowd-placement-button';
            $placement_options = $this->top_options;

            if ( isset($_POST[ $enable ]) ) $this->setEnabled(1);

            if ( isset($_POST[ $disable ]) ) $this->setEnabled(0);

            if (isset($_POST[ $add_site_code ])) {
                $selectedTab = 'siteVerification';

                if ( strlen($_POST[ $new_site_name ]) && strlen($_POST[ $new_site_code ]) ) {
                    $new_site_code = sanitize_text_field($_POST[ $new_site_code ]);
                    $new_site_name = sanitize_text_field($_POST[ $new_site_name ]);
                    $this->addSiteVerificationCode([
                        'code' => trim(substr($new_site_code,0, 20)),
                        'name' => trim(substr($new_site_name, 0, 50)),
                    ]);
                }
            }

            if ( isset($_POST[ $remove_site_code_button]) ) {
                $selectedTab = 'siteVerification';
                if ( strlen($_POST[ $remove_site_index ]) && strlen($_POST[ $remove_site_code ]) ) {
                    $remove_site_index = trim(substr(sanitize_text_field($_POST[ $remove_site_index ]),0,20));
                    $remove_site_code = trim(substr(sanitize_text_field($_POST[ $remove_site_code ]), 0, 20));
                    $this->removeSiteVerificationCode($remove_site_index, $remove_site_code);
                }

            }

            if ( isset($_POST[ $placement_button]) ) {
                $selectedTab = 'placement';
                $top = ( strlen($_POST[ $placement ]) && strlen($_POST[ $placement ]) )
                    ? sanitize_text_field($_POST[ $placement ])
                    : $this->top_default;
                $this->setPlacementTop($top);
            }

            $file = CURATORCROWD_RECIPE_BOX_PLUGIN_DIR . '/admin/settings.php';

            // get this here in case it was updated above.
            $top_value = get_option($this->options_recipe_box_plugin_top) ?: $this->top_default;
            include( $file );
        }

    }

}


/**
 * Instantiate the plugin class
 */
if ( class_exists( 'CuratorCrowdRecipeBox' ) ) {
    add_action( 'plugins_loaded', array( 'CuratorCrowdRecipeBox', 'get_instance' ) );
}

