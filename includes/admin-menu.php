<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Tambahkan halaman menu di bawah "Pengaturan"
function skp_add_admin_menu() {
    add_options_page(
        'Simulasi KPR Pro Pengaturan',
        'Simulasi KPR Pro',
        'manage_options',
        'simulasi_kpr_pro',
        'skp_options_page_html'
    );
}
add_action( 'admin_menu', 'skp_add_admin_menu' );

// Daftarkan pengaturan menggunakan Settings API
function skp_settings_init() {
    register_setting( 'skp_options_group', 'skp_options' );

    add_settings_section(
        'skp_styling_section',
        __( 'Pengaturan Tampilan Kalkulator', 'simulasi-kpr-pro' ),
        null,
        'skp_options_group'
    );

    add_settings_field(
        'skp_background_color',
        __( 'Warna Latar Belakang', 'simulasi-kpr-pro' ),
        'skp_color_field_render',
        'skp_options_group',
        'skp_styling_section',
        ['name' => 'background_color', 'default' => '#f9f9f9']
    );

    add_settings_field(
        'skp_heading_color',
        __( 'Warna Teks Judul (Heading)', 'simulasi-kpr-pro' ),
        'skp_color_field_render',
        'skp_options_group',
        'skp_styling_section',
        ['name' => 'heading_color', 'default' => '#2c3e50']
    );

    add_settings_field(
        'skp_text_color',
        __( 'Warna Teks Utama', 'simulasi-kpr-pro' ),
        'skp_color_field_render',
        'skp_options_group',
        'skp_styling_section',
        ['name' => 'text_color', 'default' => '#34495e']
    );
}
add_action( 'admin_init', 'skp_settings_init' );

// Fungsi untuk merender field input warna
function skp_color_field_render( $args ) {
    $options = get_option( 'skp_options' );
    $name = $args['name'];
    $value = isset( $options[$name] ) ? $options[$name] : $args['default'];
    ?>
    <input type="text" name="skp_options[<?php echo esc_attr( $name ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="skp-color-picker">
    <?php
}

// Tampilkan halaman pengaturan HTML
function skp_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'skp_options_group' );
            do_settings_sections( 'skp_options_group' );
            submit_button( 'Simpan Perubahan' );
            ?>
        </form>
    </div>
    <?php
}

// Muat skrip color picker bawaan WordPress
function skp_admin_enqueue_scripts( $hook ) {
    if ( 'settings_page_simulasi_kpr_pro' != $hook ) {
        return;
    }
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'skp-admin-script', SKP_PLUGIN_URL . 'assets/js/admin-script.js', array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'skp_admin_enqueue_scripts' );