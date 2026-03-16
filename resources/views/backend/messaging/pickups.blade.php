@extends('backend.master')
@section('title', 'Pickup Requests')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pickup Requests</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Messaging</a></li>
                        <li class="breadcrumb-item active">Pickups</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pickup Scheduling Overview</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Requester</th>
                                    <th>Date/Time</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pickups as $pickup)
                                <tr>
                                    <td>{{ $pickup->id }}</td>
                                    <td>{{ $pickup->product->title ?? 'N/A' }}</td>
                                    <td>{{ $pickup->requester->name ?? 'N/A' }}</td>
                                    <td>{{ $pickup->pickup_date }} {{ $pickup->pickup_time }}</td>
                                    <td>{{ Str::limit($pickup->location, 30) }}</td>
                                    <td>
                                        @if($pickup->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($pickup->status == 'accepted')
                                            <span class="badge bg-success">Accepted</span>
                                        @elseif($pickup->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $pickup->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $pickup->created_at->format('d M, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $pickups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
