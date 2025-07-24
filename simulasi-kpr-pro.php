<?php
/**
 * Plugin Name:       Simulasi KPR Pro
 * Plugin URI:        https://github.com/androwee/simulasi-kpr-wordpress
 * Description:       Plugin untuk simulasi perhitungan KPR dengan fitur detail angsuran dan unduh hasil dalam format PDF.
 * Version:           1.0.1
 * Author:            andro
 * Author URI:        https://github.com/androwee/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simulasi-kpr-pro
 * Domain Path:       /languages
 */

// Mencegah akses langsung ke file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Definisikan konstanta untuk path dan URL plugin agar mudah diakses
define( 'SKP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SKP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Memuat file shortcode yang menampilkan kalkulator di front-end
require_once SKP_PLUGIN_PATH . 'includes/shortcode-kalkulator.php';

// Memuat file menu admin hanya jika pengguna berada di area admin
if ( is_admin() ) {
    require_once SKP_PLUGIN_PATH . 'includes/admin-menu.php';
}

?>