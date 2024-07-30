<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>
    
    <div class="row">
        <!-- Total Sales -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Total Sales
                </div>
                <div class="card-body">
                    <h3>RM{{ number_format($totalSales, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Members -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Total Registered Members
                </div>
                <div class="card-body">
                    <h3>{{ $totalMembers }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Sales Over Time Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Sales Over Time
                </div>
                <div class="card-body">
                    <canvas id="salesOverTimeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- New Members Over Time Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    New Members Over Time
                </div>
                <div class="card-body">
                    <canvas id="newMembersOverTimeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Sales By Product Pie Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Sales By Product
                </div>
                <div class="card-body">
                    <canvas id="salesByProductChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sales By Type Pie Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Sales By Type
                </div>
                <div class="card-body">
                    <canvas id="salesByTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function generateRandomColors(count) {
        let colors = [];
        for (let i = 0; i < count; i++) {
            colors.push(getRandomColor());
        }
        return colors;
    }

    // Sales Over Time Chart
    var ctx = document.getElementById('salesOverTimeChart').getContext('2d');
    var salesOverTimeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesOverTime->pluck('date')) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($salesOverTime->pluck('total')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
    });

    // New Members Over Time Chart
    var ctx2 = document.getElementById('newMembersOverTimeChart').getContext('2d');
    var newMembersOverTimeChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: {!! json_encode($newMembersOverTime->pluck('date')) !!},
            datasets: [{
                label: 'New Members',
                data: {!! json_encode($newMembersOverTime->pluck('total')) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
    });

    // Sales By Product Chart
    var salesByProductColors = generateRandomColors({!! json_encode($salesByProduct->count()) !!});
    var ctx3 = document.getElementById('salesByProductChart').getContext('2d');
    var salesByProductChart = new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: {!! json_encode($salesByProduct->pluck('product')) !!},
            datasets: [{
                data: {!! json_encode($salesByProduct->pluck('quantity')) !!},
                backgroundColor: salesByProductColors
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.raw !== null) {
                                label += context.raw;
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Sales By Type Chart
    var salesByTypeColors = generateRandomColors({!! json_encode($salesByType->count()) !!});
    var ctx4 = document.getElementById('salesByTypeChart').getContext('2d');
    var salesByTypeChart = new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: {!! json_encode($salesByType->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode($salesByType->pluck('quantity')) !!},
                backgroundColor: salesByTypeColors
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.raw !== null) {
                                label += context.raw;
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
