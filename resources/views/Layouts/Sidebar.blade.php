 <!-- Sidebar -->
 <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
         <div class="sidebar-brand-icon">
             <img src="{{ asset('img/logo1.png') }}">
         </div>
         <div class="sidebar-brand-text">Kelurahan Kawatuna</div>
     </a>
     <hr class="sidebar-divider my-0">

     <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
         <a class="nav-link" href="/">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>

     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Data
     </div>
     @if (auth()->user()->role == 1)
         <li class="nav-item  {{ request()->is('cms/tahun') ? 'active' : '' }}">
             <a class="nav-link " href="{{ url('cms/tahun') }}" data-target="#collapsePage" aria-expanded="true"
                 aria-controls="collapsePage">
                 <i class="fa-solid fa-calendar-days "></i>
                 <span>Tahun Arsip</span>
             </a>
         </li>
         <li class="nav-item  {{ request()->is('cms/jenis/surat') ? 'active' : '' }}">
             <a class="nav-link " href="{{ url('cms/jenis/surat') }}" data-target="#collapsePage" aria-expanded="true"
                 aria-controls="collapsePage">
                 <i class="fa-sharp fa-solid fa-filter"></i>
                 <span>Jenis Surat</span>
             </a>
         </li>
     @endif
     <li class="nav-item  {{ request()->is('cms/surat/masuk') ? 'active' : '' }}">
         <a class="nav-link " href="{{ url('cms/surat/masuk') }}" data-target="#collapsePage" aria-expanded="true"
             aria-controls="collapsePage">
             <i class="fa-solid fa-download"></i>
             <span>Surat Masuk</span>
         </a>
     </li>
     <li class="nav-item  {{ request()->is('cms/surat/keluar') ? 'active' : '' }}">
         <a class="nav-link " href="{{ url('cms/surat/keluar') }}" data-target="#collapsePage" aria-expanded="true"
             aria-controls="collapsePage">
             <i class="fa-solid fa-upload"></i>
             <span>Surat Keluar</span>
         </a>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSuratMasuk"
             aria-expanded="false" aria-controls="collapseSuratMasuk">
             <i class="fa-solid fa-box-archive"></i>
             <span>Data Arsip Surat Masuk</span>
         </a>
         <div id="collapseSuratMasuk" class="collapse" aria-labelledby="headingSuratMasuk"
             data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Arsip Tahun</h6>
                 <div id="dropdownContent"></div>
             </div>
         </div>
     </li>
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSuratKeluar"
             aria-expanded="false" aria-controls="collapseSuratKeluar">
             <i class="fa-solid fa-box-archive"></i>
             <span>Data Arsip Surat Keluar</span>
         </a>
         <div id="collapseSuratKeluar" class="collapse" aria-labelledby="headingSuratKeluar"
             data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Arsip Tahun</h6>
                 <div id="dropdownContents"></div>
             </div>
         </div>
     </li>


     <hr class="sidebar-divider">
     <!-- Logout -->
     <li class="nav-item">
         <a class="nav-link" href="javascript:void(0);" id="logoutButton">
             <i class="fas fa-sign-out-alt fa-fw"></i>
             <span>Logout</span>
         </a>
     </li>
     {{-- @endif --}}

 </ul>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
     $(document).ready(function() {
         $('#logoutButton').click(function(e) {
             e.preventDefault();
             $.ajax({
                 url: '{{ url('v4/396d6585-16ae-4d04-9549-c499e52b75ea/auth/logout') }}',
                 method: 'POST',
                 dataType: 'json',
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                 success: function(response) {
                     console.log(response.message);
                     localStorage.removeItem('access_token');
                     window.location.href = '/login';
                 },
                 error: function(xhr, status, error) {
                     console.log(xhr.responseText);
                     alert('Error: Failed to logout. Please try again.');
                 }
             });
         });
     });

     function fetchYears(urlSegment, targetDropdownId) {
         $.ajax({
             url: "{{ url('v1/396d6585-16ae-4d04-9549-c499e52b75ea/tahun') }}",
             method: "GET",
             dataType: "json",
             success: function(response) {
                 console.log(response);
                 var dropdownContent = "";
                 response.data.forEach(function(data) {
                     dropdownContent +=
                         '<a class="collapse-item" href="{{ url('cms/arsip/surat') }}/' +
                         urlSegment +
                         '/get/' + data.id +
                         '">' +
                         data.tahun +
                         '</a>';
                 });
                 $('#' + targetDropdownId).html(dropdownContent);
             },
             error: function(xhr, status, error) {
                 console.log(xhr.responseText);
                 alert('Error: Gagal mengambil data tahun. Silakan coba lagi.');
             }
         });
     }

     $(document).ready(function() {
         fetchYears('masuk', 'dropdownContent');
         fetchYears('keluar', 'dropdownContents');
         $('#collapsePage').on('shown.bs.collapse', function() {
             fetchYears('masuk', 'dropdownContent');
             fetchYears('keluar', 'dropdownContents');
         });
     });
 </script>


 <!-- Sidebar -->
