@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 text-dark mb-0">Orders Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i>Filter Status
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item active" href="#">All Orders</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Pending</a></li>
                    <li><a class="dropdown-item" href="#">Processing</a></li>
                    <li><a class="dropdown-item" href="#">Completed</a></li>
                    <li><a class="dropdown-item" href="#">Cancelled</a></li>
                </ul>
            </div>
            <button class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Export
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Orders List</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search orders...">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ordersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-3">Order ID</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Products</th>
                            <th class="border-0">Total</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Date</th>
                            <th class="border-0 text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-3">
                                <span class="fw-bold">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">
                                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $order->user->name }}</h6>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $order->items->count() }} items
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ number_format($order->total_amount, 2) }} RSD</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $order->status === 'completed' ? 'success' : 
                                    ($order->status === 'processing' ? 'info' : 
                                    ($order->status === 'cancelled' ? 'danger' : 'warning')) 
                                }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('d.m.Y.') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog me-1"></i>Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-clock me-2 text-warning"></i>Pending
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-spinner me-2 text-info"></i>Processing
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-check me-2 text-success"></i>Completed
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-times me-2"></i>Cancel Order
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

.dropdown-item {
    padding: 0.5rem 1rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #dc3545;
    color: white !important;
}

#searchInput:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#ordersTable').DataTable({
        "dom": '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
        "pageLength": 10,
        "ordering": true,
        "responsive": true,
        "language": {
            "search": "",
            "searchPlaceholder": "Search orders..."
        }
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush

@endsection 