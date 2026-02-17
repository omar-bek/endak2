@if (session('show_user_type_modal'))
    <div class="modal fade" id="userTypeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">إتمام الملف الشخصي</h5>
                </div>

                <div class="modal-body">
                    <form id="userTypeForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">اختر نوع الحساب</label>
                            <select name="user_type" id="user_type" class="form-control">
                                <option value="" disabled selected>اختر نوع الحساب</option>
                                <option value="customer">مستخدم عادي</option>
                                <option value="provider">مزود خدمة</option>
                            </select>
                            <div class="invalid-feedback" id="user_type_error"></div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="terms_check" name="terms">
                            <label class="form-check-label">أوافق على
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">الشروط
                                    والأحكام</a>
                            </label>
                            <div class="invalid-feedback" id="terms_error"></div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button id="submitUserType" class="btn btn-primary w-100">إتمام الملف الشخصي</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal الشروط --}}
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">الشروط والأحكام</h5>
                </div>
                <div class="modal-body">
                    <p>ضع هنا نص الشروط الخاصة بك...</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-dismiss="modal">موافق</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modalEl = document.getElementById('userTypeModal');
            var userTypeModal = new bootstrap.Modal(modalEl);
            userTypeModal.show();

            document.getElementById('submitUserType').addEventListener('click', async function() {
                var userType = document.getElementById('user_type').value;
                var termsChecked = document.getElementById('terms_check').checked;

                document.getElementById('user_type_error').textContent = '';
                document.getElementById('terms_error').textContent = '';

                if (!userType) {
                    document.getElementById('user_type_error').textContent = 'يجب اختيار نوع الحساب';
                    return;
                }

                if (!termsChecked) {
                    document.getElementById('terms_error').textContent = 'يجب الموافقة على الشروط';
                    return;
                }

                var btn = this;
                btn.disabled = true;
                btn.textContent = 'جاري الحفظ...';

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
                            alert('انتهت الجلسة. جاري إعادة تحميل الصفحة...');
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
                                alert(errorData.message || 'التحقق من البيانات فشل');
                            } else {
                                alert(errorData?.message || 'حدث خطأ في الخادم');
                            }
                        } else {
                            // Not JSON - might be HTML error page
                            var text = await res.text().catch(() => '');
                            console.error('Non-JSON error response:', text.substring(0, 500));
                            alert('حدث خطأ في الخادم. يرجى المحاولة مرة أخرى.');
                        }
                        btn.disabled = false;
                        btn.textContent = 'إتمام الملف الشخصي';
                        return;
                    }

                    // Check if response is JSON before parsing
                    if (!contentType || !contentType.includes('application/json')) {
                        var text = await res.text().catch(() => '');
                        console.error('Non-JSON response received:', text.substring(0, 500));
                        alert('حدث خطأ: الاستجابة غير صحيحة. يرجى المحاولة مرة أخرى.');
                        btn.disabled = false;
                        btn.textContent = 'إتمام الملف الشخصي';
                        return;
                    }

                    var data = await res.json();

                    if (data.success) {
                        userTypeModal.hide();
                        alert(data.message || 'تم تحديث نوع الحساب بنجاح');
                        setTimeout(() => location.reload(), 700);
                    } else {
                        alert(data.message || 'حدث خطأ');
                        btn.disabled = false;
                        btn.textContent = 'إتمام الملف الشخصي';
                    }

                } catch (err) {
                    console.error('Fetch error:', err);
                    alert('حدث خطأ في الاتصال: ' + (err.message || 'يرجى التحقق من اتصالك بالإنترنت'));
                    btn.disabled = false;
                    btn.textContent = 'إتمام الملف الشخصي';
                }
            });
        });
    </script>
@endif
