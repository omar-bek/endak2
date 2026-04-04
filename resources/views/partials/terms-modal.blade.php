<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header"
                style="background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light)); color: #fff;">
                <h5 class="modal-title" id="termsModalLabel"> {{ __('messages.terms_title') }} </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4"
                style="max-height: 70vh; overflow-y: auto; background-color: #f9fbfc;">
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

                <p class="fw-semibold mt-4 text-center text-primary">{{ __('messages.terms_agree') }}
                </p>
            </div>

            <div class="modal-footer border-0 d-flex justify-content-center"
                style="background: #f9fbfc;">
                <button type="button" class="btn text-white px-4"
                    style="background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));"
                    data-bs-dismiss="modal">{{ __('messages.terms_modal_close') }}</button>
            </div>
        </div>
    </div>
</div>
