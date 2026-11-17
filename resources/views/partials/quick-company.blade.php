{{--
/**
 * ============================================================================
 *
 * @author redfluz
 * @github https://github.com/redfluz
 * @youtube https://youtube.com/@redfluz
 * @contact corex2020@icloud.com
 * @customer
 * @copyright Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */
--}}

<div class="mt-2">
    <button class="btn btn-solid theme-success btn-sm" id="show-quick-company" type="button">
        <i class="me-1" data-lucide="plus"></i>{{ $quickCompanyLabel }}
    </button>
    <div class="d-none mt-2" id="quick-company-panel">
        <div class="form-control form-adorn">
            <label class="visually-hidden" for="quick-company-name"> - </label>
            <input class="form-ghost" id="quick-company-name" maxlength="255"
                placeholder="{{ $quickCompanyNamePlaceholder }}" type="text"
>
        </div>
        <button class="btn btn-solid theme-primary" id="save-quick-company"
            type="button">{{ $quickCompanySaveLabel }}</button>
        <div class="invalid-feedback d-block" id="quick-company-error"></div>
    </div>
</div>

@push('scripts')
    <script>
        const quickCompanyButton = document.getElementById('show-quick-company');
        const quickCompanyPanel = document.getElementById('quick-company-panel');
        const quickCompanyName = document.getElementById('quick-company-name');
        const quickCompanySave = document.getElementById('save-quick-company');
        const quickCompanyError = document.getElementById('quick-company-error');

        quickCompanyButton?.addEventListener('click', () => {
            quickCompanyPanel.classList.toggle('d-none');
            if (!quickCompanyPanel.classList.contains('d-none')) quickCompanyName.focus();
        });
        quickCompanyName?.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') return;

            event.preventDefault();
            quickCompanySave?.click();
        });
        quickCompanySave?.addEventListener('click', async () => {
            const name = quickCompanyName.value.trim();
            quickCompanyError.textContent = '';
            if (!name) {
                quickCompanyError.textContent = @json($quickCompanyRequiredMessage);
                return;
            }

            quickCompanySave.disabled = true;
            try {
                const response = await fetch(@json($quickCompanyRoute), {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        name
                    }),
                });
                const result = await response.json();
                if (!response.ok) {
                    quickCompanyError.textContent = result.errors?.name?.[0] || result.message;
                    return;
                }

                const companySelect = document.getElementById('company_id');
                const contactSelect = document.getElementById('contact_id');
                companySelect.add(new Option(result.name, result.id, true, true));
                if (contactSelect) contactSelect.value = '';
                companySelect.dispatchEvent(new Event('change'));
                quickCompanyName.value = '';
                quickCompanyPanel.classList.add('d-none');
            } catch (error) {
                quickCompanyError.textContent = @json($quickCompanyFailedMessage);
            } finally {
                quickCompanySave.disabled = false;
            }
        });
    </script>
@endpush
