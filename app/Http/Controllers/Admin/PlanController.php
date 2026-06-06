<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount(['clientPlans', 'activeClients'])
            ->with('products')
            ->latest()
            ->paginate(15);

        return view('admin.planes.index', compact('plans'));
    }

    public function create()
    {
        $products = Product::where('active', true)->orderBy('name')->get();
        return view('admin.planes.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:120',
            'description' => 'nullable|string',
            'type'        => 'required|in:monthly,custom,visit',
            'price'       => 'nullable|numeric|min:0',
            'active'      => 'boolean',
            // productos
            'products'              => 'nullable|array',
            'products.*.id'         => 'required|exists:products,id',
            'products.*.dose'       => 'nullable|string|max:80',
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.schedule'   => 'required|in:breakfast,lunch,dinner,bedtime,other',
            'products.*.time'       => 'nullable|date_format:H:i',
            'products.*.place'      => 'required|in:on_site,takeaway,sealed_package',
            'products.*.notes'      => 'nullable|string|max:200',
        ]);

        $plan = Plan::create([
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => $request->type,
            'price'       => $request->type === 'custom' ? 0 : ($request->price ?? 0),
            'active'      => $request->boolean('active', true),
        ]);

        // Sincronizar productos
        if ($request->filled('products')) {
            $sync = [];
            foreach ($request->products as $item) {
                $sync[$item['id']] = [
                    'dose'              => $item['dose'] ?? null,
                    'quantity'          => $item['quantity'],
                    'schedule'          => $item['schedule'],
                    'time'              => $item['time'] ?? null,
                    'consumption_place' => $item['place'],
                    'notes'             => $item['notes'] ?? null,
                ];
            }
            $plan->products()->sync($sync);
        }

        // Si es custom, recalcular precio
        $plan->load('products');
        $plan->recalculateIfCustom();

        return redirect()->route('admin.planes.index')
            ->with('success', "Plan \"{$plan->name}\" creado correctamente.");
    }

    public function show(Plan $plan)
    {
        $plan->load('products', 'activeClients.user');
        return view('admin.planes.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        $plan->load('products');
        $products = Product::where('active', true)->orderBy('name')->get();
        return view('admin.planes.edit', compact('plan', 'products'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name'        => 'required|string|max:120',
            'description' => 'nullable|string',
            'type'        => 'required|in:monthly,custom,visit',
            'price'       => 'nullable|numeric|min:0',
            'active'      => 'boolean',
            'products'              => 'nullable|array',
            'products.*.id'         => 'required|exists:products,id',
            'products.*.dose'       => 'nullable|string|max:80',
            'products.*.quantity'   => 'required|integer|min:1',
            'products.*.schedule'   => 'required|in:breakfast,lunch,dinner,bedtime,other',
            'products.*.time'       => 'nullable|date_format:H:i',
            'products.*.place'      => 'required|in:on_site,takeaway,sealed_package',
            'products.*.notes'      => 'nullable|string|max:200',
        ]);

        $plan->update([
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => $request->type,
            'price'       => $request->type === 'custom' ? 0 : ($request->price ?? 0),
            'active'      => $request->boolean('active', true),
        ]);

        $sync = [];
        foreach (($request->products ?? []) as $item) {
            $sync[$item['id']] = [
                'dose'              => $item['dose'] ?? null,
                'quantity'          => $item['quantity'],
                'schedule'          => $item['schedule'],
                'time'              => $item['time'] ?? null,
                'consumption_place' => $item['place'],
                'notes'             => $item['notes'] ?? null,
            ];
        }
        $plan->products()->sync($sync);

        $plan->load('products');
        $plan->recalculateIfCustom();

        return redirect()->route('admin.planes.index')
            ->with('success', "Plan \"{$plan->name}\" actualizado.");
    }

    public function destroy(Plan $plan)
    {
        $active = $plan->activeClients()->count();
        if ($active > 0) {
            return back()->with('error', "No se puede eliminar: hay {$active} cliente(s) activo(s) en este plan.");
        }

        $plan->delete();
        return redirect()->route('admin.planes.index')
            ->with('success', 'Plan eliminado.');
    }
}