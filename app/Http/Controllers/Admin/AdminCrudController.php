<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Shared CRUD behaviour for the admin dashboard.
 *
 * Each concrete admin controller describes its module through a handful of
 * small configuration methods (model, columns, fields, rules) while the
 * heavy lifting (search, pagination, uploads, redirects) lives here so every
 * module behaves consistently.
 */
abstract class AdminCrudController extends Controller
{
  /** Fully qualified model class handled by the controller. */
  abstract protected function model(): string;

  /** Human readable module name, e.g. "Services". */
  abstract protected function moduleTitle(): string;

  /** Route name prefix, e.g. "admin.services". */
  abstract protected function routePrefix(): string;

  /** Column definitions shown in the index table. */
  abstract protected function columns(): array;

  /** Form field definitions used by the shared form view. */
  abstract protected function fields(): array;

  /** Validation rules. `$model` is null on store, the instance on update. */
  abstract protected function rules(Request $request, ?Model $model): array;

  /** Attributes eager loaded on the index listing. */
  protected function indexWith(): array
  {
    return [];
  }

  /** Attribute names that are searchable through the `q` query string. */
  protected function searchable(): array
  {
    return ['name'];
  }

  /** Directory (inside the public disk) used to store uploaded files. */
  protected function uploadDirectory(): string
  {
    return 'uploads/' . Str::kebab(Str::pluralStudly(class_basename($this->model())));
  }

  /**
   * Extra data merged into the model attributes before saving.
   *
   * @return array<string, mixed>
   */
  protected function prepareData(Request $request, array $data, ?Model $model): array
  {
    return $data;
  }

  /**
   * Persist many-to-many relations (e.g. tags, technologies, services).
   */
  protected function syncRelations(Request $request, Model $model): void
  {
    //
  }

  /**
   * Filter definitions rendered above the table.
   *
   * @return array<int, array{name:string,label:string,options:array<string,string>}>
   */
  protected function filters(): array
  {
    return [];
  }

  /**
   * Select options exposed to the form view.
   *
   * @return array<string, mixed>
   */
  protected function formOptions(): array
  {
    return [];
  }

  /**
   * Extra variables merged into the create/edit form view.
   *
   * @return array<string, mixed>
   */
  protected function formData(?Model $model): array
  {
    return [];
  }

  public function index(Request $request): View
  {
    $query = $this->model()::query()->with($this->indexWith());

    $search = trim((string) $request->query('q'));

    if ($search !== '') {
      $columns = $this->searchable();
      $query->where(function ($query) use ($columns, $search) {
        foreach ($columns as $column) {
          $query->orWhere($column, 'like', "%{$search}%");
        }
      });
    }

    foreach ($this->filters() as $filter) {
      $value = $request->query($filter['name']);
      if ($value !== null && $value !== '') {
        $query->where($filter['name'], $value);
      }
    }

    $records = $query->latest('id')->paginate(15)->withQueryString();

    return view('admin.crud.index', [
      'title' => $this->moduleTitle(),
      'routePrefix' => $this->routePrefix(),
      'columns' => $this->columns(),
      'records' => $records,
      'filters' => $this->filters(),
      'search' => $search !== '' ? $search : null,
    ]);
  }

  public function create(): View
  {
    return view('admin.crud.form', [
      'title' => $this->moduleTitle(),
      'routePrefix' => $this->routePrefix(),
      'fields' => $this->fields(),
      'record' => null,
      'options' => $this->formOptions(),
      'extra' => $this->formData(null),
    ]);
  }

  public function store(Request $request): RedirectResponse
  {
    $data = $request->validate($this->rules($request, null));
    $data = $this->prepareData($request, $data, null);
    $data = $this->handleUploads($request, $data, null);

    $record = $this->model()::query()->create($data);
    $this->syncRelations($request, $record);

    return redirect()
      ->route($this->routePrefix() . '.index')
      ->with('success', $this->moduleTitle() . ' berhasil ditambahkan.');
  }

  public function edit(string $id): View
  {
    $record = $this->resolveRecord($id);

    return view('admin.crud.form', [
      'title' => $this->moduleTitle(),
      'routePrefix' => $this->routePrefix(),
      'fields' => $this->fields(),
      'record' => $record,
      'options' => $this->formOptions(),
      'extra' => $this->formData($record),
    ]);
  }

  public function update(Request $request, string $id): RedirectResponse
  {
    $record = $this->resolveRecord($id);
    $data = $request->validate($this->rules($request, $record));
    $data = $this->prepareData($request, $data, $record);
    $data = $this->handleUploads($request, $data, $record);

    $record->update($data);
    $this->syncRelations($request, $record);

    return redirect()
      ->route($this->routePrefix() . '.index')
      ->with('success', $this->moduleTitle() . ' berhasil diperbarui.');
  }

  public function destroy(string $id): RedirectResponse
  {
    $this->resolveRecord($id)->delete();

    return redirect()
      ->route($this->routePrefix() . '.index')
      ->with('success', $this->moduleTitle() . ' berhasil dihapus.');
  }

  /**
   * Store uploaded files referenced by the field definitions.
   *
   * @param  array<string, mixed>  $data
   * @return array<string, mixed>
   */
  protected function handleUploads(Request $request, array $data, ?Model $model): array
  {
    foreach ($this->fields() as $field) {
      if (($field['type'] ?? null) !== 'image' || ! $request->hasFile($field['name'])) {
        continue;
      }

      $path = $request->file($field['name'])->store($this->uploadDirectory(), 'public');

      // Remove the previous file so orphaned uploads do not pile up.
      if ($model && $model->getAttribute($field['name'])) {
        Storage::disk('public')->delete($model->getAttribute($field['name']));
      }

      $data[$field['name']] = $path;
    }

    return $data;
  }

  protected function resolveRecord(string $id): Model
  {
    return $this->model()::query()->findOrFail($id);
  }
}
