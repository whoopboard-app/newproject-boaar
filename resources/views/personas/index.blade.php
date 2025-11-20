@extends('layouts.inspinia')

@section('title', 'Personas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Personas</h2>
                <a href="{{ route('personas.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Create Persona
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($personas->count() > 0)
                <div class="row">
                    @foreach($personas as $persona)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0">
                                            @if($persona->avatar)
                                                <img src="{{ asset('storage/' . $persona->avatar) }}" alt="{{ $persona->name }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <span class="text-white fs-4 fw-bold">{{ substr($persona->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="card-title mb-1">{{ $persona->name }}</h5>
                                            <p class="text-muted mb-0">{{ $persona->role }}</p>
                                        </div>
                                    </div>

                                    @if($persona->age_range || $persona->location)
                                        <div class="mb-3">
                                            @if($persona->age_range)
                                                <span class="badge bg-secondary me-1">
                                                    <i class="ti ti-cake"></i> {{ $persona->age_range }}
                                                </span>
                                            @endif
                                            @if($persona->location)
                                                <span class="badge bg-secondary">
                                                    <i class="ti ti-map-pin"></i> {{ $persona->location }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <p class="card-text text-muted small mb-3">{{ Str::limit($persona->description, 100) }}</p>

                                    @if($persona->quote)
                                        <blockquote class="blockquote mb-3">
                                            <p class="small fst-italic text-muted">"{{ Str::limit($persona->quote, 80) }}"</p>
                                        </blockquote>
                                    @endif

                                    @if($persona->segments->count() > 0)
                                        <div class="mb-3">
                                            @foreach($persona->segments->take(3) as $segment)
                                                <span class="badge bg-info me-1">{{ $segment->name }}</span>
                                            @endforeach
                                            @if($persona->segments->count() > 3)
                                                <span class="badge bg-light text-dark">+{{ $persona->segments->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $persona->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($persona->status) }}
                                        </span>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('personas.show', $persona) }}" class="btn btn-outline-primary">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('personas.edit', $persona) }}" class="btn btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('personas.destroy', $persona) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-12">
                        {{ $personas->links() }}
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-users-group fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No personas found</h5>
                        <p class="text-muted mb-3">Create your first persona to start understanding your users better.</p>
                        <a href="{{ route('personas.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Create Persona
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
