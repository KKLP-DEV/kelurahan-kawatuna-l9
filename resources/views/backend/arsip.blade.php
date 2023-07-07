@extends('Layouts.Base')

@section('content')
<div id="loading-overlay" class="loading-overlay" style="display: none;">
    <div id="loading" class="loading">
        <img src="{{ asset('img/loader.gif') }}" alt="Loading..." />
    </div>
</div>
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Arsip </h1>
           
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-~3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Surat masuk</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <th>No</th>
                                <th>Jenis surat</th>
                                <th>Action</th>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        <!--Row-->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
        //get data
        $(document).ready(function() {
            var currentURL = window.location.href;
            var uuid_tahun = currentURL.split('/').pop();
            $('#loading-overlay').show();
            $.ajax({
                url: "{{ url('v2/396d6585-16ae-4d04-9549-c499e52b75ea/jenis/surat') }}/",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    $('#loading-overlay').hide();
                    console.log(response);
                    var tableBody = "";
                    $.each(response.data, function(index, item) {
                        tableBody += "<tr>";
                        tableBody += "<td>" + (index + 1) + "</td>";
                        tableBody += "<td>" + item.jenis_surat + "</td>";
                        tableBody += "<td><a href='{{ url('cms/arsip') }}/" + uuid_tahun + "/" + item.id + "' class='btn btn-primary'><i class='fa-solid fa-eye'></i> View</a></td>";

                        tableBody += "</tr>";
                    });
                    $("#tableBody").html(tableBody);
                },
                error: function() {
                    $('#loading-overlay').hide();
                    console.log("Failed to get data from server");
                }
            });
        });
    </script>
@endsection
