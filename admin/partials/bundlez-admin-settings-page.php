<div class="wrap">
    <h1>Bundlez Settings</h1>
    <hr>
    <form method="post" action="options.php">
        <?php settings_fields( 'bundlez_options_group' ); ?>
        <?php do_settings_sections( 'bundlez_options_group' ); ?>
        <h3>Conquer Maths</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Memberpress URL</th>
                <td><input class="bundlez-text-input" type="text" id="bundlez_cm_memberpress_url" name="bundlez_cm_memberpress_url" value="<?php echo esc_attr(get_option('bundlez_cm_memberpress_url')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Memberpress API</th>
                <td><input class="bundlez-text-input" type="password" id="bundlez_cm_memberpress_api" name="bundlez_cm_memberpress_api" value="<?php echo esc_attr(get_option('bundlez_cm_memberpress_api')); ?>" /></td>
            </tr>           
        </table>

        <hr>

        <h3>Conquer Computing</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Memberpress URL</th>
                <td><input class="bundlez-text-input" type="text" id="bundlez_cc_memberpress_url" name="bundlez_cc_memberpress_url" value="<?php echo esc_attr(get_option('bundlez_cc_memberpress_url')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Memberpress API</th>
                <td><input class="bundlez-text-input" type="password" id="bundlez_cc_memberpress_api" name="bundlez_cc_memberpress_api" value="<?php echo esc_attr(get_option('bundlez_cc_memberpress_api')); ?>" /></td>
            </tr>
        </table>

        <hr>
        <?php  submit_button(); ?>
    </form>
</div>