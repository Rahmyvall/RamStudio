<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

/**
 * Base class for the REST API v1 controllers.
 *
 * Provides a consistent JSON envelope, pagination, full-text-ish search,
 * exact-match filtering and whitelisted sorting shared by every resource.
 */
abstract class ApiController extends Controller
{
  /** @return class-string<Model> */
  abstract protected function modelClass(): string;

  /** @return class-string<JsonResource> */
  abstract protected function resourceClass(): string;

  /** Columns searched with a LIKE when `?search=` is present. */
  protected function searchable(): array
  {
    return [];
  }

  /** Columns accepted as exact-match filters, e.g. `?status=new`. */
  protected function filterable(): array
  {
    return [];
  }

  /** Columns accepted for `?sort=` (default fallback is `id`). */
  protected function sortable(): array
  {
    return ['id'];
  }

  /** Relations eager loaded on the index listing. */
  protected function indexRelations(): array
  {
    return [];
  }

  /** Relations eager loaded on the detail endpoint. */
  protected function showRelations(): array
  {
    return [];
  }

  public function index(Request $request): AnonymousResourceCollection
  {
    $query = $this->modelClass()::query()->with($this->indexRelations());

    $this->applySearch($query, $request);
    $this->applyFilters($query, $request);
    $this->applySort($query, $request);

    $perPage = $this->perPage($request);
    $paginator = $query->paginate($perPage)->withQueryString();

    return $this->paginated($paginator);
  }

  /**
   * Show a single resource. The route parameter may arrive as a bound
   * model instance or as a raw identifier depending on the route.
   */
  public function show(Model|string $id): JsonResource
  {
    $record = $id instanceof Model
      ? $id->load($this->showRelations())
      : $this->modelClass()::query()->with($this->showRelations())->findOrFail($id);

    return $this->makeResource($record);
  }

  protected function findRecord(Model|string $id): Model
  {
    return $id instanceof Model
      ? $id
      : $this->modelClass()::query()->findOrFail($id);
  }

  /**
   * Wrap a single model instance in its API resource.
   */
  protected function makeResource(Model $model): JsonResource
  {
    $class = $this->resourceClass();

    return new $class($model);
  }

  protected function paginated(LengthAwarePaginator $paginator): AnonymousResourceCollection
  {
    $class = $this->resourceClass();

    return $class::collection($paginator)->additional([
      'success' => true,
      'message' => 'Data berhasil diambil.',
    ]);
  }

  protected function createdResponse(Model $model, string $message = 'Data berhasil dibuat.'): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $this->makeResource($model),
    ], 201);
  }

  protected function updatedResponse(Model $model, string $message = 'Data berhasil diperbarui.'): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $this->makeResource($model),
    ]);
  }

  protected function deletedResponse(string $message = 'Data berhasil dihapus.'): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => null,
    ]);
  }

  protected function applySearch(Builder $query, Request $request): void
  {
    $search = trim((string) $request->query('search', ''));

    if ($search === '' || $this->searchable() === []) {
      return;
    }

    $query->where(function (Builder $query) use ($search) {
      foreach ($this->searchable() as $column) {
        $query->orWhere($column, 'like', "%{$search}%");
      }
    });
  }

  protected function applyFilters(Builder $query, Request $request): void
  {
    foreach ($this->filterable() as $column) {
      $value = $request->query($column);

      if ($value === null || $value === '') {
        continue;
      }

      $query->where($column, $value);
    }
  }

  protected function applySort(Builder $query, Request $request): void
  {
    $sort = (string) $request->query('sort', 'id');
    $direction = strtolower((string) $request->query('order', 'desc')) === 'asc' ? 'asc' : 'desc';

    if (! in_array($sort, $this->sortable(), true)) {
      $sort = 'id';
    }

    $query->orderBy($sort, $direction);
  }

  protected function perPage(Request $request): int
  {
    $perPage = (int) $request->query('per_page', 15);

    return max(1, min($perPage, 100));
  }
}
