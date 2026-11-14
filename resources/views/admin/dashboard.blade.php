@extends('admin.layouts.app')

@section('title', 'Dashboard')

@php
     $statusStyles = [
         'new' => 'badge--success',
         'in_progress' => 'badge--info',
         'contacted' => 'badge--warning',
         'closed' => 'badge--muted',
     ];
     $maxInquiry = max(1, (int) ($inquiryBreakdown->max() ?? 1));
@endphp

@section('content')
     <div class="page-head">
          <div class="page-head__titles">
               <h2>Ringkasan Dashboard</h2>
               <p>Pantau aktivitas konten dan pesan masuk RAMStudio secara real-time.</p>
          </div>
          <div class="page-head__actions">
               <a href="{{ route('admin.services.create') }}" class="btn btn--ghost btn--sm">
                    <i class="fa fa-plus"></i> Layanan
               </a>
               <a href="{{ route('admin.posts.create') }}" class="btn btn--primary btn--sm">
                    <i class="fa fa-pencil"></i> Tulis Artikel
               </a>
          </div>
     </div>

     <!-- Stat cards -->
     <div class="stat-grid">
          <div class="stat-card">
               <div class="stat-card__icon"><i class="fa fa-cogs"></i></div>
               <div class="stat-card__label">Total Layanan</div>
               <div class="stat-card__value">{{ number_format($stats['services']) }}</div>
               <div class="stat-card__hint"><i class="fa fa-tags"></i> {{ $stats['service_categories'] }} kategori</div>
          </div>

          <div class="stat-card stat-card--green">
               <div class="stat-card__icon"><i class="fa fa-briefcase"></i></div>
               <div class="stat-card__label">Portofolio</div>
               <div class="stat-card__value">{{ number_format($stats['portfolios']) }}</div>
               <div class="stat-card__hint"><i class="fa fa-image"></i> proyek terdaftar</div>
          </div>

          <div class="stat-card stat-card--amber">
               <div class="stat-card__icon"><i class="fa fa-newspaper-o"></i></div>
               <div class="stat-card__label">Artikel</div>
               <div class="stat-card__value">{{ number_format($stats['posts']) }}</div>
               <div class="stat-card__hint"><i class="fa fa-file-text-o"></i> {{ $stats['pages'] }} halaman</div>
          </div>

          <div class="stat-card stat-card--rose">
               <div class="stat-card__icon"><i class="fa fa-envelope"></i></div>
               <div class="stat-card__label">Pesan Masuk</div>
               <div class="stat-card__value">{{ number_format($stats['inquiries']) }}</div>
               <div class="stat-card__hint"><i class="fa fa-bell"></i> {{ $stats['new_inquiries'] }} belum ditangani</div>
          </div>
     </div>

     <div class="grid grid--3">
          <!-- Content summary -->
          <div class="card">
               <div class="card__head">
                    <h3>Ringkasan Konten</h3>
               </div>
               <div class="card__body">
                    <ul class="mini-list">
                         <li>
                              <i class="fa fa-comments-o" style="color:var(--adm-primary)"></i>
                              <span class="mini-list__title">Testimoni</span>
                              <span class="badge badge--primary" style="margin-left:auto">{{ $stats['testimonials'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-credit-card" style="color:var(--adm-success)"></i>
                              <span class="mini-list__title">Paket Harga</span>
                              <span class="badge badge--success"
                                   style="margin-left:auto">{{ $stats['pricing_packages'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-question-circle-o" style="color:var(--adm-warning)"></i>
                              <span class="mini-list__title">FAQ</span>
                              <span class="badge badge--warning" style="margin-left:auto">{{ $stats['faqs'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-file-text-o" style="color:var(--adm-accent)"></i>
                              <span class="mini-list__title">Halaman</span>
                              <span class="badge badge--info" style="margin-left:auto">{{ $stats['pages'] }}</span>
                         </li>
                    </ul>
               </div>
          </div>

          <!-- About summary -->
          <div class="card">
               <div class="card__head">
                    <h3>Tentang Kami</h3>
                    <div class="card__tools">
                         <a href="{{ route('admin.about.profile.edit') }}" class="btn btn--ghost btn--sm">Kelola</a>
                    </div>
               </div>
               <div class="card__body">
                    <ul class="mini-list">
                         <li>
                              <i class="fa fa-user-circle-o" style="color:var(--adm-primary)"></i>
                              <span class="mini-list__title">Owner</span>
                              <span class="badge badge--primary"
                                   style="margin-left:auto">{{ $stats['about_owners'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-users" style="color:var(--adm-success)"></i>
                              <span class="mini-list__title">Anggota Tim</span>
                              <span class="badge badge--success" style="margin-left:auto">{{ $stats['about_teams'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-balance-scale" style="color:var(--adm-warning)"></i>
                              <span class="mini-list__title">Visi, Misi & Nilai</span>
                              <span class="badge badge--warning"
                                   style="margin-left:auto">{{ $stats['about_values'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-road" style="color:var(--adm-accent)"></i>
                              <span class="mini-list__title">Perjalanan</span>
                              <span class="badge badge--info" style="margin-left:auto">{{ $stats['about_journeys'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-bar-chart" style="color:var(--adm-primary)"></i>
                              <span class="mini-list__title">Statistik</span>
                              <span class="badge badge--primary"
                                   style="margin-left:auto">{{ $stats['about_statistics'] }}</span>
                         </li>
                         <li>
                              <i class="fa fa-share-alt" style="color:var(--adm-success)"></i>
                              <span class="mini-list__title">Media Sosial</span>
                              <span class="badge badge--success"
                                   style="margin-left:auto">{{ $stats['about_social_links'] }}</span>
                         </li>
                    </ul>
               </div>
          </div>

          <!-- Inquiry status -->
          <div class="card">
               <div class="card__head">
                    <h3>Status Pesan Masuk</h3>
               </div>
               <div class="card__body">
                    @forelse ($inquiryBreakdown as $status => $total)
                         <div style="margin-bottom:15px">
                              <div class="d-flex align-center" style="justify-content:space-between;margin-bottom:6px">
                                   <span style="font-size:12.5px;font-weight:600">
                                        {{ ucwords(str_replace('_', ' ', $status ?: 'tanpa status')) }}
                                   </span>
                                   <span
                                        class="badge {{ $statusStyles[$status] ?? 'badge--muted' }}">{{ $total }}</span>
                              </div>
                              <div class="progress">
                                   <div class="progress__bar"
                                        style="width: {{ max(6, (int) round(($total / $maxInquiry) * 100)) }}%"></div>
                              </div>
                         </div>
                    @empty
                         <div class="mini-list__empty">Belum ada pesan masuk.</div>
                    @endforelse
               </div>
          </div>

          <!-- Latest posts -->
          <div class="card">
               <div class="card__head">
                    <h3>Artikel Terbaru</h3>
                    <div class="card__tools">
                         <a href="{{ route('admin.posts.index') }}" class="btn btn--ghost btn--sm">Lihat semua</a>
                    </div>
               </div>
               <div class="card__body">
                    <ul class="mini-list">
                         @forelse ($latestPosts as $post)
                              <li>
                                   <div style="min-width:0">
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="mini-list__title">
                                             {{ \Illuminate\Support\Str::limit($post->title, 40) }}
                                        </a>
                                        <span class="mini-list__meta">
                                             {{ optional($post->category)->name ?? 'Tanpa kategori' }}
                                        </span>
                                   </div>
                                   <span class="badge {{ $post->status === 'published' ? 'badge--success' : 'badge--muted' }}"
                                        style="margin-left:auto">{{ ucfirst($post->status) }}</span>
                              </li>
                         @empty
                              <li class="mini-list__empty">Belum ada artikel.</li>
                         @endforelse
                    </ul>
               </div>
          </div>
     </div>

     <!-- Latest inquiries -->
     <div class="card mt-16">
          <div class="card__head">
               <h3>Pesan Masuk Terbaru</h3>
               <div class="card__tools">
                    <a href="{{ route('admin.inquiries.index') }}" class="btn btn--ghost btn--sm">Kelola pesan</a>
               </div>
          </div>

          <div class="table-wrap">
               <table class="table-modern">
                    <thead>
                         <tr>
                              <th>Nama</th>
                              <th>Email</th>
                              <th>Subjek</th>
                              <th>Layanan</th>
                              <th>Status</th>
                              <th>Tanggal</th>
                         </tr>
                    </thead>
                    <tbody>
                         @forelse ($latestInquiries as $inquiry)
                              <tr>
                                   <td class="cell-strong">{{ $inquiry->name }}</td>
                                   <td>{{ $inquiry->email }}</td>
                                   <td>{{ $inquiry->subject ?? '-' }}</td>
                                   <td>
                                        @forelse ($inquiry->services as $service)
                                             <span class="chip">{{ $service->name }}</span>
                                        @empty
                                             <span class="text-muted">-</span>
                                        @endforelse
                                   </td>
                                   <td>
                                        <span class="badge {{ $statusStyles[$inquiry->status] ?? 'badge--muted' }}">
                                             {{ ucwords(str_replace('_', ' ', $inquiry->status ?? '-')) }}
                                        </span>
                                   </td>
                                   <td>{{ $inquiry->created_at?->format('d M Y H:i') }}</td>
                              </tr>
                         @empty
                              <tr>
                                   <td colspan="6">
                                        <div class="empty-state">
                                             <i class="fa fa-inbox"></i>
                                             Belum ada pesan masuk.
                                        </div>
                                   </td>
                              </tr>
                         @endforelse
                    </tbody>
               </table>
          </div>
     </div>
@endsection
