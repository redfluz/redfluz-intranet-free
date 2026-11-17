@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between bg-1 py-3">
            <h5 class="fw-bold m-0">{{ __('notifications.detail_title') }}</h5>
            <a class="btn btn-solid theme-secondary btn-sm" href="{{ route('personnel.notifications.index') }}">
                <i class="me-1" data-lucide="arrow-left"></i>
                {{ __('common.actions.back') }}
            </a>
        </div>

        <div class="card-body">
            @php $data = $notification->data; @endphp

            <div class="mb-4">
                <h4 class="fw-bold">{{ $data['title'] ?? __('notifications.title') }}</h4>
                <div class="fg-secondary small">
                    <i data-lucide="clock" class="me-1"></i>
                    {{ $notification->created_at->format('d M Y, h:i A') }}
                    ({{ $notification->created_at->diffForHumans() }})
                </div>
            </div>

            <div class="mb-4 rounded-3 border p-3">
                <p class="fs-6 mb-0" style="white-space: pre-line">{{ $data['message'] ?? 'N/A' }}</p>
            </div>

            @if (!empty($data['url']) && $data['url'] !== '#')
                <a class="btn btn-solid theme-primary" href="{{ $data['url'] }}">
                    <i data-lucide="external-link" class="me-1"></i>
                    {{ __('common.actions.view') }}
                </a>
            @endif
        </div>
    </div>
@endsection
