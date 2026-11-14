@extends('admin.layouts.app')

@section('title', $title)

@section('content')
     <div class="page-head">
          <div class="page-head__titles">
               <h2>{{ $title }}</h2>
               <p>Identitas dan profil utama RAMStudio yang tampil di halaman About.</p>
          </div>
          <div class="page-head__actions">
               <a href="{{ route('admin.about.owners.index') }}" class="btn btn--ghost btn--sm">
                    <i class="fa fa-users"></i> Owner
               </a>
          </div>
     </div>

     <div class="card">
          <div class="card__head">
               <h3>Profil Perusahaan</h3>
               <small>Data tunggal</small>
          </div>

          <div class="card__body">
               <form method="POST" action="{{ route('admin.about.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @php
                         $old = fn(string $field, $default = null) => old(
                             $field,
                             $profile->getAttribute($field) ?? $default,
                         );
                    @endphp

                    <div class="form-grid">
                         <div class="field">
                              <label for="company_name">Nama Perusahaan <span class="req">*</span></label>
                              <input type="text" id="company_name" name="company_name" class="form-control"
                                   value="{{ $old('company_name', config('app.name')) }}" required>
                         </div>

                         <div class="field">
                              <label for="tagline">Tagline</label>
                              <input type="text" id="tagline" name="tagline" class="form-control"
                                   value="{{ $old('tagline') }}">
                         </div>

                         <div class="field field--full">
                              <label for="description">Deskripsi Perusahaan</label>
                              <textarea id="description" name="description" rows="5" class="form-control">{{ $old('description') }}</textarea>
                         </div>

                         <div class="field">
                              <label for="vision">Visi</label>
                              <textarea id="vision" name="vision" rows="4" class="form-control">{{ $old('vision') }}</textarea>
                         </div>

                         <div class="field">
                              <label for="mission">Misi</label>
                              <textarea id="mission" name="mission" rows="4" class="form-control">{{ $old('mission') }}</textarea>
                         </div>

                         <div class="field">
                              <label for="email">Email</label>
                              <input type="email" id="email" name="email" class="form-control"
                                   value="{{ $old('email') }}">
                         </div>

                         <div class="field">
                              <label for="phone">Telepon</label>
                              <input type="text" id="phone" name="phone" class="form-control"
                                   value="{{ $old('phone') }}">
                         </div>

                         <div class="field">
                              <label for="founded_year">Tahun Berdiri</label>
                              <input type="number" id="founded_year" name="founded_year" class="form-control" min="1900"
                                   max="2100" value="{{ $old('founded_year') }}">
                         </div>

                         <div class="field">
                              <label for="address">Alamat</label>
                              <input type="text" id="address" name="address" class="form-control"
                                   value="{{ $old('address') }}">
                         </div>

                         <div class="field">
                              <label for="logo">Logo</label>
                              @if ($profile->logo)
                                   <div class="thumb-preview">
                                        <img src="{{ \App\Support\Media::url($profile->logo) }}" alt="">
                                   </div>
                              @endif
                              <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                              <span class="hint">Format: jpg, jpeg, png, webp, svg. Maks 2 MB.</span>
                         </div>

                         <div class="field">
                              <label for="hero_image">Gambar Hero</label>
                              @if ($profile->hero_image)
                                   <div class="thumb-preview">
                                        <img src="{{ \App\Support\Media::url($profile->hero_image) }}" alt="">
                                   </div>
                              @endif
                              <input type="file" id="hero_image" name="hero_image" class="form-control" accept="image/*">
                              <span class="hint">Format: jpg, jpeg, png, webp. Maks 4 MB.</span>
                         </div>

                         <div class="field">
                              <label for="about_image">Gambar Halaman Tentang Kami</label>
                              @if ($profile->about_image)
                                   <div class="thumb-preview">
                                        <img src="{{ \App\Support\Media::url($profile->about_image) }}" alt="">
                                   </div>
                              @endif
                              <input type="file" id="about_image" name="about_image" class="form-control"
                                   accept="image/*">
                              <span class="hint">Tampil di halaman Tentang Kami. Format: jpg, jpeg, png, webp. Maks 4
                                   MB.</span>
                         </div>
                    </div>

                    <div class="form-actions">
                         <button type="submit" class="btn btn--success">
                              <i class="fa fa-save"></i> Simpan Profil
                         </button>
                    </div>
               </form>
          </div>
     </div>
@endsection
