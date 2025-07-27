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
        
        // Library Tailwind CSS
        wp_enqueue_script( 'skp-tailwind-css', 'https://cdn.tailwindcss.com', array(), '3.4.4', false );

        // Menghapus style lama
        // wp_enqueue_style( 'skp-style', SKP_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.1' );
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
    <div id="skp-calculator-wrapper" class="bg-gray-50 p-6 sm:p-8 rounded-xl shadow-lg max-w-3xl mx-auto font-sans">
        <div class="form-section">
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-gray-800 mb-6">🏠 Simulasi KPR</h2>

            <div class="mb-5">
                <label for="skp-harga-properti" class="block text-sm font-medium text-gray-700 mb-1">Harga Properti (Rp)</label>
                <input type="text" id="skp-harga-properti" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 skp-money" placeholder="Contoh: 800.000.000">
            </div>

            <div class="mb-5">
                <label for="skp-uang-muka" class="block text-sm font-medium text-gray-700 mb-2">Uang Muka</label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="skp-uang-muka-slider" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" min="10" max="90" value="20">
                    <input type="number" id="skp-uang-muka" class="w-24 p-2 border border-gray-300 rounded-lg text-center" value="20">
                    <span class="text-gray-500">%</span>
                </div>
            </div>

            <div class="mb-5 bg-blue-50 p-4 rounded-lg text-center">
                <label class="block text-sm font-medium text-gray-600">Jumlah Pinjaman (Pokok Hutang)</label>
                <p id="skp-jumlah-pinjaman-display" class="text-xl font-semibold text-gray-800 mt-1">Rp 0</p>
            </div>

            <div class="mb-5">
                <label for="skp-suku-bunga" class="block text-sm font-medium text-gray-700 mb-2">Suku Bunga per Tahun</label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="skp-suku-bunga-slider" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" min="3" max="15" step="0.1" value="7.5">
                    <input type="number" id="skp-suku-bunga" class="w-24 p-2 border border-gray-300 rounded-lg text-center" step="0.1" value="7.5">
                     <span class="text-gray-500">%</span>
                </div>
            </div>

            <div class="mb-6">
                <label for="skp-jangka-waktu" class="block text-sm font-medium text-gray-700 mb-2">Jangka Waktu</label>
                <div class="flex items-center space-x-4">
                    <input type="range" id="skp-jangka-waktu-slider" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" min="1" max="30" value="15">
                    <input type="number" id="skp-jangka-waktu" class="w-24 p-2 border border-gray-300 rounded-lg text-center" value="15">
                    <span class="text-gray-500">Tahun</span>
                </div>
            </div>

            <button id="skp-hitung-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out">
                Hitung Simulasi
            </button>
        </div>
        
        <div id="skp-hasil-section" class="mt-8 pt-6 border-t-2 border-dashed border-gray-200" style="display:none;">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Hasil Perhitungan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <span class="text-sm text-gray-500">Angsuran per Bulan</span>
                    <strong id="skp-hasil-angsuran" class="block text-lg font-semibold text-gray-800 mt-1"></strong>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <span class="text-sm text-gray-500">Total Pinjaman</span>
                    <strong id="skp-hasil-total-pinjaman" class="block text-lg font-semibold text-gray-800 mt-1"></strong>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <span class="text-sm text-gray-500">Total Bunga</span>
                    <strong id="skp-hasil-total-bunga" class="block text-lg font-semibold text-gray-800 mt-1"></strong>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <span class="text-sm text-gray-500">Total Pembayaran</span>
                    <strong id="skp-hasil-total-bayar" class="block text-lg font-semibold text-gray-800 mt-1"></strong>
                </div>
            </div>
            <button id="skp-download-pdf-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out mb-6">
                📄 Unduh Hasil (PDF)
            </button>
            
            <h4 class="text-lg font-bold text-gray-800 mb-3">Detail Jadwal Angsuran</h4>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <div class="max-h-96 overflow-y-auto">
                    <table id="skp-tabel-angsuran" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan ke-</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran Pokok</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran Bunga</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Angsuran</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Pokok</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Mengembalikan output buffer
    return ob_get_clean();
}
add_shortcode( 'simulasi_kpr', 'skp_kalkulator_shortcode' );