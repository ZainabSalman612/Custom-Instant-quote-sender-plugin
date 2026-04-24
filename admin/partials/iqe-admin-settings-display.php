<?php
// Settings display file
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'pricing';

if ( isset( $_GET['settings-updated'] ) ) {
    add_settings_error( 'iqe_messages', 'iqe_message', __( 'Settings Saved', 'instant-quote-estimator' ), 'updated' );
}

settings_errors( 'iqe_messages' );
?>

<div class="wrap">
    <h1>Quote Estimator Settings</h1>
    
    <h2 class="nav-tab-wrapper">
        <a href="?page=iqe_settings&tab=pricing" class="nav-tab <?php echo $active_tab == 'pricing' ? 'nav-tab-active' : ''; ?>">Pricing</a>
        <a href="?page=iqe_settings&tab=design" class="nav-tab <?php echo $active_tab == 'design' ? 'nav-tab-active' : ''; ?>">Design & Styling</a>
        <a href="?page=iqe_settings&tab=emails" class="nav-tab <?php echo $active_tab == 'emails' ? 'nav-tab-active' : ''; ?>">Emails</a>
    </h2>

    <form method="post" action="options.php">
        <?php
        if ( $active_tab == 'pricing' ) {
            settings_fields( 'iqe_pricing_options_group' );
            do_settings_sections( 'iqe_pricing_options_group' );
            $pricing_opts = get_option( 'iqe_pricing_options' );
            if (!$pricing_opts) $pricing_opts = array();
            ?>
            <h3>Base Rates (per m²)</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Driveway Base Price</th>
                    <td><input type="number" name="iqe_pricing_options[driveway_base]" value="<?php echo isset($pricing_opts['driveway_base']) ? esc_attr($pricing_opts['driveway_base']) : '50'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Patio - Natural Stone Base Price</th>
                    <td><input type="number" name="iqe_pricing_options[patio_natural]" value="<?php echo isset($pricing_opts['patio_natural']) ? esc_attr($pricing_opts['patio_natural']) : '70'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Patio - Porcelain Multiplier (%)</th>
                    <td><input type="number" name="iqe_pricing_options[patio_porcelain_mult]" value="<?php echo isset($pricing_opts['patio_porcelain_mult']) ? esc_attr($pricing_opts['patio_porcelain_mult']) : '15'; ?>" class="regular-text"> <p class="description">Added to the natural stone price (e.g. 15 for 15%)</p></td>
                </tr>
                <tr>
                    <th scope="row">Garden Renovation - Basic Refresh</th>
                    <td><input type="number" name="iqe_pricing_options[garden_basic]" value="<?php echo isset($pricing_opts['garden_basic']) ? esc_attr($pricing_opts['garden_basic']) : '40'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Garden Renovation - Full Renovation</th>
                    <td><input type="number" name="iqe_pricing_options[garden_full]" value="<?php echo isset($pricing_opts['garden_full']) ? esc_attr($pricing_opts['garden_full']) : '90'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Garden Renovation - High End</th>
                    <td><input type="number" name="iqe_pricing_options[garden_high]" value="<?php echo isset($pricing_opts['garden_high']) ? esc_attr($pricing_opts['garden_high']) : '150'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Manhole Charge (Flat rate per manhole)</th>
                    <td><input type="number" name="iqe_pricing_options[manhole_charge]" value="<?php echo isset($pricing_opts['manhole_charge']) ? esc_attr($pricing_opts['manhole_charge']) : '250'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Poor Access Penalty (%)</th>
                    <td><input type="number" name="iqe_pricing_options[poor_access_charge]" value="<?php echo isset($pricing_opts['poor_access_charge']) ? esc_attr($pricing_opts['poor_access_charge']) : '20'; ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php
        } elseif ( $active_tab == 'design' ) {
            settings_fields( 'iqe_design_options_group' );
            do_settings_sections( 'iqe_design_options_group' );
            $design_opts = get_option( 'iqe_design_options' );
            if (!$design_opts) $design_opts = array();
            ?>
            <h3>Colors</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Primary Color</th>
                    <td><input type="text" name="iqe_design_options[primary_color]" value="<?php echo isset($design_opts['primary_color']) ? esc_attr($design_opts['primary_color']) : '#0073aa'; ?>" class="iqe-color-picker"></td>
                </tr>
                <tr>
                    <th scope="row">Secondary Color</th>
                    <td><input type="text" name="iqe_design_options[secondary_color]" value="<?php echo isset($design_opts['secondary_color']) ? esc_attr($design_opts['secondary_color']) : '#2271b1'; ?>" class="iqe-color-picker"></td>
                </tr>
                <tr>
                    <th scope="row">Background Color</th>
                    <td><input type="text" name="iqe_design_options[bg_color]" value="<?php echo isset($design_opts['bg_color']) ? esc_attr($design_opts['bg_color']) : '#f0f0f1'; ?>" class="iqe-color-picker"></td>
                </tr>
                <tr>
                    <th scope="row">Form Background Color</th>
                    <td><input type="text" name="iqe_design_options[form_bg_color]" value="<?php echo isset($design_opts['form_bg_color']) ? esc_attr($design_opts['form_bg_color']) : '#ffffff'; ?>" class="iqe-color-picker"></td>
                </tr>
                <tr>
                    <th scope="row">Text Color</th>
                    <td><input type="text" name="iqe_design_options[text_color]" value="<?php echo isset($design_opts['text_color']) ? esc_attr($design_opts['text_color']) : '#3c434a'; ?>" class="iqe-color-picker"></td>
                </tr>
                <tr>
                    <th scope="row">Border Color</th>
                    <td><input type="text" name="iqe_design_options[border_color]" value="<?php echo isset($design_opts['border_color']) ? esc_attr($design_opts['border_color']) : '#ccd0d4'; ?>" class="iqe-color-picker"></td>
                </tr>
            </table>

            <h3>Typography</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Font Family</th>
                    <td>
                        <select name="iqe_design_options[font_family]">
                            <option value="system-ui" <?php selected(isset($design_opts['font_family']) ? $design_opts['font_family'] : '', 'system-ui'); ?>>System Default</option>
                            <option value="'Inter', sans-serif" <?php selected(isset($design_opts['font_family']) ? $design_opts['font_family'] : '', "'Inter', sans-serif"); ?>>Inter</option>
                            <option value="'Roboto', sans-serif" <?php selected(isset($design_opts['font_family']) ? $design_opts['font_family'] : '', "'Roboto', sans-serif"); ?>>Roboto</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Heading Font Size (px)</th>
                    <td><input type="number" name="iqe_design_options[heading_size]" value="<?php echo isset($design_opts['heading_size']) ? esc_attr($design_opts['heading_size']) : '24'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Body Font Size (px)</th>
                    <td><input type="number" name="iqe_design_options[body_size]" value="<?php echo isset($design_opts['body_size']) ? esc_attr($design_opts['body_size']) : '16'; ?>" class="regular-text"></td>
                </tr>
            </table>
            
            <h3>Container Design</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Border Radius (px)</th>
                    <td><input type="number" name="iqe_design_options[border_radius]" value="<?php echo isset($design_opts['border_radius']) ? esc_attr($design_opts['border_radius']) : '8'; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Max Width (px)</th>
                    <td><input type="number" name="iqe_design_options[max_width]" value="<?php echo isset($design_opts['max_width']) ? esc_attr($design_opts['max_width']) : '600'; ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php
        } elseif ( $active_tab == 'emails' ) {
            settings_fields( 'iqe_email_options_group' );
            do_settings_sections( 'iqe_email_options_group' );
            $email_opts = get_option( 'iqe_email_options' );
            if (!$email_opts) $email_opts = array();
            ?>
            <h3>Email Settings</h3>
            <table class="form-table">
                <tr>
                    <th scope="row">Admin Notification Emails (comma separated)</th>
                    <td><input type="text" name="iqe_email_options[admin_emails]" value="<?php echo isset($email_opts['admin_emails']) ? esc_attr($email_opts['admin_emails']) : get_option('admin_email'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">From Name</th>
                    <td><input type="text" name="iqe_email_options[from_name]" value="<?php echo isset($email_opts['from_name']) ? esc_attr($email_opts['from_name']) : get_option('blogname'); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">From Email</th>
                    <td><input type="email" name="iqe_email_options[from_email]" value="<?php echo isset($email_opts['from_email']) ? esc_attr($email_opts['from_email']) : get_option('admin_email'); ?>" class="regular-text"></td>
                </tr>
            </table>
            <?php
        }
        
        submit_button();
        ?>
    </form>
</div>
