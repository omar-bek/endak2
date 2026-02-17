@extends('layouts.app')

@section('title', 'خدماتي')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h2 class="text-teal">
                    <i class="fas fa-list text-gold"></i>
                    @if(auth()->user()->isProvider())
                           {{ __('messages.services_title_provider') }}
                    @else
                          {{ __('messages.services_title_client') }}
                    @endif
                </h2>
                @if(!auth()->user()->isProvider())
                    <a href="{{ route('categories.index') }}" class="btn btn-gold text-white fw-bold shadow-sm">
                        <i class="fas fa-plus"></i>  {{ __('messages.request_service_new') }}
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
            @endif

            @if($services->count() > 0)
                <div class="row">
                    @foreach($services as $service)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-lg rounded-3 overflow-hidden">
                                
                                    <img src="{{ $service->category->image_url }}"
                                         alt="{{ $service->title }}"
                                         class="card-img-top"
                                         onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';"
                                         style="height: 200px; object-fit: cover;">
                                
                                    
                                

                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold text-teal text-truncate mb-3">{{ $service->title }}</h5>

                                    <p class="text-muted mb-2">
                                        <i class="fas fa-folder text-gold"></i>
                                        {{ $service->category->name }}
                                    </p>

                                    @if($service->from_city)
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-map-marker-alt text-teal"></i>
                                            {{ $service->from_city }}
                                        </p>
                                    @endif

                                    @if(auth()->user()->isProvider())
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-user text-gold"></i>
                                            {{ $service->user->name }}
                                        </p>
                                    @endif

                                    <p class="text-muted mb-3">
                                        <i class="fas fa-calendar text-teal"></i>
                                        {{ $service->created_at->format('Y-m-d') }}
                                    </p>

                                    <div class="mb-3">
                                        @if($service->is_active)
                                            <span class="badge bg-teal text-white px-3 py-2">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">{{ __('messages.inactive') }} </span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
    @php
        $pendingOffers = $service->offers->where('status', 'pending')->count();
        $acceptedOffers = $service->offers->where('status', 'accepted')->count();
        $myOffers = $service->offers->where('provider_id', auth()->id());
        $myPendingOffers = $myOffers->where('status', 'pending')->count();
        $myAcceptedOffers = $myOffers->where('status', 'accepted')->count();
    @endphp

    @if(auth()->user()->isProvider())
        @if($myPendingOffers > 0)
            <span class="badge bg-warning text-dark px-3 py-2">
                <i class="fas fa-clock"></i> {{ __('messages.my_offer_pending') }}
            </span>
        @endif

        @if($myAcceptedOffers > 0)
            <span class="badge bg-teal text-white px-3 py-2">
                <i class="fas fa-check"></i> {{ __('messages.my_offer_accepted') }}
            </span>
        @endif

        @if($myOffers->count() == 0)
            <span class="badge bg-gold text-white px-3 py-2">
                <i class="fas fa-plus"></i> {{ __('messages.you_can_submit_offer') }}
            </span>
        @endif
    @else
        @if($pendingOffers > 0)
            <span class="badge bg-warning text-dark px-3 py-2">
                <i class="fas fa-clock"></i> {{ $pendingOffers }} {{ __('messages.pending_offers_count') }}
            </span>
        @endif

        @if($acceptedOffers > 0)
            <span class="badge bg-teal text-white px-3 py-2">
                <i class="fas fa-check"></i> {{ __('messages.accepted_offer') }}
            </span>
        @endif

        @if($service->offers->count() == 0)
            <span class="badge bg-secondary px-3 py-2">
                <i class="fas fa-inbox"></i> {{ __('messages.no_offers') }}
            </span>
        @endif
    @endif
</div>


                                    @if($service->description)
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($service->description, 100) }}
                                        </p>
                                    @endif
                                </div>

                                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                    <a href="{{ route('services.show', $service->slug) }}"
                                       class="btn btn-outline-teal btn-sm fw-bold">
                                        <i class="fas fa-eye"></i> {{ __('messages.offer') }}
                                    </a>

                                    @if(auth()->user()->isProvider())
                                        @if($myOffers->count() > 0)
                                            <a href="{{ route('service-offers.index', $service) }}"
                                               class="btn btn-outline-gold btn-sm fw-bold">
                                                <i class="fas fa-handshake"></i> {{ __('messages.my_offers') }}
                                                @if($myPendingOffers > 0)
                                                    <span class="badge bg-warning text-dark ms-1">{{ $myPendingOffers }}</span>
                                                @endif
                                            </a>
                                        @else
                                            <a href="{{ route('service-offers.create', $service) }}"
                                               class="btn btn-outline-success btn-sm fw-bold">
                                                <i class="fas fa-plus"></i> {{ __('messages.submit_offer') }} 
                                            </a>
                                        @endif
                                    @else
                                        @if($service->offers->count() > 0)
                                            <a href="{{ route('service-offers.index', $service) }}"
                                               class="btn btn-outline-gold btn-sm fw-bold">
                                                <i class="fas fa-handshake"></i> {{ __('messages.offers') }} 
                                                @if($pendingOffers > 0)
                                                    <span class="badge bg-warning text-dark ms-1">{{ $pendingOffers }}</span>
                                                @endif
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $services->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                    @if(auth()->user()->isProvider())
                        <h4 class="text-muted">{{ __('messages.provider_title') }} </h4>
                        <p class="text-muted mb-4">{{ __('messages.provider_text') }} </p>
                    @else
                        <h4 class="text-muted">{{ __('messages.client_title') }} </h4>
                        <p class="text-muted mb-4">{{ __('messages.client_text') }} </p>
                        <a href="{{ route('categories.index') }}" class="btn btn-gold text-white btn-lg fw-bold shadow-sm">
                            <i class="fas fa-plus"></i> {{ __('messages.client_btn') }} 
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .text-teal { color: #008b8b !important; }
    .bg-teal { background-color: #008b8b !important; }
    .btn-outline-teal {
        color: #008b8b;
        border-color: #008b8b;
    }
    .btn-outline-teal:hover {
        background-color: #008b8b;
        color: #fff;
    }
    .text-gold { color: #d4af37 !important; }
    .bg-gold { background-color: #d4af37 !important; }
    .btn-gold {
        background-color: #d4af37;
        border: none;
    }
    .btn-gold:hover {
        background-color: #c7a029;
    }
    .btn-outline-gold {
        color: #d4af37;
        border-color: #d4af37;
    }
    .btn-outline-gold:hover {
        background-color: #d4af37;
        color: #fff;
    }
</style>
@endsection
