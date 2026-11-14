@extends('admin.layouts.app')

@section('title', $title)

@php
     // Resolve values such as "category.name" or relation counts.
     $value = function ($record, string $key) {
         $segments = explode('.', $key);
         $current = $record;
         foreach ($segments as $segment) {
             if (is_null($current)) {
                 return null;
             }
             $current = is_object($current) ? $current->{$segment} ?? null : null;
         }
         return $current;
     };

     $hasActiveFilter = collect($filters)->contains(
         fn($f) => request($f['name']) !== null && request($f['name']) !== '',
     );
@endphp

@section('content')
     <div class="page-head">
          <div class="page-head__titles">
               <h2>{{ $title }}</h2>
               <p>Kelola data {{ \Illuminate\Support\Str::lower($title) }} &middot;
                    {{ number_format($records->total()) }} data terdaftar.</p>
          </div>
          <div class="page-head__actions">
               <a href="{{ route($routePrefix . '.create') }}" class="btn btn--primary btn--sm">
                    <i class="fa fa-plus"></i> Tambah {{ $title }}
               </a>
          </div>
     </div>

     <div class="card">
          <form method="GET" action="{{ route($routePrefix . '.index') }}" class="toolbar">
               <div class="toolbar__search">
                    <i class="fa fa-search"></i>
                    <input type="text" name="q" class="form-control"
                         placeholder="Cari {{ \Illuminate\Support\Str::lower($title) }}..." value="{{ $search }}">
               </div>

               @foreach ($filters as $filter)
                    <select name="{{ $filter['name'] }}" class="form-control" style="max-width:180px"
                         onchange="this.form.submit()">
                         <option value="">{{ $filter['label'] }}</option>
                         @foreach ($filter['options'] as $optionValue => $optionLabel)
                              <option value="{{ $optionValue }}" @selected((string) request($filter['name']) === (string) $optionValue)>
                                   {{ $optionLabel }}
                              </option>
                         @endforeach
                    </select>
               @endforeach

               <div class="toolbar__actions">
                    <button type="submit" class="btn btn--ghost btn--sm">
                         <i class="fa fa-search"></i> Cari
                    </button>
                    @if ($search || $hasActiveFilter)
                         <a href="{{ route($routePrefix . '.index') }}" class="btn btn--ghost btn--sm" title="Reset filter">
                              <i class="fa fa-times"></i> Reset
                         </a>
                    @endif
               </div>
          </form>

          <div class="table-wrap">
               <table class="table-modern">
                    <thead>
                         <tr>
                              <th class="row-index">#</th>
                              @foreach ($columns as $column)
                                   <th>{{ $column['label'] }}</th>
                              @endforeach
                              <th style="text-align:right">Aksi</th>
                         </tr>
                    </thead>
                    <tbody>
                         @forelse ($records as $index => $record)
                              <tr>
                                   <td class="row-index">{{ $records->firstItem() + $index }}</td>

                                   @foreach ($columns as $column)
                                        @php
                                             $raw = $value($record, $column['key']);
                                             $type = $column['type'] ?? 'text';
                                        @endphp

                                        <td>
                                             @switch($type)
                                                  @case('image')
                                                       @if ($raw)
                                                            <img src="{{ \App\Support\Media::url($raw) }}" alt=""
                                                                 class="thumb">
                                                       @else
                                                            <span class="text-muted">-</span>
                                                       @endif
                                                  @break

                                                  @case('bool')
                                                       @if ($raw)
                                                            <span class="badge badge--success"><i class="fa fa-check"></i> Ya</span>
                                                       @else
                                                            <span class="badge badge--muted"><i class="fa fa-minus"></i> Tidak</span>
                                                       @endif
                                                  @break

                                                  @case('code')
                                                       <code>{{ $raw }}</code>
                                                  @break

                                                  @case('count')
                                                       @if (!empty($column['relation']) && method_exists($record, $column['relation']))
                                                            <span
                                                                 class="badge badge--info">{{ $record->{$column['relation']}->count() }}</span>
                                                       @else
                                                            {{ $raw ?? 0 }}
                                                       @endif
                                                  @break

                                                  @case('relation')
                                                       {{ $raw ?? '-' }}
                                                  @break

                                                  @case('date')
                                                       {{ $raw ? \Illuminate\Support\Carbon::parse($raw)->format('d M Y') : '-' }}
                                                  @break

                                                  @case('price')
                                                       {{ $raw !== null ? 'Rp ' . number_format((float) $raw, 0, ',', '.') : '-' }}
                                                  @break

                                                  @case('rating')
                                                       @for ($i = 0; $i < (int) ($raw ?? 0); $i++)
                                                            <i class="fa fa-star" style="color:#f59e0b"></i>
                                                       @endfor
                                                  @break

                                                  @case('badge')
                                                       <span class="badge badge--primary">
                                                            {{ ucwords(str_replace('_', ' ', (string) $raw)) }}
                                                       </span>
                                                  @break

                                                  @case('truncate')
                                                       {{ \Illuminate\Support\Str::limit((string) $raw, 40) }}
                                                  @break

                                                  @case('tags')
                                                       @php
                                                            $items =
                                                                $raw instanceof \Illuminate\Support\Collection
                                                                    ? $raw
                                                                    : collect();
                                                       @endphp
                                                       @if ($items->isNotEmpty())
                                                            <div class="tag-list">
                                                                 @foreach ($items as $item)
                                                                      <span class="badge badge--info">
                                                                           {{ is_object($item) ? $item->name ?? ($item->title ?? '') : $item }}
                                                                      </span>
                                                                 @endforeach
                                                            </div>
                                                       @else
                                                            <span class="text-muted">-</span>
                                                       @endif
                                                  @break

                                                  @case('gallery')
                                                       @php
                                                            $items =
                                                                $raw instanceof \Illuminate\Support\Collection
                                                                    ? $raw
                                                                    : collect();
                                                       @endphp
                                                       @if ($items->isNotEmpty())
                                                            <div class="gallery-stack">
                                                                 @foreach ($items->take(3) as $image)
                                                                      <img src="{{ \App\Support\Media::url($image->image_path) }}"
                                                                           alt="" class="thumb thumb--sm">
                                                                 @endforeach
                                                                 @if ($items->count() > 3)
                                                                      <span
                                                                           class="badge badge--muted">+{{ $items->count() - 3 }}</span>
                                                                 @endif
                                                            </div>
                                                       @else
                                                            <span class="text-muted">-</span>
                                                       @endif
                                                  @break

                                                  @default
                                                       {{ $raw }}
                                             @endswitch
                                        </td>
                                   @endforeach

                                   <td class="cell-actions">
                                        <a href="{{ route($routePrefix . '.edit', $record->getKey()) }}"
                                             class="btn btn--ghost btn--sm btn--icon" title="Edit">
                                             <i class="fa fa-pencil"></i>
                                        </a>
                                        <form method="POST"
                                             action="{{ route($routePrefix . '.destroy', $record->getKey()) }}"
                                             style="display:inline"
                                             onsubmit="return confirm('Hapus data ini? Tindakan tidak dapat dibatalkan.');">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit" class="btn btn--danger btn--sm btn--icon" title="Hapus">
                                                  <i class="fa fa-trash"></i>
                                             </button>
                                        </form>
                                   </td>
                              </tr>
                              @empty
                                   <tr>
                                        <td colspan="{{ count($columns) + 2 }}">
                                             <div class="empty-state">
                                                  <i class="fa fa-inbox"></i>
                                                  Belum ada data{{ $search ? ' untuk pencarian "' . $search . '"' : '' }}.
                                             </div>
                                        </td>
                                   </tr>
                              @endforelse
                         </tbody>
                    </table>
               </div>

               <div class="pagination-info">
                    <span>
                         Menampilkan {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }}
                         dari {{ number_format($records->total()) }} data
                    </span>
                    @include('admin.partials.pagination', ['paginator' => $records])
               </div>
          </div>
     @endsection
