<?php
// Make sure user is admin before rendering
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

global $wpdb;
$table_name = $wpdb->prefix . 'iqe_leads';

// Verify table exists before querying
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    echo '<div class="wrap"><h1>Quote Leads</h1><p>Database table not initialized yet. Deactivate and reactivate the plugin to create the necessary tables.</p></div>';
    return;
}

// Basic sorting
$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'created_at';
$order = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';

$valid_orderby = array('id', 'name', 'email', 'service_type', 'created_at');
if ( ! in_array( $orderby, $valid_orderby ) ) {
    $orderby = 'created_at';
}

$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

// Fetch rows
$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY {$orderby} {$order}" );
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Quote Leads</h1>
    <a href="<?php echo esc_url( admin_url( 'admin-post.php?action=iqe_export_csv' ) ); ?>" class="page-title-action">Export to CSV</a>
    <hr class="wp-header-end">

    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-primary <?php echo $orderby == 'id' ? 'sorted ' . strtolower($order) : 'sortable desc'; ?>">
                    <a href="?page=iqe_leads&orderby=id&order=<?php echo ($orderby == 'id' && $order == 'ASC') ? 'desc' : 'asc'; ?>"><span>ID</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" class="manage-column <?php echo $orderby == 'name' ? 'sorted ' . strtolower($order) : 'sortable desc'; ?>">
                    <a href="?page=iqe_leads&orderby=name&order=<?php echo ($orderby == 'name' && $order == 'ASC') ? 'desc' : 'asc'; ?>"><span>Name & Contact</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" class="manage-column <?php echo $orderby == 'service_type' ? 'sorted ' . strtolower($order) : 'sortable desc'; ?>">
                    <a href="?page=iqe_leads&orderby=service_type&order=<?php echo ($orderby == 'service_type' && $order == 'ASC') ? 'desc' : 'asc'; ?>"><span>Service Type</span><span class="sorting-indicator"></span></a>
                </th>
                <th scope="col" class="manage-column">Options Selected</th>
                <th scope="col" class="manage-column">Quoted Price</th>
                <th scope="col" class="manage-column <?php echo $orderby == 'created_at' ? 'sorted ' . strtolower($order) : 'sortable desc'; ?>">
                    <a href="?page=iqe_leads&orderby=created_at&order=<?php echo ($orderby == 'created_at' && $order == 'ASC') ? 'desc' : 'asc'; ?>"><span>Date Submitted</span><span class="sorting-indicator"></span></a>
                </th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php if ( ! empty($results) ) : ?>
                <?php foreach ( $results as $row ) : 
                    $inputs = json_decode($row->inputs, true);
                    $totals = json_decode($row->totals, true);
                    
                    $options_html = "Area: {$inputs['area']} m²<br>Manholes: {$inputs['manholes']}<br>Poor Access: " . ucfirst($inputs['poor_access']);
                    if (isset($inputs['material'])) $options_html .= "<br>Material: " . ucwords(str_replace('_', ' ', $inputs['material']));
                    if (isset($inputs['level'])) $options_html .= "<br>Level: " . ucwords(str_replace('_', ' ', $inputs['level']));
                ?>
                    <tr>
                        <td class="column-primary" data-colname="ID">#<?php echo esc_html($row->id); ?></td>
                        <td data-colname="Name & Contact">
                            <strong><?php echo esc_html($row->name); ?></strong><br>
                            <a href="mailto:<?php echo esc_attr($row->email); ?>"><?php echo esc_html($row->email); ?></a><br>
                            <?php echo esc_html($row->phone); ?>
                        </td>
                        <td data-colname="Service"><?php echo esc_html(ucwords(str_replace('_', ' ', $row->service_type))); ?></td>
                        <td data-colname="Options Selected"><?php echo $options_html; ?></td>
                        <td data-colname="Quoted Price">
                            Ex VAT: £<?php echo esc_html($totals['ex_vat']); ?><br>
                            Inc VAT: £<?php echo esc_html($totals['inc_vat']); ?>
                        </td>
                        <td data-colname="Date Submitted"><?php echo esc_html(date('Y-m-d H:i:s', strtotime($row->created_at))); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr class="no-items"><td colspan="6" class="colspanchange">No leads found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
