jQuery(document).ready(function($) {
    const pricing = iqe_ajax.pricing_options;
    
    // Parse pricing options as floats
    const baseDriveway = parseFloat(pricing.driveway_base) || 50;
    const basePatioNat = parseFloat(pricing.patio_natural) || 70;
    const patioPorcMult = parseFloat(pricing.patio_porcelain_mult) || 15;
    const gardenBasic = parseFloat(pricing.garden_basic) || 40;
    const gardenFull = parseFloat(pricing.garden_full) || 90;
    const gardenHigh = parseFloat(pricing.garden_high) || 150;
    const manholeCharge = parseFloat(pricing.manhole_charge) || 250;
    const poorAccessCharge = parseFloat(pricing.poor_access_charge) || 20;

    const $serviceType = $('#iqe_service_type');
    const $patioMaterialGroup = $('#iqe_patio_material_group');
    const $gardenLevelGroup = $('#iqe_garden_level_group');
    
    function toggleFields() {
        const service = $serviceType.val();
        $patioMaterialGroup.addClass('iqe-hidden');
        $gardenLevelGroup.addClass('iqe-hidden');
        
        if (service === 'patio') {
            $patioMaterialGroup.removeClass('iqe-hidden');
        } else if (service === 'garden_renovation') {
            $gardenLevelGroup.removeClass('iqe-hidden');
        }
        calculateTotal();
    }

    function calculateTotal() {
        const service = $serviceType.val();
        const area = parseFloat($('#iqe_area').val()) || 0;
        const manholes = parseInt($('#iqe_manholes').val()) || 0;
        const poorAccess = $('#iqe_poor_access').val();
        
        let baseRate = 0;
        
        if (service === 'driveway') {
            baseRate = baseDriveway;
        } else if (service === 'patio') {
            const material = $('#iqe_patio_material').val();
            baseRate = basePatioNat;
            if (material === 'porcelain') {
                baseRate = baseRate * (1 + (patioPorcMult / 100));
            }
        } else if (service === 'garden_renovation') {
            const level = $('#iqe_garden_level').val();
            if (level === 'basic') baseRate = gardenBasic;
            else if (level === 'full') baseRate = gardenFull;
            else if (level === 'high_end') baseRate = gardenHigh;
        }
        
        const basePrice = area * baseRate;
        const manholeCost = manholes * manholeCharge;
        let subtotal = basePrice + manholeCost;
        
        if (poorAccess === 'yes') {
            subtotal = subtotal * (1 + (poorAccessCharge / 100));
        }
        
        const totalExVat = subtotal;
        const totalIncVat = subtotal * 1.20;
        
        $('#iqe_calc_ex_vat').text('£' + totalExVat.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#iqe_calc_inc_vat').text('£' + totalIncVat.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    $serviceType.on('change', toggleFields);
    $('#iqe_area, #iqe_manholes, #iqe_poor_access, #iqe_patio_material, #iqe_garden_level').on('input change', calculateTotal);

    // Initial Trigger
    toggleFields();

    // AJAX Form submission
    $('#iqe-quote-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $('#iqe_submit_btn');
        const $msg = $('#iqe_form_message');
        
        $btn.addClass('iqe-loading').prop('disabled', true);
        $msg.removeClass('success error').hide();
        
        const formData = $form.serializeArray();
        formData.push({name: 'action', value: 'iqe_submit_form'});
        formData.push({name: 'security', value: iqe_ajax.nonce});
        
        // Include calculated totals for email/db (server will theoretically trust this as a guide estimate)
        formData.push({name: 'total_ex_vat', value: $('#iqe_calc_ex_vat').text().replace('£', '')});
        formData.push({name: 'total_inc_vat', value: $('#iqe_calc_inc_vat').text().replace('£', '')});

        $.ajax({
            url: iqe_ajax.ajax_url,
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                $btn.removeClass('iqe-loading').prop('disabled', false);
                if (response.success) {
                    $msg.addClass('success').text(response.data.message).show();
                    $form.trigger('reset');
                    calculateTotal();
                } else {
                    $msg.addClass('error').text(response.data.message || 'An error occurred.').show();
                }
            },
            error: function() {
                $btn.removeClass('iqe-loading').prop('disabled', false);
                $msg.addClass('error').text('A server error occurred. Please try again.').show();
            }
        });
    });
});
