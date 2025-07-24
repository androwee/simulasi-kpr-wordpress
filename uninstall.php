<?php
/**
 * File yang dijalankan saat plugin Simulasi KPR Pro dihapus.
 *
 * @link       https://example.com
 * @since      1.0.0
 * @package    Simulasi_KPR_Pro
 */

// Jika uninstall tidak dipanggil dari WordPress, keluar.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Hapus opsi dari tabel wp_options jika ada
// Contoh: delete_option( 'skp_nama_opsi' );

// Hapus data kustom lainnya jika ada
// global $wpdb;
// $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}nama_tabel_kustom" );