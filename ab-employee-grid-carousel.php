<?php
/**
 * Plugin Name: AB Employee Grid and Carousel
 * Plugin URI: https://codetron.co.in
 * Description: Complete Amelia & BuddyBoss employee showcase solution with dynamic grid and carousel layouts. Features full customization, professional card designs, job titles, company info, speciality chips, and seamless integration with Amelia booking providers and BuddyBoss member profiles. Includes dynamic settings, carousel arrow positioning fixes, and complete customization options.
 * Version: 3.3.0
 * Author: CodeTron
 * Author URI: https://codetron.co.in
 * Text Domain: ab-employee-grid-carousel
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'ABEGC_PLUGIN_FILE', __FILE__ );
define( 'ABEGC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ABEGC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ABEGC_VERSION', '3.0.0' );
// Activation hook
register_activation_hook( __FILE__, 'abegc_activate' );
function abegc_activate() {
    abegc_register_cpts();
    flush_rewrite_rules();

    // COMPLETE default settings with ALL options
    if ( ! get_option( 'abegc_global_settings' ) ) {
        update_option( 'abegc_global_settings', array(
            // Layout Settings
            'column_gap' => 20,
            'card_min_height' => 380,
            'card_padding' => 20,
            'card_border_radius' => 16,

            // Card Background Settings
            'card_background_color' => '#ffffff',
            'card_background_gradient' => '',
            'card_background_image' => '',
            'card_background_opacity' => 100,

            // Shadow Settings
            'card_shadow_color' => 'rgba(0,0,0,0.08)',
            'card_shadow_blur' => 20,
            'card_shadow_spread' => 0,
            'card_shadow_x' => 0,
            'card_shadow_y' => 4,
            'card_shadow_hover_color' => 'rgba(0,0,0,0.15)',
            'card_shadow_hover_blur' => 30,
            'card_shadow_hover_y' => 8,

            // Typography Settings
            'card_name_font_size' => 17,
            'card_name_font_weight' => 700,
            'card_name_color' => '#1a202c',
            'card_job_font_size' => 14,
            'card_job_color' => '#475569',
            'card_company_font_size' => 13,
            'card_company_color' => '#64748b',

            // Image Settings
            'image_height' => 220,
            'image_border_radius' => 0,
            'image_overlay_color' => 'transparent',
            'image_overlay_opacity' => 0,
            'image_object_fit' => 'contain',
            'image_background_color' => '#f8fafc',

            // Chip Settings
            'chip_background_color' => '#f1f5f9',
            'chip_text_color' => '#475569',
            'chip_border_color' => '#e2e8f0',
            'chip_border_radius' => 14,
            'chip_padding_x' => 12,
            'chip_padding_y' => 5,
            'chip_font_size' => 12,
            'chip_gap' => 8,

            // Hover Effects
            'enable_hover_effects' => 1,
            'hover_transform' => 'translateY(-3px)',
            'hover_transition_duration' => 0.3,

            // BuddyBoss Integration
            'speciality_field_id' => 14,
            'job_title_field_id' => 15,
            'company_field_id' => 16,

            // Carousel Settings
            'carousel_arrow_size' => 44,
            'carousel_arrow_color' => '#475569',
            'carousel_arrow_background' => 'rgba(255,255,255,0.95)',
            'carousel_arrow_position' => 'outside', // outside, inside, hidden
            'carousel_dots_size' => 10,
            'carousel_dots_color' => '#cbd5e1',
            'carousel_dots_active_color' => '#3182ce',

            // Advanced Settings
            'custom_css' => '',
            'enable_lazy_loading' => 1,
            'enable_lightbox' => 0,
            'show_employee_labels' => 1, // KEEPING this feature as requested
        ) );
    }
}

// Deactivation hook
register_deactivation_hook( __FILE__, 'abegc_deactivate' );
function abegc_deactivate() {
    flush_rewrite_rules();
}

// Initialize plugin
add_action( 'plugins_loaded', 'abegc_init' );
function abegc_init() {
    load_plugin_textdomain( 'ab-employee-grid-carousel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Register CPTs
add_action( 'init', 'abegc_register_cpts' );
function abegc_register_cpts() {
    // Grid CPT
    register_post_type( 'abegc_grid', array(
        'labels' => array(
            'name' => __( 'Employee Grids', 'ab-employee-grid-carousel' ),
            'singular_name' => __( 'Employee Grid', 'ab-employee-grid-carousel' ),
            'add_new' => __( 'Add New Grid', 'ab-employee-grid-carousel' ),
            'add_new_item' => __( 'Add New Employee Grid', 'ab-employee-grid-carousel' ),
            'edit_item' => __( 'Edit Employee Grid', 'ab-employee-grid-carousel' ),
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array( 'title' ),
        'show_in_menu' => false,
        'capability_type' => 'post',
        'capabilities' => array( 'create_posts' => 'manage_options' ),
        'map_meta_cap' => true,
    ) );

    // Carousel CPT
    register_post_type( 'abegc_carousel', array(
        'labels' => array(
            'name' => __( 'Employee Carousels', 'ab-employee-grid-carousel' ),
            'singular_name' => __( 'Employee Carousel', 'ab-employee-grid-carousel' ),
            'add_new' => __( 'Add New Carousel', 'ab-employee-grid-carousel' ),
            'add_new_item' => __( 'Add New Employee Carousel', 'ab-employee-grid-carousel' ),
            'edit_item' => __( 'Edit Employee Carousel', 'ab-employee-grid-carousel' ),
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array( 'title' ),
        'show_in_menu' => false,
        'capability_type' => 'post',
        'capabilities' => array( 'create_posts' => 'manage_options' ),
        'map_meta_cap' => true,
    ) );
}

// Admin menus
add_action( 'admin_menu', 'abegc_admin_menus' );
function abegc_admin_menus() {
    add_menu_page(
        __( 'AB Employee Grid & Carousel', 'ab-employee-grid-carousel' ),
        __( 'AB Employee Grid', 'ab-employee-grid-carousel' ),
        'manage_options',
        'abegc_dashboard',
        'abegc_dashboard_page',
        'dashicons-groups',
        26
    );

    add_submenu_page( 'abegc_dashboard', __( 'Dashboard', 'ab-employee-grid-carousel' ), __( 'Dashboard', 'ab-employee-grid-carousel' ), 'manage_options', 'abegc_dashboard', 'abegc_dashboard_page' );
    add_submenu_page( 'abegc_dashboard', __( 'All Grids', 'ab-employee-grid-carousel' ), __( 'All Grids', 'ab-employee-grid-carousel' ), 'manage_options', 'edit.php?post_type=abegc_grid' );
    add_submenu_page( 'abegc_dashboard', __( 'All Carousels', 'ab-employee-grid-carousel' ), __( 'All Carousels', 'ab-employee-grid-carousel' ), 'manage_options', 'edit.php?post_type=abegc_carousel' );
    add_submenu_page( 'abegc_dashboard', __( 'Add Grid', 'ab-employee-grid-carousel' ), __( '+ Add Grid', 'ab-employee-grid-carousel' ), 'manage_options', 'post-new.php?post_type=abegc_grid' );
    add_submenu_page( 'abegc_dashboard', __( 'Add Carousel', 'ab-employee-grid-carousel' ), __( '+ Add Carousel', 'ab-employee-grid-carousel' ), 'manage_options', 'post-new.php?post_type=abegc_carousel' );
    add_submenu_page( 'abegc_dashboard', __( 'Global Settings', 'ab-employee-grid-carousel' ), __( 'Global Settings', 'ab-employee-grid-carousel' ), 'manage_options', 'abegc_settings', 'abegc_settings_page' );
}

// Dashboard page
function abegc_dashboard_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'ab-employee-grid-carousel' ) );
    }

    // Check Amelia connection
    global $wpdb;
    $table = $wpdb->prefix . 'amelia_users';
    $amelia_connected = ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) === $table );
    $employee_count = 0;

    if ( $amelia_connected ) {
        $employee_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE type = %s", 'provider' ) );
    }

    // Check BuddyBoss connection
    $buddyboss_connected = function_exists( 'bp_is_active' ) && function_exists( 'xprofile_get_field_data' );

    $grids = get_posts( array( 'post_type' => 'abegc_grid', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
    $carousels = get_posts( array( 'post_type' => 'abegc_carousel', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
    ?>
    <div class="wrap">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <span class="dashicons dashicons-groups" style="font-size: 30px;"></span>
            <?php esc_html_e( 'AB Employee Grid & Carousel Dashboard', 'ab-employee-grid-carousel' ); ?>
        </h1>

        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; margin: 20px 0; border-radius: 12px;">
            <h2 style="color: white; margin-top: 0;"><?php esc_html_e( '🚀 Welcome to AB Employee Grid & Carousel', 'ab-employee-grid-carousel' ); ?></h2>
            <p style="font-size: 16px; margin-bottom: 0;"><?php esc_html_e( 'Create stunning employee showcases with Amelia & BuddyBoss integration. Display your team professionally with customizable grids and carousels.', 'ab-employee-grid-carousel' ); ?></p>
        </div>

        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <div style="flex: 1; background: <?php echo $amelia_connected ? '#d4edda' : '#f8d7da'; ?>; border: 1px solid <?php echo $amelia_connected ? '#c3e6cb' : '#f5c6cb'; ?>; padding: 20px; border-radius: 8px; border-left: 4px solid <?php echo $amelia_connected ? '#28a745' : '#dc3545'; ?>;">
                <h3 style="margin-top: 0; display: flex; align-items: center; gap: 10px;">
                    <span class="dashicons <?php echo $amelia_connected ? 'dashicons-yes-alt' : 'dashicons-warning'; ?>"></span>
                    <?php esc_html_e( 'Amelia Integration', 'ab-employee-grid-carousel' ); ?>
                </h3>
                <?php if ( $amelia_connected ) : ?>
                    <p><strong><?php esc_html_e( 'Connected!', 'ab-employee-grid-carousel' ); ?></strong></p>
                    <p><?php echo esc_html( sprintf( __( 'Found %d Amelia providers/employees ready to showcase.', 'ab-employee-grid-carousel' ), $employee_count ) ); ?></p>
                <?php else : ?>
                    <p><strong><?php esc_html_e( 'Not Connected', 'ab-employee-grid-carousel' ); ?></strong></p>
                    <p><?php esc_html_e( 'Install and activate the Amelia plugin to automatically sync employee data.', 'ab-employee-grid-carousel' ); ?></p>
                <?php endif; ?>
            </div>

            <div style="flex: 1; background: <?php echo $buddyboss_connected ? '#d4edda' : '#f8d7da'; ?>; border: 1px solid <?php echo $buddyboss_connected ? '#c3e6cb' : '#f5c6cb'; ?>; padding: 20px; border-radius: 8px; border-left: 4px solid <?php echo $buddyboss_connected ? '#28a745' : '#dc3545'; ?>;">
                <h3 style="margin-top: 0; display: flex; align-items: center; gap: 10px;">
                    <span class="dashicons <?php echo $buddyboss_connected ? 'dashicons-yes-alt' : 'dashicons-warning'; ?>"></span>
                    <?php esc_html_e( 'BuddyBoss Integration', 'ab-employee-grid-carousel' ); ?>
                </h3>
                <?php if ( $buddyboss_connected ) : ?>
                    <p><strong><?php esc_html_e( 'Connected!', 'ab-employee-grid-carousel' ); ?></strong></p>
                    <p><?php esc_html_e( 'Ready to pull job titles, companies, and specialities from member profiles.', 'ab-employee-grid-carousel' ); ?></p>
                <?php else : ?>
                    <p><strong><?php esc_html_e( 'Not Connected', 'ab-employee-grid-carousel' ); ?></strong></p>
                    <p><?php esc_html_e( 'Install BuddyBoss to enable advanced profile field integration.', 'ab-employee-grid-carousel' ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div style="background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h2 style="display: flex; align-items: center; gap: 10px; margin-top: 0;">
                <span class="dashicons dashicons-admin-tools"></span>
                <?php esc_html_e( 'Quick Actions', 'ab-employee-grid-carousel' ); ?>
            </h2>
            <p>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=abegc_grid' ) ); ?>" class="button button-primary button-large">
                    <span class="dashicons dashicons-grid-view"></span> <?php esc_html_e( '+ Create New Grid', 'ab-employee-grid-carousel' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=abegc_carousel' ) ); ?>" class="button button-primary button-large">
                    <span class="dashicons dashicons-slides"></span> <?php esc_html_e( '+ Create New Carousel', 'ab-employee-grid-carousel' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=abegc_settings' ) ); ?>" class="button button-secondary button-large">
                    <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Global Settings', 'ab-employee-grid-carousel' ); ?>
                </a>
            </p>
        </div>

        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <div style="flex: 1; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); border-radius: 8px;">
                <div style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color: white; padding: 15px; border-radius: 8px 8px 0 0;">
                    <h2 style="margin: 0; font-size: 18px; display: flex; align-items: center; gap: 10px;">
                        <span class="dashicons dashicons-grid-view"></span>
                        <?php echo esc_html( sprintf( __( 'Employee Grids (%d)', 'ab-employee-grid-carousel' ), count( $grids ) ) ); ?>
                    </h2>
                </div>
                <div style="padding: 20px;">
                    <?php if ( empty( $grids ) ) : ?>
                        <div style="text-align: center; padding: 30px 0;">
                            <span class="dashicons dashicons-grid-view" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></span>
                            <p style="color: #666; font-style: italic; margin-bottom: 15px;"><?php esc_html_e( 'No employee grids created yet.', 'ab-employee-grid-carousel' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=abegc_grid' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Create Your First Grid', 'ab-employee-grid-carousel' ); ?></a>
                        </div>
                    <?php else : ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e( 'Grid Name', 'ab-employee-grid-carousel' ); ?></th>
                                    <th><?php esc_html_e( 'Shortcode', 'ab-employee-grid-carousel' ); ?></th>
                                    <th><?php esc_html_e( 'Actions', 'ab-employee-grid-carousel' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $grids as $grid ) : ?>
                                <tr>
                                    <td><strong><?php echo esc_html( $grid->post_title ); ?></strong></td>
                                    <td>
                                        <input type="text" readonly value='[ab_employee_grid id="<?php echo esc_attr( $grid->ID ); ?>"]' 
                                               style="width: 280px; font-family: monospace; font-size: 12px; background: #f9f9f9;" 
                                               onclick="this.select()" title="<?php esc_attr_e( 'Click to select shortcode', 'ab-employee-grid-carousel' ); ?>" />
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url( get_edit_post_link( $grid->ID ) ); ?>" class="button button-small"><?php esc_html_e( 'Edit', 'ab-employee-grid-carousel' ); ?></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div style="flex: 1; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04); border-radius: 8px;">
                <div style="background: linear-gradient(135deg, #ec4899 0%, #f59e0b 100%); color: white; padding: 15px; border-radius: 8px 8px 0 0;">
                    <h2 style="margin: 0; font-size: 18px; display: flex; align-items: center; gap: 10px;">
                        <span class="dashicons dashicons-slides"></span>
                        <?php echo esc_html( sprintf( __( 'Employee Carousels (%d)', 'ab-employee-grid-carousel' ), count( $carousels ) ) ); ?>
                    </h2>
                </div>
                <div style="padding: 20px;">
                    <?php if ( empty( $carousels ) ) : ?>
                        <div style="text-align: center; padding: 30px 0;">
                            <span class="dashicons dashicons-slides" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></span>
                            <p style="color: #666; font-style: italic; margin-bottom: 15px;"><?php esc_html_e( 'No employee carousels created yet.', 'ab-employee-grid-carousel' ); ?></p>
                            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=abegc_carousel' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Create Your First Carousel', 'ab-employee-grid-carousel' ); ?></a>
                        </div>
                    <?php else : ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e( 'Carousel Name', 'ab-employee-grid-carousel' ); ?></th>
                                    <th><?php esc_html_e( 'Shortcode', 'ab-employee-grid-carousel' ); ?></th>
                                    <th><?php esc_html_e( 'Actions', 'ab-employee-grid-carousel' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $carousels as $carousel ) : ?>
                                <tr>
                                    <td><strong><?php echo esc_html( $carousel->post_title ); ?></strong></td>
                                    <td>
                                        <input type="text" readonly value='[ab_employee_carousel id="<?php echo esc_attr( $carousel->ID ); ?>"]' 
                                               style="width: 300px; font-family: monospace; font-size: 12px; background: #f9f9f9;" 
                                               onclick="this.select()" title="<?php esc_attr_e( 'Click to select shortcode', 'ab-employee-grid-carousel' ); ?>" />
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url( get_edit_post_link( $carousel->ID ) ); ?>" class="button button-small"><?php esc_html_e( 'Edit', 'ab-employee-grid-carousel' ); ?></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="background: #f8f9fa; border: 1px solid #e9ecef; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #17a2b8;">
            <h3 style="margin-top: 0; color: #17a2b8;"><?php esc_html_e( '📖 How to Use AB Employee Grid & Carousel', 'ab-employee-grid-carousel' ); ?></h3>
            <ol style="margin-left: 20px;">
                <li><strong><?php esc_html_e( 'Install Integrations:', 'ab-employee-grid-carousel' ); ?></strong> <?php esc_html_e( 'Install Amelia (for employee data) and BuddyBoss (for profiles)', 'ab-employee-grid-carousel' ); ?></li>
                <li><strong><?php esc_html_e( 'Configure Settings:', 'ab-employee-grid-carousel' ); ?></strong> <?php esc_html_e( 'Go to Global Settings and configure field IDs and styling', 'ab-employee-grid-carousel' ); ?></li>
                <li><strong><?php esc_html_e( 'Create Showcase:', 'ab-employee-grid-carousel' ); ?></strong> <?php esc_html_e( 'Create a new Grid or Carousel with your preferred settings', 'ab-employee-grid-carousel' ); ?></li>
                <li><strong><?php esc_html_e( 'Use Shortcode:', 'ab-employee-grid-carousel' ); ?></strong> <?php esc_html_e( 'Copy the shortcode and paste it into any page or post', 'ab-employee-grid-carousel' ); ?></li>
            </ol>
        </div>
    </div>
    <?php
}

// COMPLETE Global Settings page with ALL options
function abegc_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'ab-employee-grid-carousel' ) );
    }

    $settings = get_option( 'abegc_global_settings', array() );

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['abegc_settings_nonce'] ) && wp_verify_nonce( $_POST['abegc_settings_nonce'], 'abegc_settings_save' ) ) {
        $new_settings = array();

        // Layout Settings
        $new_settings['column_gap'] = absint( $_POST['column_gap'] );
        $new_settings['card_min_height'] = absint( $_POST['card_min_height'] );
        $new_settings['card_padding'] = absint( $_POST['card_padding'] );
        $new_settings['card_border_radius'] = absint( $_POST['card_border_radius'] );

        // Card Background Settings
        $new_settings['card_background_color'] = sanitize_hex_color( $_POST['card_background_color'] );
        $new_settings['card_background_gradient'] = sanitize_text_field( $_POST['card_background_gradient'] );
        $new_settings['card_background_image'] = sanitize_url( $_POST['card_background_image'] );
        $new_settings['card_background_opacity'] = absint( $_POST['card_background_opacity'] );

        // Shadow Settings
        $new_settings['card_shadow_color'] = sanitize_text_field( $_POST['card_shadow_color'] );
        $new_settings['card_shadow_blur'] = absint( $_POST['card_shadow_blur'] );
        $new_settings['card_shadow_spread'] = intval( $_POST['card_shadow_spread'] );
        $new_settings['card_shadow_x'] = intval( $_POST['card_shadow_x'] );
        $new_settings['card_shadow_y'] = intval( $_POST['card_shadow_y'] );
        $new_settings['card_shadow_hover_color'] = sanitize_text_field( $_POST['card_shadow_hover_color'] );
        $new_settings['card_shadow_hover_blur'] = absint( $_POST['card_shadow_hover_blur'] );
        $new_settings['card_shadow_hover_y'] = intval( $_POST['card_shadow_hover_y'] );

        // Typography Settings
        $new_settings['card_name_font_size'] = absint( $_POST['card_name_font_size'] );
        $new_settings['card_name_font_weight'] = absint( $_POST['card_name_font_weight'] );
        $new_settings['card_name_color'] = sanitize_hex_color( $_POST['card_name_color'] );
        $new_settings['card_job_font_size'] = absint( $_POST['card_job_font_size'] );
        $new_settings['card_job_color'] = sanitize_hex_color( $_POST['card_job_color'] );
        $new_settings['card_company_font_size'] = absint( $_POST['card_company_font_size'] );
        $new_settings['card_company_color'] = sanitize_hex_color( $_POST['card_company_color'] );

        // Image Settings
        $new_settings['image_height'] = absint( $_POST['image_height'] );
        $new_settings['image_border_radius'] = absint( $_POST['image_border_radius'] );
        $new_settings['image_overlay_color'] = sanitize_text_field( $_POST['image_overlay_color'] );
        $new_settings['image_overlay_opacity'] = absint( $_POST['image_overlay_opacity'] );
        $new_settings['image_object_fit'] = sanitize_text_field( $_POST['image_object_fit'] );
        $new_settings['image_background_color'] = sanitize_hex_color( $_POST['image_background_color'] );

        // Chip Settings
        $new_settings['chip_background_color'] = sanitize_hex_color( $_POST['chip_background_color'] );
        $new_settings['chip_text_color'] = sanitize_hex_color( $_POST['chip_text_color'] );
        $new_settings['chip_border_color'] = sanitize_hex_color( $_POST['chip_border_color'] );
        $new_settings['chip_border_radius'] = absint( $_POST['chip_border_radius'] );
        $new_settings['chip_padding_x'] = absint( $_POST['chip_padding_x'] );
        $new_settings['chip_padding_y'] = absint( $_POST['chip_padding_y'] );
        $new_settings['chip_font_size'] = absint( $_POST['chip_font_size'] );
        $new_settings['chip_gap'] = absint( $_POST['chip_gap'] );

        // Hover Effects
        $new_settings['enable_hover_effects'] = isset( $_POST['enable_hover_effects'] ) ? 1 : 0;
        $new_settings['hover_transform'] = sanitize_text_field( $_POST['hover_transform'] );
        $new_settings['hover_transition_duration'] = floatval( $_POST['hover_transition_duration'] );

        // BuddyBoss Integration
        $new_settings['speciality_field_id'] = absint( $_POST['speciality_field_id'] );
        $new_settings['job_title_field_id'] = absint( $_POST['job_title_field_id'] );
        $new_settings['company_field_id'] = absint( $_POST['company_field_id'] );

        // Carousel Settings
        $new_settings['carousel_arrow_size'] = absint( $_POST['carousel_arrow_size'] );
        $new_settings['carousel_arrow_color'] = sanitize_hex_color( $_POST['carousel_arrow_color'] );
        $new_settings['carousel_arrow_background'] = sanitize_text_field( $_POST['carousel_arrow_background'] );
        $new_settings['carousel_arrow_position'] = sanitize_text_field( $_POST['carousel_arrow_position'] );
        $new_settings['carousel_dots_size'] = absint( $_POST['carousel_dots_size'] );
        $new_settings['carousel_dots_color'] = sanitize_hex_color( $_POST['carousel_dots_color'] );
        $new_settings['carousel_dots_active_color'] = sanitize_hex_color( $_POST['carousel_dots_active_color'] );

        // Advanced Settings
        $new_settings['custom_css'] = wp_kses_post( $_POST['custom_css'] );
        $new_settings['enable_lazy_loading'] = isset( $_POST['enable_lazy_loading'] ) ? 1 : 0;
        $new_settings['enable_lightbox'] = isset( $_POST['enable_lightbox'] ) ? 1 : 0;
        $new_settings['show_employee_labels'] = isset( $_POST['show_employee_labels'] ) ? 1 : 0;

        
// Typography fields
$new_settings['title_font_family'] = sanitize_text_field( wp_unslash( $_POST['title_font_family'] ?? '' ) );
$new_settings['title_font_size'] = absint( $_POST['title_font_size'] ?? 18 );
$new_settings['title_font_weight'] = sanitize_text_field( wp_unslash( $_POST['title_font_weight'] ?? '' ) );
$new_settings['title_line_height'] = floatval( $_POST['title_line_height'] ?? 1.3 );

$new_settings['job_font_family'] = sanitize_text_field( wp_unslash( $_POST['job_font_family'] ?? '' ) );
$new_settings['job_font_size'] = absint( $_POST['job_font_size'] ?? 14 );
$new_settings['job_font_weight'] = sanitize_text_field( wp_unslash( $_POST['job_font_weight'] ?? '' ) );

$new_settings['company_font_family'] = sanitize_text_field( wp_unslash( $_POST['company_font_family'] ?? '' ) );
$new_settings['company_font_size'] = absint( $_POST['company_font_size'] ?? 14 );

// Chips fields
$new_settings['chip_bg'] = sanitize_hex_color( wp_unslash( $_POST['chip_bg'] ?? '#eef2f7' ) );
$new_settings['chip_color'] = sanitize_hex_color( wp_unslash( $_POST['chip_color'] ?? '#111827' ) );
$new_settings['chip_border_color'] = sanitize_hex_color( wp_unslash( $_POST['chip_border_color'] ?? '#d1d5db' ) );
$new_settings['chip_radius'] = absint( $_POST['chip_radius'] ?? 20 );
$new_settings['chip_pad_y'] = absint( $_POST['chip_pad_y'] ?? 6 );
$new_settings['chip_pad_x'] = absint( $_POST['chip_pad_x'] ?? 12 );
$new_settings['chip_gap'] = absint( $_POST['chip_gap'] ?? 8 );

// Labels (Advanced)
$new_settings['label_employee_singular'] = sanitize_text_field( wp_unslash( $_POST['label_employee_singular'] ?? 'Employee' ) );
$new_settings['label_employee_plural'] = sanitize_text_field( wp_unslash( $_POST['label_employee_plural'] ?? 'Employees' ) );
update_option( 'abegc_global_settings', $new_settings );
        $settings = $new_settings;
        echo '<div class="updated"><p><strong>' . esc_html__( 'Settings saved successfully!', 'ab-employee-grid-carousel' ) . '</strong></p></div>';
    }

    // Provide ALL defaults
    $defaults = array(
        'column_gap' => 20,
        'card_min_height' => 380,
        'card_padding' => 20,
        'card_border_radius' => 16,
        'card_background_color' => '#ffffff',
        'card_background_gradient' => '',
        'card_background_image' => '',
        'card_background_opacity' => 100,
        'card_shadow_color' => 'rgba(0,0,0,0.08)',
        'card_shadow_blur' => 20,
        'card_shadow_spread' => 0,
        'card_shadow_x' => 0,
        'card_shadow_y' => 4,
        'card_shadow_hover_color' => 'rgba(0,0,0,0.15)',
        'card_shadow_hover_blur' => 30,
        'card_shadow_hover_y' => 8,
        'card_name_font_size' => 17,
        'card_name_font_weight' => 700,
        'card_name_color' => '#1a202c',
        'card_job_font_size' => 14,
        'card_job_color' => '#475569',
        'card_company_font_size' => 13,
        'card_company_color' => '#64748b',
        'image_height' => 220,
        'image_border_radius' => 0,
        'image_overlay_color' => 'transparent',
        'image_overlay_opacity' => 0,
        'image_object_fit' => 'contain',
        'image_background_color' => '#f8fafc',
        'chip_background_color' => '#f1f5f9',
        'chip_text_color' => '#475569',
        'chip_border_color' => '#e2e8f0',
        'chip_border_radius' => 14,
        'chip_padding_x' => 12,
        'chip_padding_y' => 5,
        'chip_font_size' => 12,
        'chip_gap' => 8,
        'enable_hover_effects' => 1,
        'hover_transform' => 'translateY(-3px)',
        'hover_transition_duration' => 0.3,
        'speciality_field_id' => 14,
        'job_title_field_id' => 15,
        'company_field_id' => 16,
        'carousel_arrow_size' => 44,
        'carousel_arrow_color' => '#475569',
        'carousel_arrow_background' => 'rgba(255,255,255,0.95)',
        'carousel_arrow_position' => 'outside',
        'carousel_dots_size' => 10,
        'carousel_dots_color' => '#cbd5e1',
        'carousel_dots_active_color' => '#3182ce',
        'custom_css' => '',
        'enable_lazy_loading' => 1,
        'enable_lightbox' => 0,
        'show_employee_labels' => 1,
    );

    $settings = wp_parse_args( $settings, $defaults );

    // Check connections
    global $wpdb;
    $table = $wpdb->prefix . 'amelia_users';
    $amelia_connected = ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) === $table );
    $buddyboss_connected = function_exists( 'bp_is_active' ) && function_exists( 'xprofile_get_field_data' );
    ?>
    <div class="wrap">
        <h1 style="display: flex; align-items: center; gap: 10px;">
            <span class="dashicons dashicons-admin-settings"></span>
            <?php esc_html_e( 'AB Employee Grid & Carousel - Global Settings', 'ab-employee-grid-carousel' ); ?>
        </h1>

        <p><a href="<?php echo esc_url( admin_url( 'admin.php?page=abegc_dashboard' ) ); ?>" class="button">← <?php esc_html_e( 'Back to Dashboard', 'ab-employee-grid-carousel' ); ?></a></p>

        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h2 style="color: white; margin-top: 0;"><?php esc_html_e( '⚙️ Global Settings', 'ab-employee-grid-carousel' ); ?></h2>
            <p style="margin-bottom: 0;"><?php esc_html_e( 'Customize the appearance and behavior of all your employee grids and carousels. Changes here apply globally unless overridden in individual showcases.', 'ab-employee-grid-carousel' ); ?></p>
        </div>

        <form method="post" id="abegc-settings-form">
            <?php wp_nonce_field( 'abegc_settings_save', 'abegc_settings_nonce' ); ?>

            <div class="abegc-settings-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#layout" class="nav-tab nav-tab-active"><?php esc_html_e( 'Layout', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#background" class="nav-tab"><?php esc_html_e( 'Background', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#typography" class="nav-tab"><?php esc_html_e( 'Typography', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#images" class="nav-tab"><?php esc_html_e( 'Images', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#chips" class="nav-tab"><?php esc_html_e( 'Chips', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#carousel" class="nav-tab"><?php esc_html_e( 'Carousel', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#integration" class="nav-tab"><?php esc_html_e( 'Integration', 'ab-employee-grid-carousel' ); ?></a>
                    <a href="#advanced" class="nav-tab"><?php esc_html_e( 'Advanced', 'ab-employee-grid-carousel' ); ?></a>
                </nav>

                <!-- Layout Settings Tab -->
                <div id="layout" class="abegc-tab-content">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Column Gap (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="column_gap" value="<?php echo esc_attr( $settings['column_gap'] ); ?>" min="0" max="50" />
                                <p class="description"><?php esc_html_e( 'Space between cards in grid layouts.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Card Min Height (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="card_min_height" value="<?php echo esc_attr( $settings['card_min_height'] ); ?>" min="280" max="600" />
                                <p class="description"><?php esc_html_e( 'Minimum height for employee cards.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Card Padding (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="card_padding" value="<?php echo esc_attr( $settings['card_padding'] ); ?>" min="10" max="50" />
                                <p class="description"><?php esc_html_e( 'Internal padding inside each card.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Card Border Radius (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="card_border_radius" value="<?php echo esc_attr( $settings['card_border_radius'] ); ?>" min="0" max="50" />
                                <p class="description"><?php esc_html_e( 'Rounded corners for cards.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Background Settings Tab -->
                <div id="background" class="abegc-tab-content" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Card Background Color', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="color" name="card_background_color" value="<?php echo esc_attr( $settings['card_background_color'] ); ?>" />
                                <p class="description"><?php esc_html_e( 'Background color for employee cards.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Card Background Gradient', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="text" name="card_background_gradient" value="<?php echo esc_attr( $settings['card_background_gradient'] ); ?>" style="width: 100%;" placeholder="linear-gradient(135deg, #667eea 0%, #764ba2 100%)" />
                                <p class="description"><?php esc_html_e( 'CSS gradient for card background (overrides solid color).', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Card Background Image', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="url" name="card_background_image" value="<?php echo esc_attr( $settings['card_background_image'] ); ?>" style="width: 100%;" />
                                <p class="description"><?php esc_html_e( 'Background image URL for cards.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Shadow Color', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="text" name="card_shadow_color" value="<?php echo esc_attr( $settings['card_shadow_color'] ); ?>" />
                                <p class="description"><?php esc_html_e( 'Shadow color (use rgba for transparency).', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Shadow Blur (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="card_shadow_blur" value="<?php echo esc_attr( $settings['card_shadow_blur'] ); ?>" min="0" max="50" />
                            </td>
                        </tr>
                    </table>
                </div>

                

<!-- Typography Settings Tab -->
<div id="typography" class="abegc-tab-content" style="display:none;">
    <table class="form-table">
        <tr><th><?php esc_html_e('Name Font Family','ab-employee-grid-carousel'); ?></th>
            <td><input type="text" name="title_font_family" value="<?php echo esc_attr($settings['title_font_family'] ?? ''); ?>" class="regular-text" />
            <p class="description"><?php esc_html_e('e.g. Inter, Roboto, system-ui (leave blank to use theme).','ab-employee-grid-carousel'); ?></p></td></tr>
        <tr><th><?php esc_html_e('Name Size (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="title_font_size" value="<?php echo esc_attr($settings['title_font_size'] ?? 18); ?>" min="10" max="64" /></td></tr>
        <tr><th><?php esc_html_e('Name Weight','ab-employee-grid-carousel'); ?></th>
            <td><select name="title_font_weight"><option value=""><?php esc_html_e('Default','ab-employee-grid-carousel'); ?></option><option value="400" <?php selected($settings['title_font_weight']??'','400'); ?>>400</option><option value="600" <?php selected($settings['title_font_weight']??'','600'); ?>>600</option><option value="700" <?php selected($settings['title_font_weight']??'','700'); ?>>700</option></select></td></tr>
        <tr><th><?php esc_html_e('Name Line Height','ab-employee-grid-carousel'); ?></th><td><input type="number" step="0.05" name="title_line_height" value="<?php echo esc_attr($settings['title_line_height'] ?? 1.3); ?>" /></td></tr>
        <tr><th><?php esc_html_e('Job Font Family','ab-employee-grid-carousel'); ?></th>
            <td><input type="text" name="job_font_family" value="<?php echo esc_attr($settings['job_font_family'] ?? ''); ?>" class="regular-text" /></td></tr>
        <tr><th><?php esc_html_e('Job Size (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="job_font_size" value="<?php echo esc_attr($settings['job_font_size'] ?? 14); ?>" min="8" max="48"/></td></tr>
        <tr><th><?php esc_html_e('Job Weight','ab-employee-grid-carousel'); ?></th>
            <td><select name="job_font_weight"><option value=""><?php esc_html_e('Default','ab-employee-grid-carousel'); ?></option><option value="400" <?php selected($settings['job_font_weight']??'','400'); ?>>400</option><option value="600" <?php selected($settings['job_font_weight']??'','600'); ?>>600</option></select></td></tr>
        <tr><th><?php esc_html_e('Company Font Family','ab-employee-grid-carousel'); ?></th>
            <td><input type="text" name="company_font_family" value="<?php echo esc_attr($settings['company_font_family'] ?? ''); ?>" class="regular-text" /></td></tr>
        <tr><th><?php esc_html_e('Company Size (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="company_font_size" value="<?php echo esc_attr($settings['company_font_size'] ?? 14); ?>" min="8" max="48"/></td></tr>
    </table>
</div>


<!-- Chip Settings Tab -->
<div id="chips" class="abegc-tab-content" style="display:none;">
    <table class="form-table">
        <tr><th><?php esc_html_e('Chip Background','ab-employee-grid-carousel'); ?></th>
            <td><input type="color" name="chip_bg" value="<?php echo esc_attr($settings['chip_bg'] ?? '#eef2f7'); ?>" /></td></tr>
        <tr><th><?php esc_html_e('Chip Text Color','ab-employee-grid-carousel'); ?></th>
            <td><input type="color" name="chip_color" value="<?php echo esc_attr($settings['chip_color'] ?? '#111827'); ?>" /></td></tr>
        <tr><th><?php esc_html_e('Chip Border Color','ab-employee-grid-carousel'); ?></th>
            <td><input type="color" name="chip_border_color" value="<?php echo esc_attr($settings['chip_border_color'] ?? '#d1d5db'); ?>" /></td></tr>
        <tr><th><?php esc_html_e('Chip Radius (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="chip_radius" value="<?php echo esc_attr($settings['chip_radius'] ?? 20); ?>" min="0" max="80" /></td></tr>
        <tr><th><?php esc_html_e('Chip Padding Y (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="chip_pad_y" value="<?php echo esc_attr($settings['chip_pad_y'] ?? 6); ?>" min="0" max="40" /></td></tr>
        <tr><th><?php esc_html_e('Chip Padding X (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="chip_pad_x" value="<?php echo esc_attr($settings['chip_pad_x'] ?? 12); ?>" min="0" max="60" /></td></tr>
        <tr><th><?php esc_html_e('Chip Gap (px)','ab-employee-grid-carousel'); ?></th>
            <td><input type="number" name="chip_gap" value="<?php echo esc_attr($settings['chip_gap'] ?? 8); ?>" min="0" max="40" /></td></tr>
    </table>
</div>
<!-- Image Settings Tab -->
                <div id="images" class="abegc-tab-content" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Image Height (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="image_height" value="<?php echo esc_attr( $settings['image_height'] ); ?>" min="100" max="400" />
                                <p class="description"><?php esc_html_e( 'Height of employee images in cards.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Image Object Fit', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <select name="image_object_fit">
                                    <option value="contain" <?php selected( $settings['image_object_fit'], 'contain' ); ?>><?php esc_html_e( 'Contain (Full Image)', 'ab-employee-grid-carousel' ); ?></option>
                                    <option value="cover" <?php selected( $settings['image_object_fit'], 'cover' ); ?>><?php esc_html_e( 'Cover (Crop to Fit)', 'ab-employee-grid-carousel' ); ?></option>
                                    <option value="fill" <?php selected( $settings['image_object_fit'], 'fill' ); ?>><?php esc_html_e( 'Fill (Stretch)', 'ab-employee-grid-carousel' ); ?></option>
                                </select>
                                <p class="description"><?php esc_html_e( 'How images should fit in their containers. "Contain" shows full image without cropping.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Image Background Color', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="color" name="image_background_color" value="<?php echo esc_attr( $settings['image_background_color'] ); ?>" />
                                <p class="description"><?php esc_html_e( 'Background color shown behind images.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Image Overlay Color', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="text" name="image_overlay_color" value="<?php echo esc_attr( $settings['image_overlay_color'] ); ?>" />
                                <p class="description"><?php esc_html_e( 'Color overlay on images (use "transparent" for no overlay).', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Image Overlay Opacity (%)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="range" name="image_overlay_opacity" value="<?php echo esc_attr( $settings['image_overlay_opacity'] ); ?>" min="0" max="100" />
                                <span class="abegc-range-value"><?php echo esc_html( $settings['image_overlay_opacity'] ); ?>%</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Carousel Settings Tab -->
                <div id="carousel" class="abegc-tab-content" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Arrow Size (px)', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="carousel_arrow_size" value="<?php echo esc_attr( $settings['carousel_arrow_size'] ); ?>" min="30" max="60" />
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Arrow Position', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <select name="carousel_arrow_position">
                                    <option value="outside" <?php selected( $settings['carousel_arrow_position'], 'outside' ); ?>><?php esc_html_e( 'Outside Container (Fixed)', 'ab-employee-grid-carousel' ); ?></option>
                                    <option value="inside" <?php selected( $settings['carousel_arrow_position'], 'inside' ); ?>><?php esc_html_e( 'Inside Container', 'ab-employee-grid-carousel' ); ?></option>
                                    <option value="hidden" <?php selected( $settings['carousel_arrow_position'], 'hidden' ); ?>><?php esc_html_e( 'Hidden', 'ab-employee-grid-carousel' ); ?></option>
                                </select>
                                <p class="description"><?php esc_html_e( '"Outside Container" positions arrows outside and fixes the cutting issue.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Integration Settings Tab -->
                <div id="integration" class="abegc-tab-content" style="display: none;">
                    <div style="background: #f0f8ff; border: 1px solid #0073aa; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                        <h3 style="margin-top: 0;"><?php esc_html_e( '🔗 Integration Status', 'ab-employee-grid-carousel' ); ?></h3>
                        <p><strong><?php esc_html_e( 'Amelia:', 'ab-employee-grid-carousel' ); ?></strong> <?php echo $amelia_connected ? '<span style="color: green;">✅ Connected</span>' : '<span style="color: red;">❌ Not Connected</span>'; ?></p>
                        <p><strong><?php esc_html_e( 'BuddyBoss:', 'ab-employee-grid-carousel' ); ?></strong> <?php echo $buddyboss_connected ? '<span style="color: green;">✅ Connected</span>' : '<span style="color: red;">❌ Not Connected</span>'; ?></p>
                    </div>

                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Speciality Field ID', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="speciality_field_id" value="<?php echo esc_attr( $settings['speciality_field_id'] ); ?>" min="1" />
                                <p class="description"><?php esc_html_e( 'BuddyBoss profile field ID for employee specialities/skills.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Job Title Field ID', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="job_title_field_id" value="<?php echo esc_attr( $settings['job_title_field_id'] ); ?>" min="1" />
                                <p class="description"><?php esc_html_e( 'BuddyBoss profile field ID for job titles.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Company Field ID', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="number" name="company_field_id" value="<?php echo esc_attr( $settings['company_field_id'] ); ?>" min="1" />
                                <p class="description"><?php esc_html_e( 'BuddyBoss profile field ID for company/organization.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Advanced Settings Tab -->
                <div id="advanced" class="abegc-tab-content" style="display: none;">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Show Employee Labels', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="checkbox" name="show_employee_labels" value="1" <?php checked( $settings['show_employee_labels'], 1 ); ?> />
                                <label for="show_employee_labels"><?php esc_html_e( 'Display "Employee" or custom labels (FEATURE KEPT as requested)', 'ab-employee-grid-carousel' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Enable Lazy Loading', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <input type="checkbox" name="enable_lazy_loading" value="1" <?php checked( $settings['enable_lazy_loading'], 1 ); ?> />
                                <label for="enable_lazy_loading"><?php esc_html_e( 'Lazy load employee images for better performance', 'ab-employee-grid-carousel' ); ?></label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Custom CSS', 'ab-employee-grid-carousel' ); ?></th>
                            <td>
                                <textarea name="custom_css" rows="10" style="width: 100%;" placeholder="/* Add your custom CSS here */"><?php echo esc_textarea( $settings['custom_css'] ); ?></textarea>
                                <p class="description"><?php esc_html_e( 'Add custom CSS to override default styles.', 'ab-employee-grid-carousel' ); ?></p>
                            </td>
                        </tr>
                    </table>
                
<table class="form-table">
    <tr><th><?php esc_html_e('Employee Label (singular)','ab-employee-grid-carousel'); ?></th>
        <td><input type="text" name="label_employee_singular" value="<?php echo esc_attr($settings['label_employee_singular'] ?? 'Employee'); ?>" class="regular-text" /></td></tr>
    <tr><th><?php esc_html_e('Employee Label (plural)','ab-employee-grid-carousel'); ?></th>
        <td><input type="text" name="label_employee_plural" value="<?php echo esc_attr($settings['label_employee_plural'] ?? 'Employees'); ?>" class="regular-text" /></td></tr>
</table>
</div>
            </div>

            <p class="submit">
                <button class="button button-primary button-large" type="submit">
                    <span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Save All Settings', 'ab-employee-grid-carousel' ); ?>
                </button>
                <button class="button button-secondary" type="button" onclick="location.reload();">
                    <span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Reset Changes', 'ab-employee-grid-carousel' ); ?>
                </button>
            </p>
        </form>
    </div>

    <style>
    .abegc-settings-tabs .nav-tab-wrapper {
        margin-bottom: 20px;
    }

    .abegc-tab-content {
        background: #fff;
        padding: 20px;
        border: 1px solid #ccd0d4;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }

    .abegc-range-value {
        font-weight: bold;
        margin-left: 10px;
        color: #0073aa;
    }

    .form-table th {
        width: 250px;
    }

    .form-table .description {
        font-size: 13px;
        margin-top: 5px;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').click(function(e) {
            e.preventDefault();
            $('.nav-tab').removeClass('nav-tab-active');
            $('.abegc-tab-content').hide();
            $(this).addClass('nav-tab-active');
            $($(this).attr('href')).show();
        });

        // Range value display
        $('input[type="range"]').on('input', function() {
            $(this).next('.abegc-range-value').text($(this).val() + '%');
        });
    });
    </script>
    <?php
}

// Meta boxes for grid and carousel settings
add_action( 'add_meta_boxes', 'abegc_add_meta_boxes' );
function abegc_add_meta_boxes() {
    add_meta_box( 'abegc_grid_settings', __( 'Grid Settings', 'ab-employee-grid-carousel' ), 'abegc_grid_meta_box', 'abegc_grid', 'normal', 'high' );
    add_meta_box( 'abegc_carousel_settings', __( 'Carousel Settings', 'ab-employee-grid-carousel' ), 'abegc_carousel_meta_box', 'abegc_carousel', 'normal', 'high' );
}

function abegc_grid_meta_box( $post ) {
    wp_nonce_field( 'abegc_meta_save', 'abegc_meta_nonce' );
    $meta = get_post_meta( $post->ID, 'abegc_meta', true );
    if ( ! is_array( $meta ) ) $meta = array();

    $defaults = array(
        'employees_to_display' => 0,
        'columns' => 4,
        'speciality' => '',
        'border_radius' => 16,
        'card_style' => 'fullimage'
    );
    $meta = wp_parse_args( $meta, $defaults );
    ?>
    <table class="form-table">
        <tr>
            <th><?php esc_html_e( 'Employees to Display', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="number" name="employees_to_display" value="<?php echo esc_attr( $meta['employees_to_display'] ); ?>" min="0" /></td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Columns', 'ab-employee-grid-carousel' ); ?></th>
            <td>
                <select name="columns">
                    <option value="2" <?php selected( $meta['columns'], 2 ); ?>>2</option>
                    <option value="3" <?php selected( $meta['columns'], 3 ); ?>>3</option>
                    <option value="4" <?php selected( $meta['columns'], 4 ); ?>>4</option>
                    <option value="6" <?php selected( $meta['columns'], 6 ); ?>>6</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Card Style', 'ab-employee-grid-carousel' ); ?></th>
            <td>
                <select name="card_style">
                    <option value="fullimage" <?php selected( $meta['card_style'], 'fullimage' ); ?>><?php esc_html_e( 'Full Image', 'ab-employee-grid-carousel' ); ?></option>
                    <option value="professional" <?php selected( $meta['card_style'], 'professional' ); ?>><?php esc_html_e( 'Professional', 'ab-employee-grid-carousel' ); ?></option>
                    <option value="minimal" <?php selected( $meta['card_style'], 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'ab-employee-grid-carousel' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Shortcode', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="text" readonly value='[ab_employee_grid id="<?php echo $post->ID; ?>"]' style="width: 100%;" onclick="this.select()" /></td>
        </tr>
    </table>
    <?php
}

function abegc_carousel_meta_box( $post ) {
    wp_nonce_field( 'abegc_meta_save', 'abegc_meta_nonce' );
    $meta = get_post_meta( $post->ID, 'abegc_meta', true );
    if ( ! is_array( $meta ) ) $meta = array();

    $defaults = array(
        'total_employees' => 10,
        'per_slide' => 3,
        'speciality' => '',
        'border_radius' => 16,
        'card_style' => 'fullimage',
        'slider_lib' => 'swiper',
        'dots_show' => 1,
        'arrows_show' => 1
    );
    $meta = wp_parse_args( $meta, $defaults );
    ?>
    <table class="form-table">
        <tr>
            <th><?php esc_html_e( 'Total Employees', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="number" name="total_employees" value="<?php echo esc_attr( $meta['total_employees'] ); ?>" min="1" /></td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Employees Per Slide', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="number" name="per_slide" value="<?php echo esc_attr( $meta['per_slide'] ); ?>" min="1" max="6" /></td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Card Style', 'ab-employee-grid-carousel' ); ?></th>
            <td>
                <select name="card_style">
                    <option value="fullimage" <?php selected( $meta['card_style'], 'fullimage' ); ?>><?php esc_html_e( 'Full Image', 'ab-employee-grid-carousel' ); ?></option>
                    <option value="professional" <?php selected( $meta['card_style'], 'professional' ); ?>><?php esc_html_e( 'Professional', 'ab-employee-grid-carousel' ); ?></option>
                    <option value="minimal" <?php selected( $meta['card_style'], 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'ab-employee-grid-carousel' ); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Show Dots', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="checkbox" name="dots_show" value="1" <?php checked( $meta['dots_show'], 1 ); ?> /></td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Show Arrows', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="checkbox" name="arrows_show" value="1" <?php checked( $meta['arrows_show'], 1 ); ?> /></td>
        </tr>
        <tr>
            <th><?php esc_html_e( 'Shortcode', 'ab-employee-grid-carousel' ); ?></th>
            <td><input type="text" readonly value='[ab_employee_carousel id="<?php echo $post->ID; ?>"]' style="width: 100%;" onclick="this.select()" /></td>
        </tr>
    </table>
    <?php
}

// Save meta
add_action( 'save_post', 'abegc_save_meta' );
function abegc_save_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( ! isset( $_POST['abegc_meta_nonce'] ) || ! wp_verify_nonce( $_POST['abegc_meta_nonce'], 'abegc_meta_save' ) ) return;

    $fields = array( 'employees_to_display', 'columns', 'speciality', 'border_radius', 'card_style', 'total_employees', 'per_slide', 'slider_lib', 'dots_show', 'arrows_show' );
    $meta = array();

    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            if ( is_numeric( $_POST[$field] ) ) {
                $meta[$field] = absint( $_POST[$field] );
            } else {
                $meta[$field] = sanitize_text_field( $_POST[$field] );
            }
        } else {
            $meta[$field] = in_array( $field, array( 'dots_show', 'arrows_show' ) ) ? 0 : '';
        }
    }

    update_post_meta( $post_id, 'abegc_meta', $meta );
}

// Enqueue assets
add_action( 'admin_enqueue_scripts', 'abegc_admin_assets' );
function abegc_admin_assets() {
    wp_enqueue_style( 'abegc-admin-css', ABEGC_PLUGIN_URL . 'assets/admin.css', array(), ABEGC_VERSION );
    wp_enqueue_script( 'abegc-admin-js', ABEGC_PLUGIN_URL . 'assets/admin.js', array( 'jquery' ), ABEGC_VERSION, true );
}

add_action( 'wp_enqueue_scripts', 'abegc_frontend_assets' );
function abegc_frontend_assets() {
    wp_enqueue_style( 'abegc-frontend-css', ABEGC_PLUGIN_URL . 'assets/front.css', array(), ABEGC_VERSION );
}

// DYNAMIC CSS generation from global settings
add_action( 'wp_head', 'abegc_dynamic_styles' );
function abegc_dynamic_styles() {
    $settings = get_option( 'abegc_global_settings', array() );
    if ( empty( $settings ) ) return;

    echo '<style id="abegc-dynamic-styles">';
    echo ':root {';

    // Layout variables
    if ( isset( $settings['column_gap'] ) ) echo '--abegc-gap: ' . intval( $settings['column_gap'] ) . 'px;';
    if ( isset( $settings['card_min_height'] ) ) echo '--abegc-height: ' . intval( $settings['card_min_height'] ) . 'px;';
    if ( isset( $settings['card_padding'] ) ) echo '--abegc-padding: ' . intval( $settings['card_padding'] ) . 'px;';
    if ( isset( $settings['card_border_radius'] ) ) echo '--abegc-border-radius: ' . intval( $settings['card_border_radius'] ) . 'px;';

    // Background variables
    if ( isset( $settings['card_background_color'] ) ) echo '--abegc-bg-color: ' . esc_attr( $settings['card_background_color'] ) . ';';
    if ( isset( $settings['card_background_gradient'] ) && ! empty( $settings['card_background_gradient'] ) ) {
        echo '--abegc-bg-gradient: ' . esc_attr( $settings['card_background_gradient'] ) . ';';
    }

    // Shadow variables
    if ( isset( $settings['card_shadow_color'], $settings['card_shadow_blur'], $settings['card_shadow_x'], $settings['card_shadow_y'], $settings['card_shadow_spread'] ) ) {
        $shadow = sprintf( '%dpx %dpx %dpx %dpx %s', 
            intval( $settings['card_shadow_x'] ), 
            intval( $settings['card_shadow_y'] ), 
            intval( $settings['card_shadow_blur'] ), 
            intval( $settings['card_shadow_spread'] ), 
            esc_attr( $settings['card_shadow_color'] ) 
        );
        echo '--abegc-shadow: ' . $shadow . ';';
    }

    // Typography variables
    if ( isset( $settings['card_name_font_size'] ) ) echo '--abegc-name-size: ' . intval( $settings['card_name_font_size'] ) . 'px;';
    if ( isset( $settings['card_name_font_weight'] ) ) echo '--abegc-name-weight: ' . intval( $settings['card_name_font_weight'] ) . ';';
    if ( isset( $settings['card_name_color'] ) ) echo '--abegc-name-color: ' . esc_attr( $settings['card_name_color'] ) . ';';

    // Image variables
    if ( isset( $settings['image_height'] ) ) echo '--abegc-img-height: ' . intval( $settings['image_height'] ) . 'px;';
    if ( isset( $settings['image_object_fit'] ) ) echo '--abegc-img-fit: ' . esc_attr( $settings['image_object_fit'] ) . ';';
    if ( isset( $settings['image_background_color'] ) ) echo '--abegc-img-bg: ' . esc_attr( $settings['image_background_color'] ) . ';';

    // Carousel variables - FIXED positioning
    if ( isset( $settings['carousel_arrow_size'] ) ) echo '--abegc-arrow-size: ' . intval( $settings['carousel_arrow_size'] ) . 'px;';
    if ( isset( $settings['carousel_arrow_color'] ) ) echo '--abegc-arrow-color: ' . esc_attr( $settings['carousel_arrow_color'] ) . ';';
    if ( isset( $settings['carousel_arrow_background'] ) ) echo '--abegc-arrow-bg: ' . esc_attr( $settings['carousel_arrow_background'] ) . ';';

    echo '}';

    // Custom CSS from settings
    if ( isset( $settings['custom_css'] ) && ! empty( $settings['custom_css'] ) ) {
        echo '/* Custom CSS from Global Settings */';
        echo wp_kses_post( $settings['custom_css'] );
    }

    // FIXED carousel arrow positioning based on setting
    if ( isset( $settings['carousel_arrow_position'] ) ) {
        if ( $settings['carousel_arrow_position'] === 'outside' ) {
            echo '.abegc-carousel-wrap { padding: 0 60px; }'; // Add padding to prevent cutting
            echo '.abegc-carousel-wrap .swiper-button-prev { left: 0 !important; }';
            echo '.abegc-carousel-wrap .swiper-button-next { right: 0 !important; }';
        } elseif ( $settings['carousel_arrow_position'] === 'inside' ) {
            echo '.abegc-carousel-wrap .swiper-button-prev { left: 20px !important; }';
            echo '.abegc-carousel-wrap .swiper-button-next { right: 20px !important; }';
        } elseif ( $settings['carousel_arrow_position'] === 'hidden' ) {
            echo '.abegc-carousel-wrap .swiper-button-prev, .abegc-carousel-wrap .swiper-button-next { display: none !important; }';
        }
    }

    
// Typography & Chips variables
if ( isset( $settings['chip_bg'] ) ) echo '--abegc-chip-bg: ' . esc_attr( $settings['chip_bg'] ) . ';';
if ( isset( $settings['chip_color'] ) ) echo '--abegc-chip-color: ' . esc_attr( $settings['chip_color'] ) . ';';
if ( isset( $settings['chip_border_color'] ) ) echo '--abegc-chip-border: ' . esc_attr( $settings['chip_border_color'] ) . ';';
if ( isset( $settings['chip_radius'] ) ) echo '--abegc-chip-radius: ' . intval( $settings['chip_radius'] ) . 'px;';
if ( isset( $settings['chip_pad_y'] ) ) echo '--abegc-chip-pad-y: ' . intval( $settings['chip_pad_y'] ) . 'px;';
if ( isset( $settings['chip_pad_x'] ) ) echo '--abegc-chip-pad-x: ' . intval( $settings['chip_pad_x'] ) . 'px;';
if ( isset( $settings['chip_gap'] ) ) echo '--abegc-chip-gap: ' . intval( $settings['chip_gap'] ) . 'px;';

if ( isset( $settings['title_font_family'] ) ) echo '--abegc-title-font: ' . esc_attr( $settings['title_font_family'] ) . ';';
if ( isset( $settings['title_font_size'] ) ) echo '--abegc-title-size: ' . intval( $settings['title_font_size'] ) . 'px;';
if ( isset( $settings['title_color'] ) ) echo '--abegc-title-color: ' . esc_attr( $settings['title_color'] ) . ';';
if ( isset( $settings['title_line_height'] ) ) echo '--abegc-title-line: ' . floatval( $settings['title_line_height'] ) . ';';
if ( isset( $settings['title_align'] ) ) echo '--abegc-title-align: ' . esc_attr( $settings['title_align'] ) . ';';

if ( isset( $settings['job_font_family'] ) ) echo '--abegc-job-font: ' . esc_attr( $settings['job_font_family'] ) . ';';
if ( isset( $settings['job_font_size'] ) ) echo '--abegc-job-size: ' . intval( $settings['job_font_size'] ) . 'px;';
if ( isset( $settings['job_color'] ) ) echo '--abegc-job-color: ' . esc_attr( $settings['job_color'] ) . ';';
echo '</style>';
}

// Helper functions for data retrieval
function abegc_get_employee_image( $picture_path, $user_id ) {
    if ( ! empty( $picture_path ) ) {
        if ( filter_var( $picture_path, FILTER_VALIDATE_URL ) ) {
            return $picture_path;
        } else {
            $possible_urls = array(
                home_url( ltrim( $picture_path, '/' ) ),
                home_url( 'wp-content/uploads/' . ltrim( $picture_path, '/' ) )
            );

            foreach ( $possible_urls as $url ) {
                $response = wp_remote_head( $url );
                if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                    return $url;
                }
            }
        }
    }

    return get_avatar_url( $user_id, array( 'size' => 600, 'default' => 'mystery' ) );
}

function abegc_bp_get_user_domain( $user_id ) {
    if ( function_exists( 'bp_core_get_user_domain' ) ) {
        return bp_core_get_user_domain( $user_id );
    }
    return get_author_posts_url( $user_id );
}

function abegc_xprofile_get_field_data( $field, $user_id = 0 ) {
    if ( function_exists( 'xprofile_get_field_data' ) ) {
        return xprofile_get_field_data( $field, $user_id );
    }
    return '';
}

// Grid Shortcode - displays employee grid
add_shortcode( 'ab_employee_grid', 'abegc_grid_shortcode' );
function abegc_grid_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'id' => 0 ), $atts );
    $id = absint( $atts['id'] );

    if ( ! $id ) return '';

    $meta = get_post_meta( $id, 'abegc_meta', true );
    if ( ! is_array( $meta ) ) $meta = array();

    $settings = get_option( 'abegc_global_settings', array() );

    wp_enqueue_style( 'abegc-frontend-css' );

    global $wpdb;
    $table = $wpdb->prefix . 'amelia_users';

    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) !== $table ) {
        return '<p>' . esc_html__( 'Amelia plugin not found.', 'ab-employee-grid-carousel' ) . '</p>';
    }

    $query = $wpdb->prepare( "SELECT externalId, firstName, lastName, pictureFullPath FROM {$table} WHERE type = %s", 'provider' );
    $rows = $wpdb->get_results( $query );

    if ( empty( $rows ) ) {
        return '<p>' . esc_html__( 'No employees found.', 'ab-employee-grid-carousel' ) . '</p>';
    }

    $items = array();
    foreach ( $rows as $row ) {
        $uid = absint( $row->externalId );
        $name = trim( $row->firstName . ' ' . $row->lastName );
        $picture = abegc_get_employee_image( $row->pictureFullPath, $uid );

        $job_title = abegc_xprofile_get_field_data( $settings['job_title_field_id'] ?? 15, $uid );
        $company = abegc_xprofile_get_field_data( $settings['company_field_id'] ?? 16, $uid );

        $spec_raw = abegc_xprofile_get_field_data( $settings['speciality_field_id'] ?? 14, $uid );
        $specs = array();
        if ( $spec_raw ) {
            $specs = is_array( $spec_raw ) ? $spec_raw : array_map( 'trim', explode( ',', $spec_raw ) );
        }

        $items[] = array(
            'uid' => $uid,
            'name' => $name,
            'picture' => $picture,
            'job_title' => $job_title ? $job_title : 'Team Member',
            'company' => $company ? $company : '',
            'specs' => $specs,
            'profile' => abegc_bp_get_user_domain( $uid )
        );
    }

    // Filter by speciality
    if ( ! empty( $meta['speciality'] ) ) {
        $wanted = array_map( 'trim', explode( ',', $meta['speciality'] ) );
        $items = array_filter( $items, function( $item ) use ( $wanted ) {
            foreach ( $item['specs'] as $spec ) {
                if ( in_array( $spec, $wanted ) ) return true;
            }
            return false;
        } );
    }

    // Limit employees
    if ( ! empty( $meta['employees_to_display'] ) && $meta['employees_to_display'] > 0 ) {
        $items = array_slice( $items, 0, $meta['employees_to_display'] );
    }

    if ( empty( $items ) ) {
        return '<p>' . esc_html__( 'No employees match the criteria.', 'ab-employee-grid-carousel' ) . '</p>';
    }

    $columns = $meta['columns'] ?? 4;
    $gap = $settings['column_gap'] ?? 20;
    $show_labels = $settings['show_employee_labels'] ?? 1;

    ob_start();
    ?>
    <div class="abegc-grid-container abegc-layout" data-columns="<?php echo esc_attr( $columns ); ?>">
        <div class="abegc-grid-inner" style="gap: <?php echo esc_attr( $gap ); ?>px; grid-template-columns: repeat(<?php echo esc_attr( $columns ); ?>, 1fr);">
            <?php foreach ( $items as $item ) : ?>
                <div class="abegc-card abegc-card-<?php echo esc_attr( $meta['card_style'] ?? 'fullimage' ); ?>" 
                     style="border-radius: <?php echo esc_attr( $meta['border_radius'] ?? 16 ); ?>px;">

                    <div class="abegc-card-image">
                        <a href="<?php echo esc_url( $item['profile'] ); ?>">
                            <img src="<?php echo esc_url( $item['picture'] ); ?>" 
                                 alt="<?php echo esc_attr( $item['name'] ); ?>" 
                                 class="abegc-card-img" 
                                 <?php echo ( $settings['enable_lazy_loading'] ?? 1 ) ? 'loading="lazy"' : ''; ?> />
                        </a>
                    </div>

                    <div class="abegc-card-content">
                        <div class="abegc-card-header">
                            <h3 class="abegc-card-name">
                                <a href="<?php echo esc_url( $item['profile'] ); ?>">
                                    <?php echo esc_html( $item['name'] ); ?>
                                </a>
                            </h3>
                            <div class="abegc-card-job-info">
                                <?php if ( $show_labels ) : ?>
                                    <div class="abegc-employee-label"><?php esc_html_e( 'Employee', 'ab-employee-grid-carousel' ); ?></div>
                                <?php endif; ?>
                                <div class="abegc-job-title"><?php echo esc_html( $item['job_title'] ); ?></div>
                                <?php if ( $item['company'] ) : ?>
                                    <div class="abegc-company">at <?php echo esc_html( $item['company'] ); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ( ! empty( $item['specs'] ) ) : ?>
                            <div class="abegc-chip-wrap">
                                <?php foreach ( $item['specs'] as $spec ) : ?>
                                    <span class="abegc-chip"><?php echo esc_html( $spec ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Carousel Shortcode - displays employee carousel
add_shortcode( 'ab_employee_carousel', 'abegc_carousel_shortcode' );
function abegc_carousel_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'id' => 0 ), $atts );
    $id = absint( $atts['id'] );

    if ( ! $id ) return '';

    $meta = get_post_meta( $id, 'abegc_meta', true );
    if ( ! is_array( $meta ) ) $meta = array();

    $settings = get_option( 'abegc_global_settings', array() );

    wp_enqueue_style( 'abegc-frontend-css' );

    // Enqueue Swiper library
    wp_enqueue_style( 'abegc-swiper-css', plugins_url( 'assets/swiper-bundle.min.css', __FILE__ ), array(), ABEGC_VERSION );
    wp_enqueue_script( 'abegc-swiper-js', plugins_url( 'assets/swiper.min.js', __FILE__ ), array(), ABEGC_VERSION, true );
    wp_enqueue_script( 'abegc-swiper-fallback', plugins_url( 'assets/abegc-swiper-fallback.js', __FILE__ ), array(), ABEGC_VERSION, true );

    global $wpdb;
    $table = $wpdb->prefix . 'amelia_users';

    if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) !== $table ) {
        return '<p>' . esc_html__( 'Amelia plugin not found.', 'ab-employee-grid-carousel' ) . '</p>';
    }

    $query = $wpdb->prepare( "SELECT externalId, firstName, lastName, pictureFullPath FROM {$table} WHERE type = %s", 'provider' );
    $rows = $wpdb->get_results( $query );

    if ( empty( $rows ) ) {
        return '<p>' . esc_html__( 'No employees found.', 'ab-employee-grid-carousel' ) . '</p>';
    }

    $items = array();
    foreach ( $rows as $row ) {
        $uid = absint( $row->externalId );
        $name = trim( $row->firstName . ' ' . $row->lastName );
        $picture = abegc_get_employee_image( $row->pictureFullPath, $uid );

        $job_title = abegc_xprofile_get_field_data( $settings['job_title_field_id'] ?? 15, $uid );
        $company = abegc_xprofile_get_field_data( $settings['company_field_id'] ?? 16, $uid );

        $spec_raw = abegc_xprofile_get_field_data( $settings['speciality_field_id'] ?? 14, $uid );
        $specs = array();
        if ( $spec_raw ) {
            $specs = is_array( $spec_raw ) ? $spec_raw : array_map( 'trim', explode( ',', $spec_raw ) );
        }

        $items[] = array(
            'uid' => $uid,
            'name' => $name,
            'picture' => $picture,
            'job_title' => $job_title ? $job_title : 'Team Member',
            'company' => $company ? $company : '',
            'specs' => $specs,
            'profile' => abegc_bp_get_user_domain( $uid )
        );
    }

    // Filter by speciality
    if ( ! empty( $meta['speciality'] ) ) {
        $wanted = array_map( 'trim', explode( ',', $meta['speciality'] ) );
        $items = array_filter( $items, function( $item ) use ( $wanted ) {
            foreach ( $item['specs'] as $spec ) {
                if ( in_array( $spec, $wanted ) ) return true;
            }
            return false;
        } );
    }

    // Limit employees
    if ( ! empty( $meta['total_employees'] ) && $meta['total_employees'] > 0 ) {
        $items = array_slice( $items, 0, $meta['total_employees'] );
    }

    if ( empty( $items ) ) {
        return '<p>' . esc_html__( 'No employees match the criteria.', 'ab-employee-grid-carousel' ) . '</p>';
    }

    $per_slide = $meta['per_slide'] ?? 3;
    $gap = $settings['column_gap'] ?? 20;
    $carousel_id = 'abegc_carousel_' . wp_rand( 1000, 9999 );
    $show_labels = $settings['show_employee_labels'] ?? 1;

    ob_start();
    ?>
    <div class="abegc-carousel-wrap abegc-layout">
        <div class="abegc-swiper swiper" id="<?php echo esc_attr( $carousel_id ); ?>">
            <div class="swiper-wrapper abegc-slides">
            <?php foreach ( $items as $item ) : ?>
                <div class="swiper-slide abegc-slide">
                    <div class="abegc-card abegc-card-<?php echo esc_attr( $meta['card_style'] ?? 'fullimage' ); ?>" 
                         style="border-radius: <?php echo esc_attr( $meta['border_radius'] ?? 16 ); ?>px;">

                        <div class="abegc-card-image">
                            <a href="<?php echo esc_url( $item['profile'] ); ?>">
                                <img src="<?php echo esc_url( $item['picture'] ); ?>" 
                                     alt="<?php echo esc_attr( $item['name'] ); ?>" 
                                     class="abegc-card-img" 
                                     <?php echo ( $settings['enable_lazy_loading'] ?? 1 ) ? 'loading="lazy"' : ''; ?> />
                            </a>
                        </div>

                        <div class="abegc-card-content">
                            <div class="abegc-card-header">
                                <h3 class="abegc-card-name">
                                    <a href="<?php echo esc_url( $item['profile'] ); ?>">
                                        <?php echo esc_html( $item['name'] ); ?>
                                    </a>
                                </h3>
                                <div class="abegc-card-job-info">
                                    <?php if ( $show_labels ) : ?>
                                        <div class="abegc-employee-label"><?php esc_html_e( 'Employee', 'ab-employee-grid-carousel' ); ?></div>
                                    <?php endif; ?>
                                    <div class="abegc-job-title"><?php echo esc_html( $item['job_title'] ); ?></div>
                                    <?php if ( $item['company'] ) : ?>
                                        <div class="abegc-company">at <?php echo esc_html( $item['company'] ); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if ( ! empty( $item['specs'] ) ) : ?>
                                <div class="abegc-chip-wrap">
                                    <?php foreach ( $item['specs'] as $spec ) : ?>
                                        <span class="abegc-chip"><?php echo esc_html( $spec ); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>

            <?php if ( $meta['dots_show'] ?? 1 ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>

            <?php if ( $meta['arrows_show'] ?? 1 ) : ?>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            <?php endif; ?>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function(){
            if (typeof Swiper !== 'undefined') {
                new Swiper('#<?php echo esc_js( $carousel_id ); ?>', {
                    slidesPerView: <?php echo esc_js( $per_slide ); ?>,
                    spaceBetween: <?php echo esc_js( $gap ); ?>,
                    loop: true,
                    pagination: {
                        el: '#<?php echo esc_js( $carousel_id ); ?> .swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '#<?php echo esc_js( $carousel_id ); ?> .swiper-button-next',
                        prevEl: '#<?php echo esc_js( $carousel_id ); ?> .swiper-button-prev',
                    },
                    breakpoints: {
                        640: { slidesPerView: 1 },
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: <?php echo esc_js( $per_slide ); ?> },
                    },
                });
            }
        });
        </script>
    </div>
    <?php
    return ob_get_clean();
}

// Plugin action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'abegc_plugin_links' );
function abegc_plugin_links( $links ) {
    $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=abegc_settings' ) ) . '">' . __( 'Settings', 'ab-employee-grid-carousel' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

// End of plugin file
