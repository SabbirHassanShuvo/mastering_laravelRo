@extends('backend.master')
@section('title', 'All Conversations')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Conversations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Messaging</a></li>
                        <li class="breadcrumb-item active">Conversations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Conversation History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Initiator</th>
                                    <th>Receiver</th>
                                    <th>Messages</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conversations as $conv)
                                <tr>
                                    <td>{{ $conv->id }}</td>
                                    <td>{{ $conv->product->title ?? 'N/A' }}</td>
                                    <td>{{ $conv->userOne->name ?? 'N/A' }}</td>
                                    <td>{{ $conv->userTwo->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-soft-info">{{ $conv->messages_count }}</span></td>
                                    <td>
                                        <span class="badge {{ $conv->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($conv->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $conv->created_at->format('d M, Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View Details</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $conversations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
