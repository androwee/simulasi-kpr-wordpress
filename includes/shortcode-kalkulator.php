<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Mencegah akses langsung
}

/**
 * Mendaftarkan dan memuat skrip & style untuk front-end.
 */
function skp_enqueue_assets() {
    // Hanya muat skrip jika shortcode ada di halaman
    if ( is_a( get_post( get_the_ID() ), 'WP_Post' ) && has_shortcode( get_post( get_the_ID() )->post_content, 'simulasi_kpr' ) ) {
        
        // Library jsPDF untuk membuat PDF
        wp_enqueue_script( 'skp-jspdf', SKP_PLUGIN_URL . 'assets/js/jspdf.umd.min.js', array(), '2.5.1', true );
        
        // Library jsPDF-AutoTable untuk membuat tabel di PDF
        wp_enqueue_script( 'skp-jspdf-autotable', SKP_PLUGIN_URL . 'assets/js/jspdf.plugin.autotable.min.js', array('skp-jspdf'), '3.8.2', true );

        // Skrip utama, sekarang bergantung pada 'skp-jspdf-autotable'
        wp_enqueue_script( 'skp-script', SKP_PLUGIN_URL . 'assets/js/script.js', array( 'jquery', 'skp-jspdf-autotable' ), '1.0.1', true );
        
        // Style CSS
        wp_enqueue_style( 'skp-style', SKP_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.1' );
    }
}
add_action( 'wp_enqueue_scripts', 'skp_enqueue_assets' );

/**
 * Fungsi utama untuk menampilkan HTML kalkulator melalui shortcode.
 */
function skp_kalkulator_shortcode() {
    // Ambil opsi warna yang disimpan dari database
    $options = get_option('skp_options');
    $bg_color = !empty($options['background_color']) ? esc_attr($options['background_color']) : '#f9f9f9';
    $heading_color = !empty($options['heading_color']) ? esc_attr($options['heading_color']) : '#2c3e50';
    $text_color = !empty($options['text_color']) ? esc_attr($options['text_color']) : '#34495e';

    // Mulai output buffering
    ob_start();
    ?>
    <style>
        .skp-calculator-wrapper {
            background-color: <?php echo $bg_color; ?>;
        }
        .skp-calculator-wrapper h2, 
        .skp-calculator-wrapper h3, 
        .skp-calculator-wrapper h4 {
            color: <?php echo $heading_color; ?>;
        }
        .skp-calculator-wrapper,
        .skp-calculator-wrapper label,
        .skp-calculator-wrapper .skp-hasil-item strong {
            color: <?php echo $text_color; ?>;
        }
    </style>

    <div id="skp-calculator-wrapper" class="skp-calculator-wrapper">
        <div class="skp-form-section">
            <h2>🏠 Simulasi Kredit Pemilikan Rumah (KPR)</h2>
            <div class="skp-form-group">
                <label for="skp-harga-properti">Harga Properti (Rp)</label>
                <input type="text" id="skp-harga-properti" class="skp-input skp-money" placeholder="cth: 800.000.000">
            </div>
            <div class="skp-form-group">
                <label for="skp-uang-muka">Uang Muka (%)</label>
                <input type="number" id="skp-uang-muka" class="skp-input" value="20" placeholder="cth: 20">
            </div>
            <div class="skp-form-group">
                <label>Jumlah Pinjaman (Pokok Hutang)</label>
                <p id="skp-jumlah-pinjaman-display" class="skp-calculated-field">Rp 0</p>
            </div>
            <div class="skp-form-group">
                <label for="skp-suku-bunga">Suku Bunga per Tahun (%)</label>
                <input type="number" id="skp-suku-bunga" class="skp-input" step="0.1" value="7.5" placeholder="cth: 7.5">
            </div>
            <div class="skp-form-group">
                <label for="skp-jangka-waktu">Jangka Waktu (Tahun)</label>
                <input type="number" id="skp-jangka-waktu" class="skp-input" value="15" placeholder="cth: 15">
            </div>
            <button id="skp-hitung-btn" class="skp-button">Hitung Simulasi</button>
        </div>
        
        <div id="skp-hasil-section" class="skp-hasil-section" style="display:none;">
            <h3>Hasil Perhitungan</h3>
            <div class="skp-hasil-ringkasan">
                <div class="skp-hasil-item">
                    <span>Angsuran per Bulan</span>
                    <strong id="skp-hasil-angsuran"></strong>
                </div>
                <div class="skp-hasil-item">
                    <span>Total Pinjaman</span>
                    <strong id="skp-hasil-total-pinjaman"></strong>
                </div>
                <div class="skp-hasil-item">
                    <span>Total Bunga</span>
                    <strong id="skp-hasil-total-bunga"></strong>
                </div>
                <div class="skp-hasil-item">
                    <span>Total Pembayaran</span>
                    <strong id="skp-hasil-total-bayar"></strong>
                </div>
            </div>
            <button id="skp-download-pdf-btn" class="skp-button skp-button-secondary">📄 Unduh Hasil (PDF)</button>
            
            <h4>Detail Jadwal Angsuran</h4>
            <div class="skp-tabel-wrapper">
                <table id="skp-tabel-angsuran">
                    <thead>
                        <tr>
                            <th>Bulan ke-</th>
                            <th>Angsuran Pokok</th>
                            <th>Angsuran Bunga</th>
                            <th>Total Angsuran</th>
                            <th>Sisa Pokok</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    // Mengembalikan output buffer
    return ob_get_clean();
}
add_shortcode( 'simulasi_kpr', 'skp_kalkulator_shortcode' );