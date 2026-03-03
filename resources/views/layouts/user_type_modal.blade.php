@if (session('show_user_type_modal'))
    <div class="modal fade" id="userTypeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ __('messages.complete_profile') }}</h5>
                </div>

                <div class="modal-body">
                    <form id="userTypeForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.select_account_type') }}</label>
                            <select name="user_type" id="user_type" class="form-control">
                                <option value="" disabled selected>{{ __('messages.select_account_type') }}
                                </option>
                                <option value="customer">{{ __('messages.user_regular') }}</option>
                                <option value="provider">{{ __('messages.user_provider') }}</option>
                            </select>
                            <div class="invalid-feedback" id="user_type_error"></div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms_check" name="terms">
                            <label class="form-check-label">{{ __('messages.terms_accept') }}
                                <a href="#" class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#termsModal">{{ __('messages.terms_conditions') }}</a>
                            </label>
                            <div class="invalid-feedback" id="terms_error"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button id="submitUserType"
                        class="btn btn-primary w-100">{{ __('messages.complete_profile') }}</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal الشروط --}}
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background: linear-gradient(135deg, #2f5c69, #3c7d8b); color: #fff;">
                    <h5 class="modal-title">{{ __('messages.terms_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto; background-color: #f9fbfc;">
                    <h6 class="fw-bold mb-2 text-primary">{{ __('messages.terms_welcome') }}</h6>
                    <p class="text-muted mb-4">{{ __('messages.terms_intro') }}</p>

                    <h6 class="fw-bold">{{ __('messages.terms_1_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_1_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_2_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_2_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_3_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_3_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_4_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_4_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_5_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_5_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_6_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_6_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_7_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_7_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_8_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_8_text') }}</p>

                    <h6 class="fw-bold mt-4">{{ __('messages.terms_9_title') }}</h6>
                    <p class="text-muted">{{ __('messages.terms_9_text') }}</p>

                    <p class="fw-semibold mt-4 text-center text-primary">{{ __('messages.terms_agree') }}</p>
                </div>
                <div class="modal-footer border-0 d-flex justify-content-center" style="background: #f9fbfc;">
                    <button type="button" class="btn text-white px-4"
                        style="background: linear-gradient(135deg, #2f5c69, #3c7d8b);"
                        data-bs-dismiss="modal">{{ __('messages.terms_modal_close') }}</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('userTypeModal');
            if (modalEl) {
                var userTypeModal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                // إظهار المودال تلقائياً
                userTypeModal.show();

                // منع إغلاق المودال بالنقر خارجيه
                modalEl.addEventListener('hide.bs.modal', function(event) {
                    // يمكن إضافة منطق هنا لمنع الإغلاق إذا لزم الأمر
                });
            }

            document.getElementById('submitUserType').addEventListener('click', async function() {
                var userType = document.getElementById('user_type').value;
                var termsChecked = document.getElementById('terms_check').checked;

                document.getElementById('user_type_error').textContent = '';
                document.getElementById('terms_error').textContent = '';

                if (!userType) {
                    document.getElementById('user_type_error').textContent =
                        '{{ __('messages.user_type_required') }}';
                    document.getElementById('user_type').classList.add('is-invalid');
                    return;
                } else {
                    document.getElementById('user_type').classList.remove('is-invalid');
                }

                if (!termsChecked) {
                    document.getElementById('terms_error').textContent =
                        '{{ __('messages.terms_required') }}';
                    document.getElementById('terms_check').classList.add('is-invalid');
                    return;
                } else {
                    document.getElementById('terms_check').classList.remove('is-invalid');
                }

                var btn = this;
                btn.disabled = true;
                btn.textContent = '{{ __('messages.loading') }}';

                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content');

                var formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('user_type', userType);
                formData.append('terms', termsChecked ? '1' : '0');

                try {
                    var res = await fetch('{{ route('save-user-type') }}', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    // Check content type first
                    var contentType = res.headers.get('content-type');
                    console.log('Response status:', res.status);
                    console.log('Response content-type:', contentType);

                    // Check if response is ok
                    if (!res.ok) {
                        // Handle CSRF error (419)
                        if (res.status === 419) {
                            alert('{{ __('messages.session_expired') }}');
                            window.location.reload();
                            return;
                        }

                        // Check if response is JSON
                        if (contentType && contentType.includes('application/json')) {
                            // Try to parse error response
                            var errorData = await res.json().catch(() => null);
                            if (errorData && errorData.errors) {
                                // Show validation errors
                                if (errorData.errors.user_type) {
                                    document.getElementById('user_type_error').textContent = errorData
                                        .errors.user_type[0];
                                    document.getElementById('user_type').classList.add('is-invalid');
                                }
                                if (errorData.errors.terms) {
                                    document.getElementById('terms_error').textContent = errorData
                                        .errors
                                        .terms[0];
                                    document.getElementById('terms_check').classList.add('is-invalid');
                                }
                                alert(errorData.message || '{{ __('messages.validation_failed') }}');
                            } else {
                                alert(errorData?.message || '{{ __('messages.server_error') }}');
                            }
                        } else {
                            // Not JSON - might be HTML error page
                            var text = await res.text().catch(() => '');
                            console.error('Non-JSON error response:', text.substring(0, 500));
                            alert('{{ __('messages.server_error_try_again') }}');
                        }
                        btn.disabled = false;
                        btn.textContent = '{{ __('messages.complete_profile') }}';
                        return;
                    }

                    // Check if response is JSON before parsing
                    if (!contentType || !contentType.includes('application/json')) {
                        var text = await res.text().catch(() => '');
                        console.error('Non-JSON response received:', text.substring(0, 500));
                        alert('{{ __('messages.invalid_response') }}');
                        btn.disabled = false;
                        btn.textContent = '{{ __('messages.complete_profile') }}';
                        return;
                    }

                    var data = await res.json();

                    if (data.success) {
                        userTypeModal.hide();
                        alert(data.message || '{{ __('messages.profile_updated_success') }}');
                        setTimeout(() => location.reload(), 700);
                    } else {
                        alert(data.message || '{{ __('messages.error_occurred') }}');
                        btn.disabled = false;
                        btn.textContent = '{{ __('messages.complete_profile') }}';
                    }

                } catch (err) {
                    console.error('Fetch error:', err);
                    alert('{{ __('messages.connection_error') }}: ' + (err.message ||
                        '{{ __('messages.check_internet') }}'));
                    btn.disabled = false;
                    btn.textContent = '{{ __('messages.complete_profile') }}';
                }
            });
        });
    </script>
@endif
