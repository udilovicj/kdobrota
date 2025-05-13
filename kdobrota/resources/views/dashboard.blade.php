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
        <!-- Left Column - Statistics -->
        <div class="col-xl-4">
            <!-- Statistics Cards -->
            <div class="row g-4">
                <!-- Users Card -->
                <div class="col-12">
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

                <!-- Products Card -->
                <div class="col-12">
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

                <!-- Categories Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-2">Categories</h6>
                                    <h2 class="mb-0 text-dark">{{ \App\Models\Category::count() }}</h2>
                                </div>
                                <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3">
                                    <i class="fas fa-tags fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase text-muted mb-2">Total Orders</h6>
                                    <h2 class="mb-0 text-dark">{{ \App\Models\Order::count() }}</h2>
                                </div>
                                <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Analytics -->
        <div class="col-xl-8">
            <!-- Analytics Charts -->
            <div class="row g-4">
                <!-- Orders Timeline Chart -->
                <div class="col-12">
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

                <!-- Category Distribution & Top Products Charts -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0 text-dark">Product Categories</h5>
                        </div>
                        <div class="card-body">
                            <div id="categoryChart" style="height: 250px;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0 text-dark">Top Products</h5>
                        </div>
                        <div class="card-body">
                            <div id="topProductsChart" style="height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-dark">Recent Orders</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                    View All Orders
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Order ID</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Total</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Date</th>
                            <th class="border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="align-middle">
                                <span class="fw-bold">#{{ $order->id }}</span>
                            </td>
                            <td class="align-middle">{{ $order->user->name }}</td>
                            <td class="align-middle">{{ number_format($order->total_amount, 2) }} RSD</td>
                            <td class="align-middle">
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="align-middle">{{ $order->created_at->format('d.m.Y.') }}</td>
                            <td class="align-middle">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // Draw Category Distribution Chart
        var categoryData = google.visualization.arrayToDataTable([
            ['Category', 'Products'],
            @foreach($categoryData as $data)
                ['{{ $data[0] }}', {{ $data[1] }}],
            @endforeach
        ]);

        var categoryOptions = {
            pieHole: 0.6,
            colors: ['#4361ee', '#3f37c9', '#4895ef', '#4cc9f0', '#3a0ca3'],
            chartArea: {width: '100%', height: '85%'},
            legend: {position: 'bottom', alignment: 'center', textStyle: {color: '#666'}},
            pieSliceText: 'none',
            tooltip: {showColorCode: true},
            backgroundColor: 'transparent',
            animation: {
                startup: true,
                duration: 1000,
                easing: 'out'
            }
        };

        var categoryChart = new google.visualization.PieChart(document.getElementById('categoryChart'));
        categoryChart.draw(categoryData, categoryOptions);

        // Draw Orders Timeline Chart
        var timelineData = google.visualization.arrayToDataTable([
            ['Date', 'Orders'],
            @foreach($orderTimelineData as $data)
                ['{{ $data[0] }}', {{ $data[1] }}],
            @endforeach
        ]);

        var timelineOptions = {
            curveType: 'function',
            colors: ['#4361ee'],
            chartArea: {width: '90%', height: '85%'},
            legend: {position: 'none'},
            backgroundColor: 'transparent',
            hAxis: {
                textStyle: {color: '#666', fontSize: 11},
                gridlines: {color: 'transparent'},
                baselineColor: '#eee'
            },
            vAxis: {
                textStyle: {color: '#666', fontSize: 11},
                gridlines: {color: '#f5f5f5'},
                baselineColor: '#eee',
                minValue: 0
            },
            animation: {
                startup: true,
                duration: 1000,
                easing: 'out'
            },
            lineWidth: 3,
            pointSize: 6,
            tooltip: {showColorCode: true}
        };

        var timelineChart = new google.visualization.LineChart(document.getElementById('orderTimelineChart'));
        timelineChart.draw(timelineData, timelineOptions);

        // Draw Top Products Chart
        var productsData = google.visualization.arrayToDataTable([
            ['Product', 'Orders'],
            @foreach($topProductsData as $data)
                ['{{ $data[0] }}', {{ $data[1] }}],
            @endforeach
        ]);

        var productsOptions = {
            colors: ['#4361ee'],
            chartArea: {width: '70%', height: '85%'},
            legend: {position: 'none'},
            backgroundColor: 'transparent',
            hAxis: {
                textStyle: {color: '#666', fontSize: 11},
                gridlines: {color: 'transparent'},
                baselineColor: '#eee',
                minValue: 0
            },
            vAxis: {
                textStyle: {color: '#666', fontSize: 11},
                gridlines: {color: 'transparent'}
            },
            animation: {
                startup: true,
                duration: 1000,
                easing: 'out'
            },
            bar: {groupWidth: '60%'},
            tooltip: {showColorCode: true}
        };

        var productsChart = new google.visualization.BarChart(document.getElementById('topProductsChart'));
        productsChart.draw(productsData, productsOptions);
    }

    // Redraw charts on window resize with debounce
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(drawCharts, 250);
    });
</script>
@endpush 