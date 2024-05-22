@extends('layouts.app')

@section('title')
    {{ __('Halaman Dashboard') }} | {{ config('app.name') }}
@endsection

@section('content')
    <!-- Content Row -->
    <div class="container mt-5">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total User</h5>
                        <p class="card-text">{{ $cardData['totalUsers'] }}</p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <p class="card-text">{{ $cardData['totalTransaksi'] }}</p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-warning text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Barang</h5>
                        <p class="card-text">{{ $cardData['totalProducts'] }}</p>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            {{-- <div class="col-lg-3 col-md-6 mb-4">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text">{{ $cardData['totalUsers'] }}</p>
                    </div>
                </div>
            </div> --}}
        </div>
        <!-- Chart -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection

@section('script')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('myChart').getContext('2d');
            var monthlyTransactions = @json($data);

            var labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var data = labels.map((label, index) => monthlyTransactions[index + 1] || 0);

            var myChart = new Chart(ctx, {
                type: 'line', // Change the chart type to 'line'
                data: {
                    labels: labels, // Months labels
                    datasets: [{
                        label: 'Data Transaksi',
                        data: data, // Monthly transactions data
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false // Ensure the area under the line is not filled
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
            // var ctx = document.getElementById('myChart').getContext('2d');
            // var myChart = new Chart(ctx, {
            //     type: 'line', // Specify the chart type
            //     data: {
            //         labels: @json($labels), // Pass the labels data
            //         datasets: [{
            //             label: 'Transaksi',
            //             data: @json($data), // Pass the data
            //             backgroundColor: [
            //                 'rgba(255, 99, 132, 0.2)',
            //                 'rgba(54, 162, 235, 0.2)',
            //                 'rgba(255, 206, 86, 0.2)',
            //                 'rgba(75, 192, 192, 0.2)',
            //                 'rgba(153, 102, 255, 0.2)',
            //                 'rgba(255, 159, 64, 0.2)',
            //                 'rgba(255, 99, 132, 0.2)'
            //             ],
            //             borderColor: [
            //                 'rgba(255, 99, 132, 1)',
            //                 'rgba(54, 162, 235, 1)',
            //                 'rgba(255, 206, 86, 1)',
            //                 'rgba(75, 192, 192, 1)',
            //                 'rgba(153, 102, 255, 1)',
            //                 'rgba(255, 159, 64, 1)',
            //                 'rgba(255, 99, 132, 1)'
            //             ],
            //             borderWidth: 1
            //         }]
            //     },
            //     options: {
            //         scales: {
            //             y: {
            //                 beginAtZero: true
            //             }
            //         }
            //     }
            // });
        });
    </script>
@endsection