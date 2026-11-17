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
    'title' => 'System Settings',
    'subtitle' => 'Manage company details, contact information, logo, and address.',
    'read_only' => 'This page is read-only. Use the Edit tab to change settings.',
    'section' => [
        'company' => 'Company',
        'address' => 'Address',
        'document' => 'Document',
    ],
    'fields' => [
        'mobile' => 'Mobile',
        'postcode' => 'Postcode',
        'city' => 'City',
        'state' => 'State',
        'country' => 'Country',
    ],
    'help' => [
        'short_date_format' => 'Used for dates without time (e.g. invoice date).',
        'long_date_format' => 'Used for timestamps (e.g. posted at).',
    ],
    'tabs' => [
        'general' => 'General Settings',
        'logo' => 'Logo',
        'company_tax' => 'Company Tax Information',
        'rma' => 'RMA Settings',
        'mail' => 'Mail Settings',
    ],
    'logo_help' => 'Upload a company logo that appears on printed documents and invoices.',
    'logo_empty' => 'No logo uploaded yet.',
    'logo_upload_help' => 'Accepts JPG, PNG or WebP. The logo is resized automatically.',
    'tax_information_help' => 'Values in this section are used to fill the official LHDN Form E and CP8D PDFs.',
    'tax_field_help' => [
        'tax_id_type_code' => 'Enter the LHDN code for the employer TIN identification type shown on the official form.',
        'tax_agent_approval_no' => 'Enter letters and numbers only; the slash separators are already printed in the official B2 field.',
        'employer_category' => '1 = Government; 2 = Statutory Body; 3 = Local Authority; 4 = Private - Company; 5 = Private - Other; 6 = Special Employer.',
        'employer_status' => '1 = Operating; 2 = Dormant; 3 = In the process of winding up.',
        'cp8d_submission_method' => '1 = Online submission; 2 = Other approved submission method.',
    ],
    'tax_agent_information' => 'Tax Agent Information',
    'declaration_information' => 'Declaration Information',
    'tax_fields' => [
        'company_registration_no' => 'Company Registration No.',
        'company_tax_no' => 'Employer TIN',
        'tax_id_type_code' => 'Tax Identification Code',
        'employer_category' => 'Employer Category (1-6)',
        'employer_status' => 'Employer Status (1-3)',
        'lhdn_state' => 'LHDN State',
        'company_postcode' => 'Postcode',
        'company_city' => 'City',
        'company_state' => 'State',
        'company_country' => 'Country',
        'company_mobile' => 'Mobile Phone',
        'cp8d_submission_method' => 'CP8D Submission Method (1-2)',
        'tax_agent_name' => 'Tax Agent Name',
        'tax_agent_approval_no' => 'Tax Agent Approval No.',
        'tax_agent_firm_name' => 'Tax Agent Firm Name',
        'tax_agent_firm_address' => 'Tax Agent Firm Address',
        'tax_agent_firm_postcode' => 'Tax Agent Firm Postcode',
        'tax_agent_firm_city' => 'Tax Agent Firm City',
        'tax_agent_firm_state' => 'Tax Agent Firm State',
        'tax_agent_firm_tin' => 'Tax Agent Firm TIN',
        'tax_agent_phone' => 'Tax Agent Phone',
        'tax_agent_email' => 'Tax Agent Email',
        'tax_agent_signature_text' => 'Tax Agent Signature Name',
        'tax_agent_signature_date' => 'Tax Agent Signature Date',
        'tax_declarant_name' => 'Declarant Name',
        'tax_declarant_id_passport' => 'Declarant ID / Passport',
        'tax_declarant_position' => 'Declarant Position',
        'tax_declarant_signature_text' => 'Declarant Signature Name',
    ],
    'table' => [
        'column_company_name' => 'Name',
        'column_company_email' => 'Email',
        'column_company_phone' => 'Phone',
        'column_company_fax' => 'Fax',
        'column_company_website' => 'Website',
        'column_timezone' => 'Timezone',
        'column_currency' => 'Currency',
        'column_company_registration_no' => 'Registration No.',
        'column_company_tax_no' => 'Tax No.',
        'column_logo_path' => 'Logo',
        'column_address' => 'Address',
        'column_short_date_format' => 'Short Date Format',
        'column_long_date_format' => 'Long Date Format',
        'column_rma_warranty_months' => 'Default Product Warranty (Months)',
        'column_rma_role' => 'RMA Assignment Role',
    ],
    'rma_warranty_help' => 'Used as the default when creating a product. Each product and Sales Order line may override it.',
    'rma_warranty_unlimited' => 'Default new products to unlimited warranty',
    'rma_role_help' => 'Users with this role can be selected for RMA inspection and repair work. RMA permissions still control who may assign or approve the work.',
    'mail_settings_help' => 'Configure the mail transport used by the application. These settings override the .env mail configuration at runtime.',
    'mail_fields' => [
        'mail_mailer' => 'Mail Mailer',
        'mail_host' => 'Mail Host',
        'mail_port' => 'Mail Port',
        'mail_username' => 'Username',
        'mail_password' => 'Password',
        'mail_encryption' => 'Encryption',
        'mail_from_address' => 'From Address',
        'mail_from_name' => 'From Name',
    ],
    'mail_password_placeholder' => 'Leave blank to keep the current password',
    'mail_password_help' => 'The password is stored encrypted. Leave blank to keep the existing value.',
    'mail_from_name_placeholder' => 'Company Name',
];
