<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Retorna el center_id del admin autenticado.
     * El superadmin no tiene center_id, pero no debería llegar aquí.
     */
    private function centerScope()
    {
        return auth()->user()->center_id;
    }

    public function index()
    {
        $productos = Product::where('center_id', $this->centerScope())
            ->latest()
            ->paginate(15);

        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $categories = Product::where('center_id', $this->centerScope())
            ->distinct()->pluck('category')->filter()->values();

        $baseCategories = ['Vitaminas', 'Minerales', 'Proteínas', 'Omega', 'Probióticos', 'Antioxidantes'];
        $categories = $categories->merge($baseCategories)->unique()->sort()->values();

        // Productos sugeridos por superadmin que no están en el catálogo local
        $sugeridos = Product::whereNull('center_id')
            ->where('is_suggested', true)
            ->whereNotIn('name', Product::where('center_id', $this->centerScope())->pluck('name'))
            ->get();

        return view('admin.productos.create', compact('categories', 'sugeridos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:120',
            'brand'       => 'nullable|string|max:80',
            'category'    => 'nullable|string|max:100',
            'new_category'=> 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'price'       => 'nullable|numeric|min:0',
            'active'      => 'boolean',
        ]);

        $category = $request->category === 'nueva'
            ? $request->new_category
            : $request->category;

        Product::create([
            'name'        => $request->name,
            'brand'       => $request->brand,
            'category'    => $category,
            'description' => $request->description,
            'image_url'   => $request->image_url,
            'price'       => $request->price ?? 0,
            'active'      => $request->boolean('active', true),
            'center_id'   => $this->centerScope(),
        ]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $producto)
    {
        $this->authorizeCenter($producto);
        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Product $producto)
    {
        $this->authorizeCenter($producto);

        $categories = Product::where('center_id', $this->centerScope())
            ->distinct()->pluck('category')->filter()->values();

        $baseCategories = ['Vitaminas', 'Minerales', 'Proteínas', 'Omega', 'Probióticos', 'Antioxidantes'];
        $categories = $categories->merge($baseCategories)->unique()->sort()->values();

        return view('admin.productos.edit', compact('producto', 'categories'));
    }

    public function update(Request $request, Product $producto)
    {
        $this->authorizeCenter($producto);

        $request->validate([
            'name'        => 'required|string|max:120',
            'brand'       => 'nullable|string|max:80',
            'category'    => 'nullable|string|max:100',
            'new_category'=> 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url',
            'price'       => 'nullable|numeric|min:0',
            'active'      => 'boolean',
        ]);

        $category = $request->category === 'nueva'
            ? $request->new_category
            : $request->category;

        $producto->update([
            'name'        => $request->name,
            'brand'       => $request->brand,
            'category'    => $category,
            'description' => $request->description,
            'image_url'   => $request->image_url,
            'price'       => $request->price ?? 0,
            'active'      => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $producto)
    {
        $this->authorizeCenter($producto);
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado.');
    }

    // Importar producto sugerido al catálogo local
    public function importSugerido(Product $producto)
    {
        if (!$producto->is_suggested) {
            return back()->with('error', 'Este producto no es un producto sugerido.');
        }

        Product::create([
            'name'        => $producto->name,
            'brand'       => $producto->brand,
            'category'    => $producto->category,
            'description' => $producto->description,
            'image_url'   => $producto->image_url,
            'price'       => $producto->price,
            'active'      => true,
            'center_id'   => $this->centerScope(),
            'is_suggested'=> false,
        ]);

        return back()->with('success', "Producto \"{$producto->name}\" importado a tu catálogo.");
    }

    /**
     * Scope — verifica que el producto pertenezca al centro del admin.
     * El superadmin puede ver todo.
     */
    private function authorizeCenter(Product $producto): void
    {
        $user = auth()->user();
        if ($user->isSuperAdmin()) return;

        if ($producto->center_id !== $user->center_id) {
            abort(403);
        }
    }
}