<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Resources;
use App\Http\Controllers\Controller;
use App\Support\ImageUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    public function index(Request $request, string $resource)
    {
        $r = Resources::get($resource);
        $query = $r['model']::query();

        if ($term = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($r, $term) {
                foreach ($r['search'] as $col) {
                    $q->orWhere($col, 'like', '%'.$term.'%');
                }
            });
        }

        [$col, $dir] = $r['order'];
        $items = $query->orderBy($col, $dir)->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.resource.index', compact('r', 'items'));
    }

    public function create(string $resource)
    {
        $r = Resources::get($resource);
        abort_if($r['no_create'] ?? false, 404);
        $item = new $r['model'];
        foreach ($r['fields'] as $name => $f) {
            if (array_key_exists('default', $f)) {
                $item->{$name} = $f['default'];
            }
        }

        return view('admin.resource.form', compact('r', 'item'));
    }

    public function store(Request $request, string $resource)
    {
        $r = Resources::get($resource);
        abort_if($r['no_create'] ?? false, 404);
        $item = new $r['model'];
        $this->fill($request, $r, $item);
        $item->save();
        $this->flush();

        return redirect()->route('admin.resource.edit', [$resource, $item->getKey()])->with('status', ucfirst($r['singular']).' created.');
    }

    public function edit(string $resource, int $id)
    {
        $r = Resources::get($resource);
        $item = $r['model']::findOrFail($id);

        return view('admin.resource.form', compact('r', 'item'));
    }

    public function update(Request $request, string $resource, int $id)
    {
        $r = Resources::get($resource);
        $item = $r['model']::findOrFail($id);
        $this->fill($request, $r, $item);
        $item->save();
        $this->flush();

        return back()->with('status', 'Changes saved.');
    }

    public function destroy(string $resource, int $id)
    {
        $r = Resources::get($resource);
        abort_if($r['no_delete'] ?? false, 404);
        $item = $r['model']::findOrFail($id);
        foreach ($r['fields'] as $name => $f) {
            if ($f['type'] === 'image') {
                ImageUpload::delete($item->{$name});
            }
        }
        $item->delete();
        $this->flush();

        return redirect()->route('admin.resource.index', $resource)->with('status', ucfirst($r['singular']).' deleted.');
    }

    public function toggle(string $resource, int $id)
    {
        $r = Resources::get($resource);
        $item = $r['model']::findOrFail($id);
        abort_unless(isset($r['fields']['is_published']), 404);
        $item->is_published = ! $item->is_published;
        $item->save();
        $this->flush();

        return back()->with('status', $item->is_published ? 'Published.' : 'Hidden from the site.');
    }

    protected function fill(Request $request, array $r, Model $item): void
    {
        $rules = [];
        foreach ($r['fields'] as $name => $f) {
            if ($f['readonly'] ?? false) {
                continue;
            }
            $rule = [($f['required'] ?? false) ? 'required' : 'nullable'];
            $rule = array_merge($rule, match ($f['type']) {
                'text' => ['string', 'max:'.($f['max'] ?? 255)],
                'textarea' => ['string', 'max:'.($f['max'] ?? 5000)],
                'markdown' => ['string', 'max:100000'],
                'list' => ['string', 'max:10000'],
                'url' => ['url', 'max:255'],
                'email' => ['email', 'max:255'],
                'number' => ['integer', 'min:0', 'max:100000'],
                'date', 'datetime' => ['date'],
                'select' => [Rule::in(array_keys($f['options']))],
                'icon' => [Rule::in(array_keys(Resources::ICONS))],
                'image' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
                'bool' => ['boolean'],
                default => ['string'],
            });
            if ($name === 'slug') {
                $rule[] = 'alpha_dash';
            }
            $rules[$name] = $rule;
        }

        $data = $request->validate($rules);

        foreach ($r['fields'] as $name => $f) {
            if ($f['readonly'] ?? false) {
                continue;
            }
            switch ($f['type']) {
                case 'image':
                    if ($request->boolean('remove_'.$name)) {
                        ImageUpload::delete($item->{$name});
                        $item->{$name} = null;
                    }
                    if ($request->hasFile($name)) {
                        ImageUpload::delete($item->{$name});
                        $item->{$name} = ImageUpload::store($request->file($name), $r['key']);
                    }
                    break;
                case 'bool':
                    $item->{$name} = $request->boolean($name);
                    break;
                case 'list':
                    $lines = preg_split('/\r\n|\r|\n/', (string) ($data[$name] ?? ''));
                    $item->{$name} = array_values(array_filter(array_map('trim', $lines), fn ($l) => $l !== ''));
                    break;
                case 'number':
                    $item->{$name} = (int) ($data[$name] ?? 0);
                    break;
                default:
                    $item->{$name} = $data[$name] ?? null;
            }
        }
    }

    protected function flush(): void
    {
        Cache::forget('site_settings');
        Cache::forget('sitemap_xml');
    }
}
