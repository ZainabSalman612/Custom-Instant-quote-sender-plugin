<?php
class Iqe_Public {
	
	public function enqueue_styles() {
		wp_enqueue_style( 'iqe-public-css', IQE_PLUGIN_URL . 'public/css/iqe-public.css', array(), IQE_VERSION, 'all' );
        
        // Dynamic CSS based on theme settings
        $design_opts = get_option( 'iqe_design_options' );
        if ( !empty($design_opts) ) {
            $custom_css = ".instant-quote-estimator {";
            if (!empty($design_opts['font_family'])) $custom_css .= "--iqe-font-family: {$design_opts['font_family']};";
            if (!empty($design_opts['primary_color'])) $custom_css .= "--iqe-primary-color: {$design_opts['primary_color']};";
            if (!empty($design_opts['secondary_color'])) $custom_css .= "--iqe-secondary-color: {$design_opts['secondary_color']};";
            if (!empty($design_opts['bg_color'])) $custom_css .= "--iqe-bg-color: {$design_opts['bg_color']};";
            if (!empty($design_opts['form_bg_color'])) $custom_css .= "--iqe-form-bg-color: {$design_opts['form_bg_color']};";
            if (!empty($design_opts['text_color'])) $custom_css .= "--iqe-text-color: {$design_opts['text_color']};";
            if (!empty($design_opts['border_color'])) $custom_css .= "--iqe-border-color: {$design_opts['border_color']};";
            if (!empty($design_opts['border_radius'])) $custom_css .= "--iqe-border-radius: {$design_opts['border_radius']}px;";
            if (!empty($design_opts['max_width'])) $custom_css .= "--iqe-max-width: {$design_opts['max_width']}px;";
            if (!empty($design_opts['heading_size'])) $custom_css .= "--iqe-heading-size: {$design_opts['heading_size']}px;";
            if (!empty($design_opts['body_size'])) $custom_css .= "--iqe-body-size: {$design_opts['body_size']}px;";
            $custom_css .= "}";
            wp_add_inline_style( 'iqe-public-css', $custom_css );
        }
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'iqe-public-js', IQE_PLUGIN_URL . 'public/js/iqe-public.js', array( 'jquery' ), IQE_VERSION, true );
		
        $pricing_options = get_option('iqe_pricing_options');
        if ( !$pricing_options ) $pricing_options = array();

		wp_localize_script( 'iqe-public-js', 'iqe_ajax', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'iqe_form_nonce' ),
            'pricing_options' => $pricing_options
		) );
	}

	public function render_shortcode( $atts ) {
        ob_start();
        ?>
        <div class="instant-quote-estimator">
            <div class="iqe-container">
                <h2 class="iqe-title">Instant Quote Estimator</h2>
                
                <form id="iqe-quote-form">
                    <div class="iqe-form-group">
                        <label for="iqe_service_type">Service Type</label>
                        <select id="iqe_service_type" name="service_type" required>
                            <option value="patio">Patio</option>
                            <option value="driveway">Driveway</option>
                            <option value="garden_renovation">Garden Renovation</option>
                        </select>
                    </div>

                    <div class="iqe-form-group" id="iqe_patio_material_group">
                        <label for="iqe_patio_material">Material Type</label>
                        <select id="iqe_patio_material" name="patio_material">
                            <option value="natural_stone">Natural Stone</option>
                            <option value="porcelain">Porcelain</option>
                        </select>
                    </div>

                    <div class="iqe-form-group iqe-hidden" id="iqe_garden_level_group">
                        <label for="iqe_garden_level">Renovation Level</label>
                        <select id="iqe_garden_level" name="garden_level">
                            <option value="basic">Basic Refresh</option>
                            <option value="full">Full Renovation</option>
                            <option value="high_end">High End</option>
                        </select>
                    </div>

                    <div class="iqe-form-group">
                        <label for="iqe_area">Area (m²)</label>
                        <input type="number" id="iqe_area" name="area" min="0" step="1" required>
                    </div>

                    <div class="iqe-form-group">
                        <label for="iqe_manholes">Number of Manholes</label>
                        <input type="number" id="iqe_manholes" name="manholes" min="0" step="1" value="0">
                    </div>

                    <div class="iqe-form-group">
                        <label for="iqe_poor_access">Poor Access?</label>
                        <select id="iqe_poor_access" name="poor_access">
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>

                    <div class="iqe-results">
                        <div class="iqe-results-title">Estimated Cost</div>
                        <p><span>Total (ex VAT)</span> <span id="iqe_calc_ex_vat" class="iqe-total-ex">£0.00</span></p>
                        <p><span>Total (inc VAT)</span> <span id="iqe_calc_inc_vat" class="iqe-total-inc">£0.00</span></p>
                        <p class="iqe-disclaimer">This is a guide price only. Final quotation depends on access, waste removal, levels, drainage, and material choice.</p>
                    </div>

                    <div class="iqe-lead-capture">
                        <h3>Get your quote via email</h3>
                        <div class="iqe-form-group">
                            <label for="iqe_name">Name</label>
                            <input type="text" id="iqe_name" name="first_name" required>
                        </div>
                        <div class="iqe-form-group">
                            <label for="iqe_email">Email</label>
                            <input type="email" id="iqe_email" name="email_address" required>
                        </div>
                        <div class="iqe-form-group">
                            <label for="iqe_phone">Phone</label>
                            <input type="tel" id="iqe_phone" name="phone_number" required>
                        </div>

                        <button type="submit" id="iqe_submit_btn" class="iqe-submit-btn">
                            Get Quote <span class="iqe-spinner"></span>
                        </button>
                        
                        <div id="iqe_form_message" class="iqe-message"></div>
                    </div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
	}

	public function handle_form_submission() {
		check_ajax_referer( 'iqe_form_nonce', 'security' );

        // Validate required fields
        if ( empty($_POST['first_name']) || empty($_POST['email_address']) || empty($_POST['phone_number']) ) {
            wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
        }

        // Sanitize
        $name = sanitize_text_field($_POST['first_name']);
        $email = sanitize_email($_POST['email_address']);
        $phone = sanitize_text_field($_POST['phone_number']);
        $service = sanitize_text_field($_POST['service_type']);
        $area = floatval($_POST['area']);
        $manholes = intval($_POST['manholes']);
        $poor_access = sanitize_text_field($_POST['poor_access']);
        
        $total_ex = sanitize_text_field($_POST['total_ex_vat']);
        $total_inc = sanitize_text_field($_POST['total_inc_vat']);

        // Collect inputs JSON
        $inputs = array(
            'area' => $area,
            'manholes' => $manholes,
            'poor_access' => $poor_access
        );
        if ($service === 'patio') {
            $inputs['material'] = sanitize_text_field($_POST['patio_material']);
        } elseif ($service === 'garden_renovation') {
            $inputs['level'] = sanitize_text_field($_POST['garden_level']);
        }

        $totals = array(
            'ex_vat' => $total_ex,
            'inc_vat' => $total_inc
        );

        // Store in DB
        global $wpdb;
        $table_name = $wpdb->prefix . 'iqe_leads';
        
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'service_type' => $service,
                'inputs' => json_encode($inputs),
                'totals' => json_encode($totals)
            )
        );

        if ( !$inserted ) {
            wp_send_json_error( array( 'message' => 'Failed to save submission. Please try again.' ) );
        }

        // Email handling (Phase 5 logic)
        $this->send_emails($name, $email, $phone, $service, $inputs, $totals);

        wp_send_json_success( array( 'message' => 'Quote calculated and emailed successfully!' ) );
	}

    private function send_emails($name, $customer_email, $phone, $service, $inputs, $totals) {
        $email_opts = get_option('iqe_email_options');
        $admin_emails = !empty($email_opts['admin_emails']) ? $email_opts['admin_emails'] : get_option('admin_email');
        $from_name = !empty($email_opts['from_name']) ? $email_opts['from_name'] : get_bloginfo('name');
        $from_email = !empty($email_opts['from_email']) ? $email_opts['from_email'] : get_option('admin_email');

        $headers = array('Content-Type: text/html; charset=UTF-8');
        $headers[] = "From: {$from_name} <{$from_email}>";

        // Prettify inputs for email
        $inputs_html = "<ul><li>Area: {$inputs['area']} m²</li><li>Manholes: {$inputs['manholes']}</li><li>Poor Access: " . ucfirst($inputs['poor_access']) . "</li>";
        if (isset($inputs['material'])) $inputs_html .= "<li>Material: " . ucwords(str_replace('_', ' ', $inputs['material'])) . "</li>";
        if (isset($inputs['level'])) $inputs_html .= "<li>Renovation Level: " . ucwords(str_replace('_', ' ', $inputs['level'])) . "</li>";
        $inputs_html .= "</ul>";

        $service_formatted = ucwords(str_replace('_', ' ', $service));

        // Send to Customer
        $customer_subject = "Your Instant Quote Estimate";
        $customer_msg = "
            <h2>Hi {$name},</h2>
            <p>Thank you for requesting an estimate. Here are your details:</p>
            <p><strong>Service:</strong> {$service_formatted}</p>
            {$inputs_html}
            <h3 style='color:#0073aa;'>Estimated Total</h3>
            <p>Total (ex VAT): £{$totals['ex_vat']}</p>
            <p>Total (inc VAT): £{$totals['inc_vat']}</p>
            <p><small><em>This is a guide price only. Final quotation depends on access, waste removal, levels, drainage, and material choice.</em></small></p>
        ";
        wp_mail($customer_email, $customer_subject, $customer_msg, $headers);

        // Send to Admin
        $admin_subject = "New Lead - Quote Request ({$service_formatted})";
        $admin_msg = "
            <h2>New Quote Lead</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> <a href='mailto:{$customer_email}'>{$customer_email}</a></p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Service:</strong> {$service_formatted}</p>
            {$inputs_html}
            <h3>Estimate Given</h3>
            <p>Total (ex VAT): £{$totals['ex_vat']}</p>
            <p>Total (inc VAT): £{$totals['inc_vat']}</p>
        ";
        wp_mail($admin_emails, $admin_subject, $admin_msg, $headers);
    }
}
