<!DOCTYPE html>
<html lang="id">

<head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <title>Login | {{ config('app.name') }} Admin</title>

     <link href="{{ asset('backend-dev/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
     <link href="{{ asset('css/admin-modern.css') }}" rel="stylesheet">
</head>

<body class="login-body">
     <div class="login-shell">
          <!-- Brand / value proposition -->
          <section class="login-hero">
               <span class="login-hero__badge"><i class="fa fa-shield"></i> Secure Admin Area</span>

               <h1>Kelola konten<br>RAMStudio dengan mudah.</h1>
               <p>Dashboard terpusat untuk layanan, portofolio, artikel, paket harga, dan pesan calon klien.</p>

               <ul class="login-hero__list">
                    <li><i class="fa fa-check"></i> 16 modul pengelolaan data</li>
                    <li><i class="fa fa-check"></i> Statistik langsung dari database</li>
                    <li><i class="fa fa-check"></i> REST API v1 terintegrasi</li>
               </ul>
          </section>

          <!-- Login form -->
          <section class="login-panel">
               <div class="login-panel__brand">
                    <span class="adm-brand__mark"><i class="fa fa-paw"></i></span>
                    <span class="adm-brand__text">
                         <strong style="color:#0f172a">{{ config('app.name') }}</strong>
                         <span style="color:#94a3b8">Admin Panel</span>
                    </span>
               </div>

               <h2>Selamat datang kembali</h2>
               <p>Masuk dengan akun administrator Anda untuk melanjutkan.</p>

               @if (session('success'))
                    <div class="alert alert--success" role="alert">
                         <i class="fa fa-check-circle"></i>
                         <div>{{ session('success') }}</div>
                    </div>
               @endif

               @if ($errors->any())
                    <div class="alert alert--danger" role="alert">
                         <i class="fa fa-exclamation-triangle"></i>
                         <div>{{ $errors->first() }}</div>
                    </div>
               @endif

               <form method="POST" action="{{ route('admin.login.attempt') }}">
                    @csrf

                    <div class="login-field">
                         <label for="email">Alamat Email</label>
                         <div class="login-field__wrap">
                              <i class="fa fa-envelope-o"></i>
                              <input type="email" id="email" name="email" placeholder="admin@ramstudio.test"
                                   value="{{ old('email') }}" required autofocus autocomplete="username" />
                         </div>
                    </div>

                    <div class="login-field">
                         <label for="password">Password</label>
                         <div class="login-field__wrap">
                              <i class="fa fa-lock"></i>
                              <input type="password" id="password" name="password" placeholder="••••••••" required
                                   autocomplete="current-password" />
                         </div>
                    </div>

                    <div class="login-row">
                         <label for="remember">
                              <input type="checkbox" id="remember" name="remember" value="1"
                                   @checked(old('remember')) />
                              Ingat saya
                         </label>
                    </div>

                    <button type="submit" class="login-submit">
                         <i class="fa fa-sign-in"></i> Masuk ke Dashboard
                    </button>
               </form>

               <p class="login-foot">&copy; {{ date('Y') }} {{ config('app.name') }}. Backend Admin Dashboard.</p>
          </section>
     </div>
</body>

</html>
