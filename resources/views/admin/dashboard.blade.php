@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Dashboard</h2>

        <div class="row g-4">
            <!-- Stats Card 1 -->
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="card-body">
                        <h6 class="text-muted">Total Sales</h6>
                        <h3 class="mb-0">$12,450</h3>
                    </div>
                </div>
            </div>

            <!-- Stats Card 2 -->
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="card-body">
                        <h6 class="text-muted">New Orders</h6>
                        <h3 class="mb-0">45</h3>
                    </div>
                </div>
            </div>

            <!-- Stats Card 3 -->
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="card-body">
                        <h6 class="text-muted">Products</h6>
                        <h3 class="mb-0">86</h3>
                    </div>
                </div>
            </div>

            <!-- Stats Card 4 -->
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="card-body">
                        <h6 class="text-muted">Total Users</h6>
                        <h3 class="mb-0">1,203</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Recent Orders</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#12345</td>
                                        <td>Sarah Johnson</td>
                                        <td>Radiant Glow Serum</td>
                                        <td><span class="badge bg-success bg-opacity-75">Completed</span></td>
                                        <td>$45.00</td>
                                    </tr>
                                    <tr>
                                        <td>#12344</td>
                                        <td>Mike Ross</td>
                                        <td>Daily Moisturizer</td>
                                        <td><span class="badge bg-warning text-dark bg-opacity-75">Pending</span></td>
                                        <td>$32.00</td>
                                    </tr>
                                    <tr>
                                        <td>#12343</td>
                                        <td>Emily Davis</td>
                                        <td>Night Cream</td>
                                        <td><span class="badge bg-info text-dark bg-opacity-75">Processing</span></td>
                                        <td>$55.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection