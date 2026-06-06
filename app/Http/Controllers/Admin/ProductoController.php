<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Categorías base que siempre estarán disponibles
    const BASE_CATEGORIES = [
        'Vitaminas y minerales',
        'Ácidos grasos',
        'Proteínas y aminoácidos',
        'Probióticos',
        'Antioxidantes',
        'Adaptógenos',
        'Control de peso',
        'Energía y rendimiento',
        'Digestivo',
    ];

    /**
     * Devuelve la lista unificada: base + las que ya existen en BD (sin duplicados).
     */
    private function getCategories(): array
    {
        $fromDb = Product::whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();

        return collect(array_merge(self::BASE_CATEGORIES, $fromDb))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    public function index()
    {
        $productos = Product::orderBy('name')->paginate(15);
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.productos.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Si eligió "Otro", usar el campo personalizado
        if ($request->category === '__otro__') {
            $request->merge(['category' => trim($request->custom_category)]);
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'brand'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url|max:500',
            'active'      => 'boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        Product::create($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Product $producto)
    {
        $categories = $this->getCategories();
        return view('admin.productos.edit', compact('producto', 'categories'));
    }

    public function update(Request $request, Product $producto)
    {
        if ($request->category === '__otro__') {
            $request->merge(['category' => trim($request->custom_category)]);
        }

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'brand'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'image_url'   => 'nullable|url|max:500',
            'active'      => 'boolean',
        ]);

        $data['active'] = $request->boolean('active');

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $producto)
    {
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado.');
    }
}