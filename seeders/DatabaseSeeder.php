<?php
// ============================================================
// app/Http/Middleware/CheckRole.php
// ============================================================
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Sin acceso.');
        }
        return $next($request);
    }
}

// Registrar en bootstrap/app.php (Laravel 12):
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias(['role' => \App\Http\Middleware\CheckRole::class]);
// })


// ============================================================
// database/seeders/DatabaseSeeder.php
// ============================================================
// namespace Database\Seeders;
// use App\Models\User;
// use App\Models\HealthProfile;
// use App\Models\Product;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Hash;
//
// class DatabaseSeeder extends Seeder
// {
//     public function run(): void
//     {
//         // Admin principal (tú)
//         User::create([
//             'name'     => 'Administrador',
//             'email'    => 'admin@vitasolutions.mx',
//             'password' => Hash::make('vita2024'),
//             'role'     => 'admin',
//             'active'   => true,
//         ]);
//
//         // Cliente de prueba
//         $cliente = User::create([
//             'name'     => 'María García',
//             'email'    => 'maria@ejemplo.com',
//             'password' => Hash::make('password'),
//             'role'     => 'client',
//             'active'   => true,
//         ]);
//
//         HealthProfile::create([
//             'user_id'        => $cliente->id,
//             'blood_type'     => 'O+',
//             'sex'            => 'female',
//             'birth_date'     => '1990-05-15',
//             'height_cm'      => 162,
//             'weight_kg'      => 68.5,
//             'main_goal'      => 'weight_loss',
//             'activity_level' => 'light',
//         ]);
//
//         // Productos de ejemplo
//         $productos = [
//             ['name' => 'Omega 3 Premium',    'brand' => 'VitaLab', 'category' => 'omega'],
//             ['name' => 'Vitamina D3 2000UI', 'brand' => 'VitaLab', 'category' => 'vitamin'],
//             ['name' => 'Colágeno Hidrolizado','brand' => 'VitaLab', 'category' => 'collagen'],
//             ['name' => 'Probiótico 10B',     'brand' => 'VitaLab', 'category' => 'probiotic'],
//             ['name' => 'Proteína Vegetal',   'brand' => 'VitaLab', 'category' => 'protein'],
//         ];
//
//         foreach ($productos as $p) {
//             Product::create(array_merge($p, ['active' => true]));
//         }
//     }
// }
