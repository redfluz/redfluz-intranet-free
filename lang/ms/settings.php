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
    'title' => 'Tetapan Sistem',
    'subtitle' => 'Urus maklumat syarikat, maklumat hubungan, logo, dan alamat.',
    'read_only' => 'Halaman ini adalah baca sahaja. Gunakan tab Sunting untuk mengubah tetapan.',
    'section' => [
        'company' => 'Syarikat',
        'address' => 'Alamat',
        'document' => 'Dokumen',
    ],
    'fields' => [
        'mobile' => 'Mudah Alih',
        'postcode' => 'Poskod',
        'city' => 'Bandar',
        'state' => 'Negeri',
        'country' => 'Negara',
    ],
    'help' => [
        'short_date_format' => 'Digunakan untuk tarikh tanpa masa (cth. tarikh invois).',
        'long_date_format' => 'Digunakan untuk cap masa (cth. disiarkan pada).',
    ],
    'tabs' => [
        'general' => 'Tetapan Umum',
        'logo' => 'Logo',
        'company_tax' => 'Maklumat Cukai Syarikat',
        'rma' => 'Tetapan RMA',
        'mail' => 'Tetapan E-mel',
    ],
    'logo_help' => 'Muat naik logo syarikat yang dipaparkan pada dokumen dan invois bercetak.',
    'logo_empty' => 'Tiada logo dimuat naik lagi.',
    'logo_upload_help' => 'Menerima JPG, PNG atau WebP. Logo akan diubah saiz secara automatik.',
    'tax_information_help' => 'Maklumat dalam bahagian ini digunakan untuk mengisi PDF rasmi Borang E dan CP8D LHDN.',
    'tax_field_help' => [
        'tax_id_type_code' => 'Masukkan kod LHDN untuk jenis pengenalan TIN majikan seperti yang ditunjukkan dalam borang rasmi.',
        'tax_agent_approval_no' => 'Masukkan huruf dan nombor sahaja; tanda garis miring telah dicetak dalam ruangan rasmi B2.',
        'employer_category' => '1 = Kerajaan; 2 = Berkanun; 3 = Pihak Berkuasa Tempatan; 4 = Swasta - Syarikat; 5 = Swasta - Selain Syarikat; 6 = Majikan Khas.',
        'employer_status' => '1 = Beroperasi; 2 = Dormant; 3 = Dalam proses penggulungan.',
        'cp8d_submission_method' => '1 = Pengemukaan dalam talian; 2 = Kaedah pengemukaan lain yang diluluskan.',
    ],
    'tax_agent_information' => 'Maklumat Ejen Cukai',
    'declaration_information' => 'Maklumat Pengakuan',
    'tax_fields' => [
        'company_registration_no' => 'No. Pendaftaran Syarikat',
        'company_tax_no' => 'TIN Majikan',
        'tax_id_type_code' => 'Kod Pengenalan Cukai',
        'employer_category' => 'Kategori Majikan (1-6)',
        'employer_status' => 'Status Majikan (1-3)',
        'lhdn_state' => 'Negeri LHDN',
        'company_postcode' => 'Poskod',
        'company_city' => 'Bandar',
        'company_state' => 'Negeri',
        'company_country' => 'Negara',
        'company_mobile' => 'Telefon Bimbit',
        'cp8d_submission_method' => 'Cara Pengemukaan CP8D (1-2)',
        'tax_agent_name' => 'Nama Ejen Cukai',
        'tax_agent_approval_no' => 'No. Kelulusan Ejen Cukai',
        'tax_agent_firm_name' => 'Nama Firma Ejen Cukai',
        'tax_agent_firm_address' => 'Alamat Firma Ejen Cukai',
        'tax_agent_firm_postcode' => 'Poskod Firma Ejen Cukai',
        'tax_agent_firm_city' => 'Bandar Firma Ejen Cukai',
        'tax_agent_firm_state' => 'Negeri Firma Ejen Cukai',
        'tax_agent_firm_tin' => 'TIN Firma Ejen Cukai',
        'tax_agent_phone' => 'Telefon Ejen Cukai',
        'tax_agent_email' => 'E-mel Ejen Cukai',
        'tax_agent_signature_text' => 'Nama Tandatangan Ejen Cukai',
        'tax_agent_signature_date' => 'Tarikh Tandatangan Ejen Cukai',
        'tax_declarant_name' => 'Nama Pengaku',
        'tax_declarant_id_passport' => 'No. Pengenalan / Pasport Pengaku',
        'tax_declarant_position' => 'Jawatan Pengaku',
        'tax_declarant_signature_text' => 'Nama Tandatangan Pengaku',
    ],
    'table' => [
        'column_company_name' => 'Nama',
        'column_company_email' => 'E-mel',
        'column_company_phone' => 'Nombor',
        'column_company_fax' => 'Faks',
        'column_company_website' => 'Laman Web',
        'column_timezone' => 'Zon Masa',
        'column_currency' => 'Mata Wang',
        'column_company_registration_no' => 'No. Pendaftaran',
        'column_company_tax_no' => 'No. Cukai',
        'column_logo_path' => 'Logo',
        'column_address' => 'Alamat',
        'column_short_date_format' => 'Format Tarikh Pendek',
        'column_long_date_format' => 'Format Tarikh Panjang',
        'column_rma_warranty_months' => 'Waranti Produk Tetapan (Bulan)',
        'column_rma_role' => 'Peranan Tugasan RMA',
    ],
    'rma_warranty_help' => 'Digunakan sebagai tetapan apabila mencipta produk. Setiap produk dan baris Pesanan Jualan boleh mengubahnya.',
    'rma_warranty_unlimited' => 'Tetapkan produk baharu kepada waranti tanpa had',
    'rma_role_help' => 'Pengguna dengan peranan ini boleh dipilih untuk pemeriksaan dan pembaikan RMA. Kebenaran RMA masih menentukan siapa yang boleh menugaskan atau meluluskan kerja.',
    'mail_settings_help' => 'Konfigurasi pengangkutan e-mel yang digunakan oleh aplikasi. Tetapan ini mengatasi konfigurasi e-mel .env pada masa jalan.',
    'mail_fields' => [
        'mail_mailer' => 'Pengirim E-mel',
        'mail_host' => 'Hos E-mel',
        'mail_port' => 'Port E-mel',
        'mail_username' => 'Nama Pengguna',
        'mail_password' => 'Kata Laluan',
        'mail_encryption' => 'Enkripsi',
        'mail_from_address' => 'Alamat Pengirim',
        'mail_from_name' => 'Nama Pengirim',
    ],
    'mail_password_placeholder' => 'Biarkan kosong untuk mengekalkan kata laluan semasa',
    'mail_password_help' => 'Kata laluan disimpan secara tersulit. Biarkan kosong untuk mengekalkan nilai sedia ada.',
    'mail_from_name_placeholder' => 'Nama Syarikat',
];
