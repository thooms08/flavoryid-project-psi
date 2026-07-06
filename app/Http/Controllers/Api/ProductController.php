<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Models\Menu;
use App\Http\Resources\MenuResource;
use Illuminate\Support\Facades\DB;

class ProductController extends ApiController
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $query = Menu::query();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where('nama_menu', 'like', "%{$q}%");
        }

        $data = $query->paginate($perPage);
        return $this->success('List menus', MenuResource::collection($data), 200);
    }

    public function show($id)
    {
        $menu = Menu::find($id);
        if (! $menu) {
            return $this->error('Menu not found', null, 404);
        }
        return $this->success('Menu detail', new MenuResource($menu), 200);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $menu = Menu::create($request->validated());
            DB::commit();
            return $this->success('Menu created', new MenuResource($menu), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to create menu', $e->getMessage(), 500);
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $menu = Menu::find($id);
        if (! $menu) {
            return $this->error('Menu not found', null, 404);
        }

        try {
            DB::beginTransaction();
            $menu->update($request->validated());
            DB::commit();
            return $this->success('Menu updated', new MenuResource($menu), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to update menu', $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $menu = Menu::find($id);
        if (! $menu) {
            return $this->error('Menu not found', null, 404);
        }

        try {
            $menu->delete();
            return $this->success('Menu deleted', null, 200);
        } catch (\Exception $e) {
            return $this->error('Failed to delete menu', $e->getMessage(), 500);
        }
    }
}
