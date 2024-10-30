<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'This plugin requires WordPress' );
}
?>
<div>

    <h3>Site Verification</h3>

    <h4>Add Site Owner(s)</h4>
    <ol style="margin-bottom:20px;">
        <li>
            <strong>Login</strong> to
            the <a href="https://console.americanhometownmedia.com/dashboard" target="_blank">CrowdCurator</a></li>
        <li>
            Add or select property <strong><?php echo esc_html(preg_replace('/^https?:\/\//','',get_site_url())) ?></strong>
        </li>
        <li>
            <strong>Copy &amp; paste</strong> verification code to the form below (name it whatever you want).
        </li>
        <li>
            Go back to <a href="https://console.americanhometownmedia.com/dashboard" target="_blank">CuratorCrowd</a> and
            <strong>click &quot;Verify Now.&quot;</strong>
        </li>
    </ol>

    <hr>

    <form name="curatorcrowd-settings-form" id="curatorcrowd-settings-form" method="post" action="">
        <div class="rbp-row">
            <div>
                <label>Code</label>
                <input type="text" name="<?php echo esc_attr($new_site_code); ?>" id="<?php echo esc_attr($new_site_code); ?>" maxlength="20" />
            </div>
            <div>
                <label>Name</label>
                <input type="text" name="<?php echo esc_attr($new_site_name); ?>" id="<?php echo esc_attr($new_site_name); ?>" maxlength="50" />
            </div>
            <div>
                <input type="submit" name="<?php echo esc_attr($add_site_code); ?>" id="<?php echo esc_attr($add_site_code); ?>" class="button button-primary" value="<?php esc_attr_e('Add Owner Code') ?>" />
            </div>
        </div>
    </form>

    <?php if ( isset($this->verified_sites) && is_array($this->verified_sites)) { ?>
        <?php foreach ($this->verified_sites as $index=>$site) { ?>
            <div class="rbp-row">
                <div><strong><?php echo esc_html($site['code']) ?></strong></div>
                <div><?php echo esc_html($site['name']) ?></div>
                <div>
                    <form name="curatorcrowd-settings-form" id="curatorcrowd-settings-form" method="post" action="">
                        <input type="hidden" name="<?php echo esc_attr($remove_site_index) ?>" id="<?php echo esc_attr($remove_site_index .'_' . $site['code'])?>" value="<?php echo esc_html($index)?>" />
                        <input type="hidden" name="<?php echo esc_attr($remove_site_code)?>" id="<?php echo esc_attr($remove_site_code . '_' . $site['code'])?>" value="<?php echo esc_html($site['code'])?>" />
                        <input type="submit" name="<?php echo esc_attr($remove_site_code_button); ?>" id="<?php echo esc_attr($remove_site_code_button . '_' . $site['code']); ?>" class="button button-secondary" value="<?php esc_attr_e('Remove') ?>" />
                    </form>
                </div>

            </div>
        <?php }
    }?>

</div>

