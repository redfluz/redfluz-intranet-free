@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">
                    <i data-lucide="clock" class="fg-primary me-2"></i>{{ __('attendance.title') }}
                </h3>
                <div class="fg-secondary">{{ __('attendance.subtitle') }}</div>
            </div>
            <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('attendance.index') }}">
                <label class="small fg-secondary" for="attendance-month">{{ __('attendance.month') }}</label><input
                    class="form-control" id="attendance-month" name="month" type="month"
                    value="{{ $month->format('Y-m') }}" onchange="this.form.submit()" />
            </form>
        </div>

        @include('partials.alerts')
        @include('partials.errors')

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="fg-secondary small">{{ __('attendance.today') }}</div>
                    <h5 class="fw-bold mb-1">{{ today()->format('d M Y') }}</h5>
                    <div class="fg-secondary">
                        @if ($today->clock_in)
                            {{ __('attendance.clock_in_at', ['time' => $today->clock_in->format('H:i')]) }}
                        @else
                            {{ __('attendance.not_clocked_in') }}
                        @endif
                        @if ($today->clock_out)
                            · {{ __('attendance.clock_out_at', ['time' => $today->clock_out->format('H:i')]) }}
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if (!$today->clock_in)
                        <form method="POST" action="{{ route('attendance.clock-in') }}">
                            @csrf
                            <button class="btn btn-solid theme-success" type="submit">
                                <i data-lucide="log-in" class="me-1"></i>{{ __('attendance.clock_in') }}
                            </button>
                        </form>
                    @elseif (!$today->clock_out)
                        <form method="POST" action="{{ route('attendance.clock-out') }}">
                            @csrf
                            <button class="btn btn-solid theme-primary" type="submit">
                                <i data-lucide="log-out" class="me-1"></i>{{ __('attendance.clock_out') }}
                            </button>
                        </form>
                    @else
                        <span class="badge theme-success align-self-center p-2">{{ __('attendance.completed') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-1">
                <h5 class="fw-bold mb-0">
                    {{ __('attendance.monthly_title', ['month' => $month->translatedFormat('F Y')]) }}
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table-hover mb-0 table align-middle">
                    <thead class="bg-1">
                        <tr>
                            <th scope="col">{{ __('attendance.date') }}</th>
                            <th scope="col">{{ __('attendance.clock_in') }}</th>
                            <th scope="col">{{ __('attendance.clock_out') }}</th>
                            <th scope="col" class="text-end">{{ __('attendance.worked_hours') }}</th>
                            <th scope="col">{{ __('attendance.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>{{ $record->work_date->translatedFormat('d M Y (l)') }}</td>
                                <td>{{ $record->clock_in?->format('H:i') ?? '—' }}</td>
                                <td>{{ $record->clock_out?->format('H:i') ?? '—' }}</td>
                                <td class="text-end">{{ number_format($record->worked_minutes / 60, 2) }}</td>
                                <td>
                                    <span
                                        class="badge {{ $record->clock_in && $record->clock_out ? 'theme-success' : 'theme-warning' }}">{{ $record->clock_out ? __('attendance.complete') : __('attendance.in_progress') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="fg-secondary py-4 text-center" colspan="5">{{ __('attendance.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
