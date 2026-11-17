<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://youtube.com/@redfluz
 *
 * @contact     corex2020@icloud.com
 *
 * @customer
 *
 * @copyright   Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

return [
    'title' => 'Pengaturan Sistem',
    'subtitle' => 'Kelola detail perusahaan, informasi kontak, logo, dan alamat.',
    'read_only' => 'Halaman ini hanya baca. Gunakan tab Sunting untuk mengubah pengaturan.',
    'section' => [
        'company' => 'Perusahaan',
        'address' => 'Alamat',
        'document' => 'Dokumen',
    ],
    'fields' => [
        'mobile' => 'Seluler',
        'postcode' => 'Kode Pos',
        'city' => 'Kota',
        'state' => 'Negara Bagian',
        'country' => 'Negara',
    ],
    'help' => [
        'short_date_format' => 'Digunakan untuk tanggal tanpa waktu (mis. tanggal faktur).',
        'long_date_format' => 'Digunakan untuk stempel waktu (mis. diposting pada).',
    ],
    'tabs' => [
        'general' => 'Pengaturan Umum',
        'logo' => 'Logo',
        'company_tax' => 'Informasi Pajak Perusahaan',
        'rma' => 'Pengaturan RMA',
        'mail' => 'Pengaturan Email',
    ],
    'logo_help' => 'Unggah logo perusahaan yang muncul pada dokumen dan faktur cetak.',
    'logo_empty' => 'Belum ada logo yang diunggah.',
    'logo_upload_help' => 'Menerima JPG, PNG, atau WebP. Logo akan diubah ukurannya secara otomatis.',
    'tax_information_help' => 'Nilai pada bagian ini digunakan untuk mengisi PDF Form E dan CP8D resmi LHDN.',
    'tax_field_help' => [
        'tax_id_type_code' => 'Masukkan kode LHDN untuk jenis identifikasi TIN pemberi kerja yang ditampilkan pada formulir resmi.',
        'tax_agent_approval_no' => 'Masukkan huruf dan angka saja; pemisah garis miring sudah tercetak pada kolom B2 resmi.',
        'employer_category' => '1 = Pemerintah; 2 = Badan Statuta; 3 = Otoritas Lokal; 4 = Swasta - Perusahaan; 5 = Swasta - Lainnya; 6 = Pemberi Kerja Khusus.',
        'employer_status' => '1 = Beroperasi; 2 = Tidak Aktif; 3 = Dalam proses likuidasi.',
        'cp8d_submission_method' => '1 = Pengajuan daring; 2 = Metode pengajuan lain yang disetujui.',
    ],
    'tax_agent_information' => 'Informasi Agen Pajak',
    'declaration_information' => 'Informasi Pernyataan',
    'tax_fields' => [
        'company_registration_no' => 'No. Pendaftaran Perusahaan',
        'company_tax_no' => 'TIN Pemberi Kerja',
        'tax_id_type_code' => 'Kode Identifikasi Pajak',
        'employer_category' => 'Kategori Pemberi Kerja (1-6)',
        'employer_status' => 'Status Pemberi Kerja (1-3)',
        'lhdn_state' => 'Negara Bagian LHDN',
        'company_postcode' => 'Kode Pos',
        'company_city' => 'Kota',
        'company_state' => 'Negara Bagian',
        'company_country' => 'Negara',
        'company_mobile' => 'Telepon Seluler',
        'cp8d_submission_method' => 'Metode Pengajuan CP8D (1-2)',
        'tax_agent_name' => 'Nama Agen Pajak',
        'tax_agent_approval_no' => 'No. Persetujuan Agen Pajak',
        'tax_agent_firm_name' => 'Nama Firma Agen Pajak',
        'tax_agent_firm_address' => 'Alamat Firma Agen Pajak',
        'tax_agent_firm_postcode' => 'Kode Pos Firma Agen Pajak',
        'tax_agent_firm_city' => 'Kota Firma Agen Pajak',
        'tax_agent_firm_state' => 'Negara Bagian Firma Agen Pajak',
        'tax_agent_firm_tin' => 'TIN Firma Agen Pajak',
        'tax_agent_phone' => 'Telepon Agen Pajak',
        'tax_agent_email' => 'Email Agen Pajak',
        'tax_agent_signature_text' => 'Nama Tanda Tangan Agen Pajak',
        'tax_agent_signature_date' => 'Tanggal Tanda Tangan Agen Pajak',
        'tax_declarant_name' => 'Nama Penandatangan',
        'tax_declarant_id_passport' => 'ID / Paspor Penandatangan',
        'tax_declarant_position' => 'Jabatan Penandatangan',
        'tax_declarant_signature_text' => 'Nama Tanda Tangan Penandatangan',
    ],
    'table' => [
        'column_company_name' => 'Nama',
        'column_company_email' => 'Email',
        'column_company_phone' => 'Telepon',
        'column_company_fax' => 'Faks',
        'column_company_website' => 'Situs Web',
        'column_timezone' => 'Zona Waktu',
        'column_currency' => 'Mata Uang',
        'column_company_registration_no' => 'No. Pendaftaran',
        'column_company_tax_no' => 'No. Pajak',
        'column_logo_path' => 'Logo',
        'column_address' => 'Alamat',
        'column_short_date_format' => 'Format Tanggal Pendek',
        'column_long_date_format' => 'Format Tanggal Panjang',
        'column_rma_warranty_months' => 'Garansi Produk Default (Bulan)',
        'column_rma_role' => 'Peran Penugasan RMA',
    ],
    'rma_warranty_help' => 'Digunakan sebagai default saat membuat produk. Setiap produk dan baris Pesanan Penjualan dapat menggantinya.',
    'rma_warranty_unlimited' => 'Jadikan garansi produk baru tanpa batas secara default',
    'rma_role_help' => 'Pengguna dengan peran ini dapat dipilih untuk pekerjaan inspeksi dan perbaikan RMA. Izin RMA tetap mengatur siapa yang dapat menugaskan atau menyetujui pekerjaan.',
    'mail_settings_help' => 'Konfigurasi transport email yang digunakan aplikasi. Pengaturan ini menimpa konfigurasi email .env saat runtime.',
    'mail_fields' => [
        'mail_mailer' => 'Mailer Email',
        'mail_host' => 'Host Email',
        'mail_port' => 'Port Email',
        'mail_username' => 'Nama Pengguna',
        'mail_password' => 'Kata Sandi',
        'mail_encryption' => 'Enkripsi',
        'mail_from_address' => 'Alamat Pengirim',
        'mail_from_name' => 'Nama Pengirim',
    ],
    'mail_password_placeholder' => 'Kosongkan untuk mempertahankan kata sandi saat ini',
    'mail_password_help' => 'Kata sandi disimpan terenkripsi. Kosongkan untuk mempertahankan nilai yang ada.',
    'mail_from_name_placeholder' => 'Nama Perusahaan',
];
