<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'This plugin requires WordPress' );
}
?>

    <h2><?php echo __( $this->instance_name . ' Settings', $this->instance_id ) ?></h2>

    <div class="rbp-container">

        <div class="rbp-alert">

            <div class="rbp-right-block">
                <img class="rbp-logo"
                 src="<?php echo esc_url(plugins_url('/images/curatorcrowd.png', __FILE__))?>"
                 alt="CuratorCrowd - Your traffic & engagement platform">
            </div>

            <div>
                <div>See usage stats, download co-registration data, and customize your plugins in
                    the AHM Console for CuratorCrowd.</div>
                <div style="margin-top:1.5rem">
                    <a href="https://console.americanhometownmedia.com" class="button button-primary" target="_blank">Open AHM Console in New Window</a>
                </div>
            </div>

        </div>

        <div class="rbp-alert">
            <?php include(plugin_dir_path( __FILE__ ) . '_inc/widget-placement.php'); ?>
        </div>

        <div class="rbp-alert">
            <?php include(plugin_dir_path( __FILE__ ) . '_inc/site-verification.php'); ?>
        </div>

        <div class="rbp-alert">
            <?php include(plugin_dir_path( __FILE__ ) . '_inc/termsofuse.php'); ?>
        </div>

        <div class="rbp-alert rpb-small">Note: Because the Recipe Box Plugin™ is cloud-based, it only works with
            publicly accessible websites.  WordPress instances running locally (e.g. localhost or 127.0.0.1) are not
            compatible.</div>


    </div>
