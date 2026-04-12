{{--
    User Avatar Partial
    Usage: @include('partials.user-avatar', ['user' => $user, 'size' => 40])
    Optional: 'link' => true (wrap in link to profile), 'showName' => true, 'class' => 'extra-class'
--}}
@php
    $size = $size ?? 40;
    $user = $user ?? null;
    $showName = $showName ?? false;
    $link = $link ?? false;
    $class = $class ?? '';
    $fontSize = max(round($size * 0.4), 10);

    $hasImage = $user && $user->image;
    $hasAvatar = $user && $user->avatar && !$hasImage;
    $initial = $user ? mb_strtoupper(mb_substr($user->name, 0, 1)) : '?';
    $name = $user ? $user->name : '';
@endphp

@if($link && $user)
<a href="{{ $user->isProvider() ? route('provider.profile.public', $user->id) : route('user.profile.public', $user->id) }}" class="text-decoration-none d-inline-flex align-items-center {{ $class }}">
@else
<span class="d-inline-flex align-items-center {{ $class }}">
@endif

@if($hasImage)
    <img src="{{ $user->image_url }}"
         alt="{{ $name }}"
         class="rounded-circle"
         style="width: {{ $size }}px; height: {{ $size }}px; object-fit: cover; border: 2px solid #e9ecef;"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    <span class="rounded-circle d-none align-items-center justify-content-center text-white fw-bold"
          style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $fontSize }}px; background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));">
        {{ $initial }}
    </span>
@elseif($hasAvatar)
    <img src="{{ $user->avatar_url }}"
         alt="{{ $name }}"
         class="rounded-circle"
         style="width: {{ $size }}px; height: {{ $size }}px; object-fit: cover; border: 2px solid #e9ecef;"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    <span class="rounded-circle d-none align-items-center justify-content-center text-white fw-bold"
          style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $fontSize }}px; background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));">
        {{ $initial }}
    </span>
@else
    <span class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
          style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $fontSize }}px; background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));">
        {{ $initial }}
    </span>
@endif

@if($showName)
    <span class="me-2 fw-semibold" style="color: var(--e-primary);">{{ $name }}</span>
@endif

@if($link && $user)
</a>
@else
</span>
@endif
