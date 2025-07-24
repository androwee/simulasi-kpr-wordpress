=== Simulasi KPR Pro ===
Contributors: andro
Tags: kpr, mortgage, calculator, simulasi, kredit, pinjaman, pdf, hitung kpr, kustomisasi
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin simulasi KPR lengkap dengan detail angsuran, fitur unduh PDF, dan opsi kustomisasi tampilan.

== Description ==

Plugin **Simulasi KPR Pro** memudahkan pengunjung situs Anda untuk menghitung estimasi angsuran bulanan Kredit Pemilikan Rumah (KPR). Cukup dengan memasukkan harga properti, uang muka, suku bunga, dan jangka waktu, pengunjung bisa langsung mendapatkan rincian perhitungan yang lengkap dan transparan.

Fitur Utama:
* Kalkulasi **angsuran bulanan** yang akurat.
* Menampilkan **ringkasan pembiayaan**: total pinjaman, total bunga, dan total pembayaran.
* Tabel **detail jadwal angsuran** bulan per bulan yang rinci.
* Fitur **Unduh ke PDF** 📄 untuk menyimpan hasil simulasi secara offline.
* **Opsi Kustomisasi Warna** 🎨 untuk mengubah warna latar belakang, judul, dan teks agar sesuai dengan tema situs Anda.
* Antarmuka yang bersih, modern, dan responsif.
* Mudah digunakan dengan shortcode `[simulasi_kpr]`.

Plugin ini sangat cocok untuk situs properti, agen real estate, atau blog finansial.

== Installation ==

1.  Unggah folder `simulasi-kpr-pro` ke direktori `/wp-content/plugins/` Anda.
2.  Aktifkan plugin melalui menu 'Plugins' di dasbor WordPress.
3.  Letakkan shortcode `[simulasi_kpr]` pada halaman atau postingan mana pun yang Anda inginkan.
4.  (Opsional) Untuk mengubah warna, pergi ke **Pengaturan > Simulasi KPR Pro** di dasbor Anda dan sesuaikan dengan keinginan.

== Frequently Asked Questions ==

= Bagaimana cara menampilkan kalkulator? =
Sangat mudah! Cukup salin dan tempel shortcode `[simulasi_kpr]` ke dalam editor halaman atau postingan Anda.

= Di mana saya bisa mengubah warna kalkulator? =
Anda bisa menemukan semua opsi kustomisasi di dasbor WordPress, pada menu **Pengaturan > Simulasi KPR Pro**.

== Changelog ==

= 1.0.1 =
* **FITUR**: Menambahkan halaman pengaturan untuk kustomisasi warna (latar belakang, judul, dan teks).
* **PERBAIKAN**: Memperbaiki fungsi tombol unduh PDF yang tidak bekerja dengan memastikan library `jspdf-autotable` dimuat dengan benar.
* **Pembaruan**: Mengorganisir file dengan menambahkan halaman menu admin.

= 1.0.0 =
* Rilis perdana plugin Simulasi KPR Pro.
* Fitur kalkulasi, detail jadwal angsuran, dan unduh hasil dalam format PDF.

== Credits ==

Plugin ini menggunakan beberapa library open-source yang luar biasa. Terima kasih banyak kepada para pengembangnya:
* **jsPDF** - Library untuk membuat file PDF di sisi klien. (c) James Hall, https://github.com/parallax/jsPDF
* **jsPDF-AutoTable** - Plugin untuk jsPDF yang memudahkan pembuatan tabel. (c) Simon Bengtsson, https://github.com/simonbengtsson/jsPDF-AutoTable