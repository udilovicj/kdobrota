@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-dark mb-0">Dashboard Overview</h1>
        <div class="date text-muted">
            <i class="fas fa-calendar-alt me-2"></i>{{ now()->format('F d, Y') }}
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Users</h6>
                            <h2 class="mb-0 text-dark">{{ \App\Models\User::count() }}</h2>
                        </div>
                        <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Products</h6>
                            <h2 class="mb-0 text-dark">{{ \App\Models\Product::count() }}</h2>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Orders</h6>
                            <h2 class="mb-0 text-dark">{{ \App\Models\Order::count() }}</h2>
                        </div>
                        <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Total Revenue</h6>
                            <h2 class="mb-0 text-dark">€{{ number_format(\App\Models\Order::sum('total_amount'), 2) }}</h2>
                        </div>
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="fas fa-euro-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mt-4">
        <!-- Orders Timeline -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark">Orders Timeline</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active">Week</button>
                            <button type="button" class="btn btn-sm btn-outline-primary">Month</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="orderTimelineChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 text-dark">Product Categories</h5>
                </div>
                <div class="card-body">
                    <div id="categoryChart" style="height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 text-dark">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->orderItems->count() }} items</td>
                                    <td>€{{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart', 'line']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // Orders Timeline Chart
        var timelineData = new google.visualization.DataTable();
        timelineData.addColumn('string', 'Date');
        timelineData.addColumn('number', 'Orders');
        timelineData.addRows(@json($orderTimelineData));

        var timelineOptions = {
            curveType: 'function',
            legend: { position: 'none' },
            chartArea: { width: '90%', height: '80%' },
            colors: ['#0d6efd'],
            hAxis: { textStyle: { color: '#6c757d' } },
            vAxis: { textStyle: { color: '#6c757d' }, minValue: 0 },
            backgroundColor: 'transparent'
        };

        var timelineChart = new google.visualization.LineChart(document.getElementById('orderTimelineChart'));
        timelineChart.draw(timelineData, timelineOptions);

        // Category Distribution Chart
        var categoryData = new google.visualization.DataTable();
        categoryData.addColumn('string', 'Category');
        categoryData.addColumn('number', 'Products');
        categoryData.addRows(@json($categoryData));

        var categoryOptions = {
            pieHole: 0.4,
            chartArea: { width: '90%', height: '90%' },
            colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6610f2'],
            legend: { position: 'right', textStyle: { color: '#6c757d' } },
            backgroundColor: 'transparent'
        };

        var categoryChart = new google.visualization.PieChart(document.getElementById('categoryChart'));
        categoryChart.draw(categoryData, categoryOptions);
    }

    // Redraw charts on window resize
    window.addEventListener('resize', drawCharts);
</script>
@endpush
@endsection 