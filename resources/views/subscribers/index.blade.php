@extends('layouts.inspinia')

@section('title', 'Subscriber Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h2 class="mb-0">Subscriber Management</h2>
                <a href="{{ route('subscribers.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Subscriber
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

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">All Subscribers</h5>
                        <form action="{{ route('subscribers.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                            @csrf
                            <input type="file" name="csv_file" accept=".csv" class="form-control form-control-sm" style="width: auto;" required>
                            <div class="form-check">
                                <input type="checkbox" name="send_verification" value="1" class="form-check-input" id="sendVerification">
                                <label class="form-check-label" for="sendVerification">Send verification</label>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="ti ti-upload me-1"></i> Import CSV
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th>Segments</th>
                                    <th>Subscribe Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscribers as $subscriber)
                                    <tr>
                                        <td>{{ $subscriber->full_name }}</td>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $subscriber->source)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($subscriber->status === 'subscribed')
                                                <span class="badge bg-success">Subscribed</span>
                                            @elseif($subscriber->status === 'pending_verify')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($subscriber->status === 'unsubscribed')
                                                <span class="badge bg-danger">Unsubscribed</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscriber->segments->count() > 0)
                                                @foreach($subscriber->segments as $segment)
                                                    <span class="badge bg-info me-1">{{ $segment->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>{{ $subscriber->subscribe_date->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('subscribers.edit', $subscriber) }}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            @if($subscriber->status === 'pending_verify')
                                                <form action="{{ route('subscribers.resend', $subscriber) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="ti ti-send"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('subscribers.destroy', $subscriber) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No subscribers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $subscribers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
