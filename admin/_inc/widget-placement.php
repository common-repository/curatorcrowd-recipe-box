<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'This plugin requires WordPress' );
}
?>
<div style="width:100%">

    <h3>Tab Placement on Page</h3>

    <form name="curatorcrowd-placement-form"
          id="curatorcrowd-settings-form"
          method="post" action="">
        <?php foreach($placement_options as $key => $option) {?>
            <div>
                <label>
                    <input type="radio"
                           name="<?php echo esc_attr($placement); ?>"
                           id="<?php echo esc_attr($placement . '-top'); ?>"
                           <?php if ($key===$top_value) { ?>checked="checked"<?php } ?>
                           value="<?php esc_attr_e($key) ?>">
                    <?php esc_attr_e($option) ?>
                </label>
            </div>
        <?php } ?>

        <div>
            <br>
            <input type="submit"
                   name="<?php echo esc_attr($placement_button); ?>"
                   id="<?php echo esc_attr($placement_button); ?>"
                   class="button button-primary"
                   value="<?php esc_attr_e('Save Placement') ?>" />
        </div>
    </form>

</div>