@extends('admin.layouts.app')

@section('title', ($record ? 'Edit ' : 'Tambah ') . $title)

@php
     $options = $options ?? [];
@endphp

@section('content')
     <div class="page-head">
          <div class="page-head__titles">
               <h2>{{ $record ? 'Edit' : 'Tambah' }} {{ $title }}</h2>
               <p>{{ $record ? 'Perbarui data yang sudah ada.' : 'Lengkapi formulir di bawah untuk menambah data baru.' }}
               </p>
          </div>
          <div class="page-head__actions">
               <a href="{{ route($routePrefix . '.index') }}" class="btn btn--ghost btn--sm">
                    <i class="fa fa-arrow-left"></i> Kembali
               </a>
          </div>
     </div>

     <div class="card">
          <div class="card__head">
               <h3>{{ $record ? 'Perbarui data' : 'Data baru' }}</h3>
               <small>{{ $title }}</small>
          </div>

          <div class="card__body">
               <form method="POST"
                    action="{{ $record ? route($routePrefix . '.update', $record->getKey()) : route($routePrefix . '.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if ($record)
                         @method('PUT')
                    @endif

                    <div class="form-grid">
                         @foreach ($fields as $field)
                              @php
                                   $name = $field['name'];
                                   $type = $field['type'] ?? 'text';
                                   $old = old(
                                       $name,
                                       $record ? $record->getAttribute($name) : $field['default'] ?? null,
                                   );
                                   $required = !empty($field['required']);
                                   $isWide =
                                       in_array($type, ['textarea', 'multiselect', 'checkboxes', 'images'], true) ||
                                       !empty($field['wide']);
                              @endphp

                              @if ($type === 'checkbox')
                                   <div class="field">
                                        <label>{{ $field['label'] }}</label>
                                        <input type="hidden" name="{{ $name }}" value="0">
                                        <label class="check" for="{{ $name }}">
                                             <input type="checkbox" id="{{ $name }}" name="{{ $name }}"
                                                  value="1" @checked((bool) $old)>
                                             {{ $field['label'] }}
                                        </label>
                                   </div>
                              @elseif ($type === 'multiselect')
                                   @php
                                        $relation = \Illuminate\Support\Str::camel($name);
                                        $selected = old(
                                            $name,
                                            $record && method_exists($record, $relation)
                                                ? $record->{$relation}->pluck('id')->all()
                                                : [],
                                        );
                                        $selected = array_map('strval', is_array($selected) ? $selected : []);
                                        $optionList = $options[$field['options_key'] ?? ''] ?? [];
                                   @endphp
                                   <div class="field field--full">
                                        <label for="{{ $name }}">
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>
                                        <select id="{{ $name }}" name="{{ $name }}[]" class="form-control"
                                             multiple size="6">
                                             @foreach ($optionList as $optionValue => $optionLabel)
                                                  <option value="{{ $optionValue }}" @selected(in_array((string) $optionValue, old($name, $selected), true))>
                                                       {{ $optionLabel }}
                                                  </option>
                                             @endforeach
                                        </select>
                                        <span class="hint">Tahan Ctrl (Windows) / Cmd (Mac) untuk memilih lebih dari
                                             satu.</span>
                                   </div>
                              @elseif ($type === 'checkboxes')
                                   @php
                                        $relation = \Illuminate\Support\Str::camel($name);
                                        $selected = old(
                                            $name,
                                            $record && method_exists($record, $relation)
                                                ? $record->{$relation}->pluck('id')->all()
                                                : [],
                                        );
                                        $selected = array_map('strval', is_array($selected) ? $selected : []);
                                        $optionList = $options[$field['options_key'] ?? ''] ?? [];
                                   @endphp
                                   <div class="field field--full">
                                        <label>
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>
                                        <div class="check-grid">
                                             @forelse ($optionList as $optionValue => $optionLabel)
                                                  <label class="check" for="{{ $name }}_{{ $optionValue }}">
                                                       <input type="checkbox" id="{{ $name }}_{{ $optionValue }}"
                                                            name="{{ $name }}[]" value="{{ $optionValue }}"
                                                            @checked(in_array((string) $optionValue, $selected, true))>
                                                       {{ $optionLabel }}
                                                  </label>
                                             @empty
                                                  <span class="hint">Belum ada pilihan tersedia.</span>
                                             @endforelse
                                        </div>
                                        @if (!empty($field['help']))
                                             <span class="hint">{{ $field['help'] }}</span>
                                        @endif
                                   </div>
                              @elseif ($type === 'select')
                                   @php $optionList = $field['options'] ?? ($options[$field['options_key'] ?? ''] ?? []); @endphp
                                   <div class="field">
                                        <label for="{{ $name }}">
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>
                                        <select id="{{ $name }}" name="{{ $name }}" class="form-control">
                                             @if (empty($field['required']))
                                                  <option value="">-- Pilih {{ $field['label'] }} --</option>
                                             @endif
                                             @foreach ($optionList as $optionValue => $optionLabel)
                                                  <option value="{{ $optionValue }}" @selected((string) old($name, $old) === (string) $optionValue)>
                                                       {{ $optionLabel }}
                                                  </option>
                                             @endforeach
                                        </select>
                                   </div>
                              @elseif ($type === 'textarea')
                                   <div class="field field--full">
                                        <label for="{{ $name }}">
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>
                                        <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] ?? 4 }}" class="form-control"
                                             @if ($required) required @endif>{{ $old }}</textarea>
                                        @if (!empty($field['help']))
                                             <span class="hint">{{ $field['help'] }}</span>
                                        @endif
                                   </div>
                              @elseif ($type === 'images')
                                   @php
                                        $existing = $extra['galleryImages'] ?? collect();
                                   @endphp
                                   <div class="field field--full">
                                        <label for="{{ $name }}">
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>

                                        @if ($existing->isNotEmpty())
                                             <div class="gallery-grid">
                                                  @foreach ($existing as $image)
                                                       <label class="gallery-item" for="remove_image_{{ $image->id }}">
                                                            <img src="{{ \App\Support\Media::url($image->image_path) }}"
                                                                 alt="{{ $image->alt_text ?: $field['label'] }}">
                                                            <span class="gallery-item__remove">
                                                                 <input type="checkbox"
                                                                      id="remove_image_{{ $image->id }}"
                                                                      name="remove_images[]" value="{{ $image->id }}">
                                                                 Hapus
                                                            </span>
                                                       </label>
                                                  @endforeach
                                             </div>
                                             <span class="hint">Centang "Hapus" pada foto yang ingin dibuang, lalu
                                                  simpan.</span>
                                        @endif

                                        <input type="file" id="{{ $name }}" name="{{ $name }}[]"
                                             class="form-control" accept="image/*" multiple>
                                        <span
                                             class="hint">{{ $field['help'] ?? 'Format: jpg, jpeg, png, webp. Maks 2 MB per foto.' }}</span>
                                   </div>
                              @elseif ($type === 'image')
                                   <div class="field field--full">
                                        <label for="{{ $name }}">
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>
                                        @if ($record && $record->getAttribute($name))
                                             <div class="thumb-preview">
                                                  <img src="{{ \App\Support\Media::url($record->getAttribute($name)) }}"
                                                       alt="">
                                             </div>
                                        @endif
                                        <input type="file" id="{{ $name }}" name="{{ $name }}"
                                             class="form-control" accept="image/*">
                                        <span class="hint">Format: jpg, jpeg, png, webp. Maks 3 MB.</span>
                                   </div>
                              @else
                                   <div class="field">
                                        <label for="{{ $name }}">
                                             {{ $field['label'] }}
                                             @if ($required)
                                                  <span class="req">*</span>
                                             @endif
                                        </label>
                                        <input type="{{ $type }}" id="{{ $name }}"
                                             name="{{ $name }}" class="form-control"
                                             value="{{ $type === 'datetime-local' && $old ? \Illuminate\Support\Carbon::parse($old)->format('Y-m-d\TH:i') : $old }}"
                                             @if (!empty($field['step'])) step="{{ $field['step'] }}" @endif
                                             @if (!empty($field['min'])) min="{{ $field['min'] }}" @endif
                                             @if (!empty($field['max'])) max="{{ $field['max'] }}" @endif
                                             @if ($required) required @endif>
                                        @if (!empty($field['help']))
                                             <span class="hint">{{ $field['help'] }}</span>
                                        @endif
                                   </div>
                              @endif
                         @endforeach
                    </div>

                    <div class="form-actions">
                         <a href="{{ route($routePrefix . '.index') }}" class="btn btn--ghost">Batal</a>
                         <button type="submit" class="btn btn--success">
                              <i class="fa fa-save"></i> {{ $record ? 'Perbarui Data' : 'Simpan Data' }}
                         </button>
                    </div>
               </form>
          </div>
     </div>
@endsection
