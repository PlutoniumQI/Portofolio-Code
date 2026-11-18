<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesFields;
use App\Http\Controllers\Controller;
use App\Services\FrontendRevalidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResourceController extends Controller
{
    use HandlesFields;

    /*
     * Route parameters cannot be injected positionally here: every resource
     * route carries a `resource` default after its `{record}` placeholder, so
     * `edit(string $resource, string $record)` would receive the id first.
     * Read the names explicitly instead.
     */

    public function index(Request $request): View
    {
        $resource = $this->resourceName($request);
        [$definition, $model] = $this->resolve($resource);

        $query = $model->newQuery();
        $term = trim((string) $request->query('q', ''));

        if ($term !== '' && ! empty($definition['search'])) {
            $query->where(function ($query) use ($definition, $term): void {
                foreach ($definition['search'] as $column) {
                    $query->orWhereLike($column, "%{$term}%");
                }
            });
        }

        if (! empty($definition['reorder'])) {
            // Reorderable resources are small lists. Rendering every row keeps
            // drag-and-drop sort orders coherent instead of renumbering a page.
            $records = $query->orderBy('sort_order')->orderBy('id')->get();
        } else {
            $records = $query->orderByDesc('updated_at')->orderByDesc('id')->paginate(20)->withQueryString();
        }

        return view('admin.resources.index', [
            'resource' => $resource,
            'definition' => $definition,
            'records' => $records,
            'term' => $term,
        ]);
    }

    public function create(Request $request): View
    {
        $resource = $this->resourceName($request);
        [$definition, $model] = $this->resolve($resource);

        return view('admin.resources.form', [
            'resource' => $resource,
            'definition' => $definition,
            'record' => $model->newInstance(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $resource = $this->resourceName($request);
        [$definition, $model] = $this->resolve($resource);

        $fields = $this->allFields($definition['groups']);
        $this->mergeCheckboxes($request, $fields);
        $validated = $request->validate($this->fieldRules($fields));

        $record = $model->newInstance();
        $record->fill($this->normalizeFields($fields, $validated));

        if (! empty($definition['reorder']) && empty($record->sort_order)) {
            $record->sort_order = ((int) $model->newQuery()->max('sort_order')) + 1;
        }

        $record->save();

        return redirect()
            ->route("admin.{$resource}.index")
            ->with('status', "{$definition['singular']} created.");
    }

    public function edit(Request $request): View
    {
        $resource = $this->resourceName($request);
        [$definition, $model] = $this->resolve($resource);

        return view('admin.resources.form', [
            'resource' => $resource,
            'definition' => $definition,
            'record' => $model->newQuery()->findOrFail($this->recordId($request)),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $resource = $this->resourceName($request);
        [$definition, $model] = $this->resolve($resource);

        $found = $model->newQuery()->findOrFail($this->recordId($request));
        $fields = $this->allFields($definition['groups']);
        $this->mergeCheckboxes($request, $fields);
        $validated = $request->validate($this->fieldRules($fields, $found));

        $found->fill($this->normalizeFields($fields, $validated));
        $found->save();

        return redirect()
            ->route("admin.{$resource}.index")
            ->with('status', "{$definition['singular']} updated.");
    }

    public function destroy(Request $request): RedirectResponse
    {
        $resource = $this->resourceName($request);
        [$definition, $model] = $this->resolve($resource);

        $model->newQuery()->findOrFail($this->recordId($request))->delete();

        return redirect()
            ->route("admin.{$resource}.index")
            ->with('status', "{$definition['singular']} deleted.");
    }

    public function reorder(Request $request): JsonResponse
    {
        [$definition, $model] = $this->resolve($this->resourceName($request));

        abort_unless(! empty($definition['reorder']), 404);

        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $ids = array_values(array_unique($validated['ids']));
        $existing = $model->newQuery()->whereIn('id', $ids)->pluck('id')->all();

        abort_unless(count($existing) === count($ids), 422, 'Unknown record in the reorder payload.');

        DB::transaction(function () use ($model, $ids): void {
            foreach ($ids as $index => $id) {
                $model->newQuery()->whereKey($id)->update(['sort_order' => $index + 1]);
            }
        });

        app(FrontendRevalidator::class)->schedule();

        return response()->json(['ok' => true]);
    }

    /**
     * The slug set by the route's `resource` default.
     */
    private function resourceName(Request $request): string
    {
        return (string) $request->route('resource');
    }

    /**
     * The `{record}` placeholder, always delivered as a string.
     */
    private function recordId(Request $request): int
    {
        return (int) $request->route('record');
    }

    /**
     * @return array{0: array<string, mixed>, 1: Model}
     */
    private function resolve(string $resource): array
    {
        $definition = config("admin.resources.{$resource}");

        abort_unless(is_array($definition), 404);

        /** @var class-string<Model> $class */
        $class = $definition['model'];

        return [$definition, new $class];
    }
}
