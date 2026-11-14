<!DOCTYPE html>
<html lang="id">

<head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <link rel="icon" href="{{ asset('backend-dev/production/images/favicon.ico') }}" type="image/ico" />

     <title>@yield('title', 'Dashboard') | {{ config('app.name') }} Admin</title>

     <!-- Icons -->
     <link href="{{ asset('backend-dev/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
     <!-- Modern admin theme -->
     <link href="{{ asset('css/admin-modern.css') }}" rel="stylesheet">
     @stack('styles')
</head>

<body class="admin-body">
     @include('admin.partials.sidebar')

     <div class="adm-backdrop" onclick="document.body.classList.remove('sidebar-open')"></div>

     <div class="adm-main">
          <header class="adm-topbar">
               <button type="button" class="adm-topbar__toggle" aria-label="Buka menu"
                    onclick="document.body.classList.toggle('sidebar-open')">
                    <i class="fa fa-bars"></i>
               </button>

               <div class="adm-topbar__title">
                    <h1>@yield('title', 'Dashboard')</h1>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
               </div>

               <div class="adm-topbar__actions">
                    <span class="adm-pill">
                         <i class="fa fa-user-circle-o"></i>
                         {{ auth()->user()->role ?? 'admin' }}
                    </span>
                    <a href="{{ route('admin.dashboard') }}" class="adm-iconbtn" title="Dashboard">
                         <i class="fa fa-dashboard"></i>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="adm-iconbtn" title="Pengaturan">
                         <i class="fa fa-cog"></i>
                    </a>
               </div>
          </header>

          <main class="adm-content">
               @if (session('success'))
                    <div class="alert alert--success" role="alert">
                         <i class="fa fa-check-circle"></i>
                         <div>{{ session('success') }}</div>
                         <button type="button" class="alert__close" aria-label="Tutup"
                              onclick="this.parentElement.remove()">&times;</button>
                    </div>
               @endif

               @if (session('error'))
                    <div class="alert alert--danger" role="alert">
                         <i class="fa fa-exclamation-circle"></i>
                         <div>{{ session('error') }}</div>
                         <button type="button" class="alert__close" aria-label="Tutup"
                              onclick="this.parentElement.remove()">&times;</button>
                    </div>
               @endif

               @if ($errors->any())
                    <div class="alert alert--danger" role="alert">
                         <i class="fa fa-exclamation-triangle"></i>
                         <div>
                              <strong>Periksa kembali input Anda:</strong>
                              <ul>
                                   @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                   @endforeach
                              </ul>
                         </div>
                         <button type="button" class="alert__close" aria-label="Tutup"
                              onclick="this.parentElement.remove()">&times;</button>
                    </div>
               @endif

               @yield('content')
          </main>

          <footer class="adm-footer">
               <span>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
               <span>Backend Admin Dashboard &middot; v1.0</span>
          </footer>
     </div>

     @stack('scripts')
</body>

</html>
