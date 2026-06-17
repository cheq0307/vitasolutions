<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    // ─── Index ───────────────────────────────────────────────────────────────

    public function index()
    {
        $centerId = Auth::user()->center_id;

        $products = Product::forCenter($centerId)
                           ->orderBy('name')
                           ->paginate(15);

        return view('admin.productos.index', compact('products'));
    }

    // ─── Create ──────────────────────────────────────────────────────────────

    public function create()
    {
        $centerId   = Auth::user()->center_id;
        $categories = Product::forCenter($centerId)
                              ->select('category')
                              ->distinct()
                              ->pluck('category')
                              ->filter()
                              ->sort()
                              ->values();

        return view('admin.productos.create', compact('categories'));
    }

    // ─── Store ───────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'brand'       => 'nullable|string|max:100',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price'       => 'nullable|numeric|min:0',
            'image_url'   => 'nullable|url|max:500',
            'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data['center_id'] = Auth::user()->center_id;
        $data['is_active'] = $request->boolean('is_active', true);

        // Imagen: archivo local tiene prioridad sobre URL
        if ($request->hasFile('image_file')) {
            $data['image_path'] = $this->imageService->store($request->file('image_file'), 'products');
            $data['image_url']  = null; // limpiamos URL si suben archivo
        }

        Product::create($data);

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto creado correctamente.');
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(Product $producto)
    {
        $this->authorizeCenter($producto);

        $centerId   = Auth::user()->center_id;
        $categories = Product::forCenter($centerId)
                              ->select('category')
                              ->distinct()
                              ->pluck('category')
                              ->filter()
                              ->sort()
                              ->values();

        return view('admin.productos.edit', compact('producto', 'categories'));
    }

    // ─── Update ──────────────────────────────────────────────────────────────

    public function update(Request $request, Product $producto)
    {
        $this->authorizeCenter($producto);

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'brand'       => 'nullable|string|max:100',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price'       => 'nullable|numeric|min:0',
            'image_url'   => 'nullable|url|max:500',
            'image_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_image'=> 'boolean',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        // Lógica de imagen
        if ($request->hasFile('image_file')) {
            // Nueva imagen: reemplaza la anterior (local o URL)
            $data['image_path'] = $this->imageService->replace(
                $request->file('image_file'),
                $producto->image_path,
                'products'
            );
            $data['image_url'] = null;

        } elseif ($request->boolean('remove_image')) {
            // Borrar imagen actual sin reemplazar
            $this->imageService->delete($producto->image_path);
            $data['image_path'] = null;
            $data['image_url']  = null;

        } elseif (!empty($data['image_url'])) {
            // URL externa: borra archivo local si había
            $this->imageService->delete($producto->image_path);
            $data['image_path'] = null;

        } else {
            // No cambió nada — conservar lo que había
            unset($data['image_url']);
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    // ─── Toggle activo ───────────────────────────────────────────────────────

    public function toggle(Product $producto)
    {
        $this->authorizeCenter($producto);
        $producto->update(['is_active' => !$producto->is_active]);
        return back()->with('success', 'Estado del producto actualizado.');
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────

    public function destroy(Product $producto)
    {
        $this->authorizeCenter($producto);
        $this->imageService->delete($producto->image_path);
        $producto->delete();
        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto eliminado.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeCenter(Product $producto): void
    {
        abort_if($producto->center_id !== Auth::user()->center_id, 403);
    }
}