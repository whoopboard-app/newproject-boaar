@extends('layouts.inspinia')

@section('title', $persona->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h2 class="mb-0">{{ $persona->name }}</h2>
                <div>
                    <a href="{{ route('personas.edit', $persona) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($persona->avatar)
                        <img src="{{ asset('storage/' . $persona->avatar) }}" alt="{{ $persona->name }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                            <span class="text-white" style="font-size: 48px; font-weight: bold;">{{ substr($persona->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <h3>{{ $persona->name }}</h3>
                    <p class="text-muted mb-3">{{ $persona->role }}</p>

                    @if($persona->age_range || $persona->location)
                        <div class="mb-3">
                            @if($persona->age_range)
                                <div class="mb-2">
                                    <i class="ti ti-cake me-2"></i>
                                    <strong>Age:</strong> {{ $persona->age_range }}
                                </div>
                            @endif
                            @if($persona->location)
                                <div class="mb-2">
                                    <i class="ti ti-map-pin me-2"></i>
                                    <strong>Location:</strong> {{ $persona->location }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <span class="badge {{ $persona->status === 'active' ? 'bg-success' : 'bg-secondary' }} fs-6">
                        {{ ucfirst($persona->status) }}
                    </span>
                </div>
            </div>

            @if($persona->segments->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Related Segments</h5>
                    </div>
                    <div class="card-body">
                        @foreach($persona->segments as $segment)
                            <span class="badge bg-info mb-2 me-1">{{ $segment->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Overview</h5>
                </div>
                <div class="card-body">
                    <p>{{ $persona->description }}</p>
                </div>
            </div>

            @if($persona->quote)
                <div class="card bg-light">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p class="fst-italic">"{{ $persona->quote }}"</p>
                            <footer class="blockquote-footer">{{ $persona->name }}</footer>
                        </blockquote>
                    </div>
                </div>
            @endif

            @if($persona->goals && count($persona->goals) > 0)
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-target"></i> Goals</h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @foreach($persona->goals as $goal)
                                <li>{{ $goal }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if($persona->pain_points && count($persona->pain_points) > 0)
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-alert-circle"></i> Pain Points</h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @foreach($persona->pain_points as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if($persona->behaviors && count($persona->behaviors) > 0)
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="ti ti-chart-bar"></i> Behaviors</h5>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            @foreach($persona->behaviors as $behavior)
                                <li>{{ $behavior }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
