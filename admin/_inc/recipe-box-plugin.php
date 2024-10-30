<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'This plugin requires WordPress' );
}
?>
<div>
    <h3>Recipe Box Plugin</h3>

    <form name="curatorcrowd-settings-form" id="curatorcrowd-settings-form" method="post" action="">

        <?php if ( $this->getEnabled() ) { ?>

            <p><strong>Congratulations!</strong> The Recipe Box Plugin is active on your site. Go check it out, you rock star!</p>
            <p class="submit">
                <input type="submit" name="<?php echo esc_attr($disable)?>" id="<?php echo esc_attr($disable)?>" class="button button-large button-secondary" value="<?php esc_attr_e('Disable Recipe Box Plugin') ?>" />
            </p>

        <?php } else { ?>

            <p>Enabling the Recipe Box Plugin is a click away!  After you read and agree to the terms of use, the
            Recipe Box Plugin tab will show on your site.</p>

            <?php include(plugin_dir_path(__FILE__) . 'termsofuse.php'); ?>

            <p class="submit">
                <input type="submit" name="<?php echo esc_attr($enable)?>" id="<?php echo esc_attr($enable)?>" class="button button-large button-primary" value="<?php esc_attr_e('I Agree - Enable Recipe Box Plugin') ?>" />
            </p>

        <?php } ?>

    </form>
</div>