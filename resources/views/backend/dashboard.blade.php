@extends('Layouts.Base')

@section('content')
    <div class="row mb-3">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-6 col-md-6 mb-4 ">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Data Surat Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="suratMasuk"> </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2"><i class="fas fa-arrow-down"></i> </span>
                                <span>Detail</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-sharp fa-solid fa-envelope-circle-check fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4 ">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Jumlah Data Surat Keluar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="suratKeluar"></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i></span>
                                <span>Detail</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-sharp fa-solid fa-envelope-open-text fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat datang di sistem arsip kelurahan kawatuna 🎉</h5>
                            <p class="mb-4"></b></p>
                            <i class="fa-sharp fa-solid fa-face-smile text-warning"></i>
                            <a href="javascript:;" class="">Enjoy your work !!!</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img class="mb-4" src="{{ asset('img/favicon_palukota.png') }}"
                                height="350">        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                type: "get",
                url: "{{ url('dashboard/get/count') }}",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#suratMasuk').text(response.data.suratMasuk);
                    $('#suratKeluar').html(response.data.suratKeluar);
                }
            });
        });

        $(document).ready(function() {
            $.ajax({
                type: "get",
                url: "{{ url('count/users') }}",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#users').text(response.data);
                    var ctx = document.getElementById('userChart').getContext('2d');
                    var userChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Count User'],
                            datasets: [{
                                label: 'Count', 
                                data: [response.data
                                ],
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)', 
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $.ajax({
                type: "get",
                url: "{{ url('count/profile') }}",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#profile').text(response.data);
                    var ctx = document.getElementById('userChart2').getContext('2d');
                    var userChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Count Profile'],
                            datasets: [{
                                label: 'Count', 
                                data: [response.data
                                ],
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)', 
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
