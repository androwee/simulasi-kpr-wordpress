jQuery(document).ready(function ($) {
    // Fungsi untuk menyinkronkan slider dan input number
    function syncSliderAndInput(sliderId, inputId) {
        const slider = $(sliderId);
        const input = $(inputId);

        slider.on('input', function() {
            input.val($(this).val());
            calculateLoanAmount(); // Panggil kalkulasi ulang saat slider digerakkan
        });

        input.on('input', function() {
            slider.val($(this).val());
            calculateLoanAmount(); // Panggil kalkulasi ulang saat input diubah
        });
    }

    syncSliderAndInput('#skp-uang-muka-slider', '#skp-uang-muka');
    syncSliderAndInput('#skp-suku-bunga-slider', '#skp-suku-bunga');
    syncSliderAndInput('#skp-jangka-waktu-slider', '#skp-jangka-waktu');

    // Format input uang saat diketik
    $('.skp-money').on('keyup', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value) {
            $(this).val(new Intl.NumberFormat('id-ID').format(value));
        }
        calculateLoanAmount(); // Panggil kalkulasi ulang saat harga properti diubah
    });

    // Fungsi untuk menghitung jumlah pinjaman
    function calculateLoanAmount() {
        const harga = parseFloat($('#skp-harga-properti').val().replace(/[^0-9]/g, '')) || 0;
        const dp = parseFloat($('#skp-uang-muka').val()) || 0;
        const jumlahPinjaman = harga - (harga * (dp / 100));
        $('#skp-jumlah-pinjaman-display').text(formatRupiah(jumlahPinjaman));
    }

    // Panggil fungsi kalkulasi saat halaman dimuat
    calculateLoanAmount();

    // Fungsi format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Tombol Hitung di-klik
    $('#skp-hitung-btn').on('click', function () {
        const hargaProperti = parseFloat($('#skp-harga-properti').val().replace(/[^0-9]/g, '')) || 0;
        const uangMukaPersen = parseFloat($('#skp-uang-muka').val()) || 0;
        const sukuBunga = parseFloat($('#skp-suku-bunga').val()) || 0;
        const jangkaWaktu = parseInt($('#skp-jangka-waktu').val()) || 0;

        if (hargaProperti <= 0 || sukuBunga <= 0 || jangkaWaktu <= 0) {
            alert('Mohon isi semua data dengan benar.');
            return;
        }

        const pokokPinjaman = hargaProperti - (hargaProperti * (uangMukaPersen / 100));
        const bungaBulanan = sukuBunga / 100 / 12;
        const totalBulan = jangkaWaktu * 12;
        
        // Rumus anuitas
        const angsuranBulanan = pokokPinjaman * bungaBulanan * Math.pow(1 + bungaBulanan, totalBulan) / (Math.pow(1 + bungaBulanan, totalBulan) - 1);
        const totalBayar = angsuranBulanan * totalBulan;
        const totalBunga = totalBayar - pokokPinjaman;

        // Tampilkan hasil ringkasan
        $('#skp-hasil-angsuran').text(formatRupiah(angsuranBulanan));
        $('#skp-hasil-total-pinjaman').text(formatRupiah(pokokPinjaman));
        $('#skp-hasil-total-bunga').text(formatRupiah(totalBunga));
        $('#skp-hasil-total-bayar').text(formatRupiah(totalBayar));

        // Tampilkan tabel detail angsuran
        const tabelBody = $('#skp-tabel-angsuran tbody');
        tabelBody.empty();
        let sisaPokok = pokokPinjaman;

        for (let i = 1; i <= totalBulan; i++) {
            const angsuranBunga = sisaPokok * bungaBulanan;
            const angsuranPokok = angsuranBulanan - angsuranBunga;
            sisaPokok -= angsuranPokok;
            
            // Mengatasi sisa pokok minus di akhir periode
            if (sisaPokok < 0) sisaPokok = 0;

            const row = `
                <tr>
                    <td>${i}</td>
                    <td>${formatRupiah(angsuranPokok)}</td>
                    <td>${formatRupiah(angsuranBunga)}</td>
                    <td>${formatRupiah(angsuranBulanan)}</td>
                    <td>${formatRupiah(sisaPokok)}</td>
                </tr>
            `;
            tabelBody.append(row);
        }

        $('#skp-hasil-section').slideDown();
    });

    // Tombol Download PDF di-klik
    $('#skp-download-pdf-btn').on('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const hargaProperti = $('#skp-harga-properti').val();
        const uangMuka = $('#skp-uang-muka').val();
        const bunga = $('#skp-suku-bunga').val();
        const tenor = $('#skp-jangka-waktu').val();
        
        const angsuranBulanan = $('#skp-hasil-angsuran').text();
        const totalPinjaman = $('#skp-hasil-total-pinjaman').text();
        const totalBunga = $('#skp-hasil-total-bunga').text();
        const totalBayar = $('#skp-hasil-total-bayar').text();
        
        doc.setFontSize(18);
        doc.text("Hasil Simulasi KPR", 14, 22);
        
        doc.setFontSize(11);
        doc.text(`Harga Properti: ${hargaProperti}`, 14, 35);
        doc.text(`Uang Muka: ${uangMuka}%`, 14, 42);
        doc.text(`Suku Bunga: ${bunga}% per tahun`, 14, 49);
        doc.text(`Jangka Waktu: ${tenor} tahun`, 14, 56);
        
        doc.line(14, 60, 196, 60); // Garis pemisah
        
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("Ringkasan Pembiayaan", 14, 68);
        
        doc.setFont('helvetica', 'normal');
        doc.text(`Angsuran per Bulan: ${angsuranBulanan}`, 14, 75);
        doc.text(`Total Pinjaman: ${totalPinjaman}`, 14, 82);
        doc.text(`Total Bunga: ${totalBunga}`, 14, 89);
        doc.text(`Total Pembayaran: ${totalBayar}`, 14, 96);
        
        doc.addPage();
        doc.setFontSize(18);
        doc.text("Detail Jadwal Angsuran", 14, 22);

        const tableData = [];
        $('#skp-tabel-angsuran tr').each(function() {
            const rowData = [];
            $(this).find('th, td').each(function() {
                rowData.push($(this).text());
            });
            tableData.push(rowData);
        });

        doc.autoTable({
            head: [tableData[0]],
            body: tableData.slice(1),
            startY: 30,
            theme: 'grid',
            headStyles: { fillColor: [41, 128, 185] }
        });

        doc.save('Simulasi-KPR.pdf');
        
        // autoTable adalah plugin untuk jsPDF, untuk implementasi yang lebih kuat
        // Anda mungkin perlu menambahkannya. Untuk contoh ini, kita asumsikan
        // ada fungsi dasar untuk tabel atau teks manual.
        // Jika autoTable tidak ada, Anda harus membuat tabel secara manual.
        // Contoh:
        // let y = 110;
        // doc.text("Bulan | Pokok | Bunga | Total | Sisa", 14, y);
        // ... loop dan doc.text() untuk setiap baris
    });

    // Untuk menggunakan autoTable, tambahkan juga library jspdf-autotable.js
    // dan panggil di skp_enqueue_assets().
});