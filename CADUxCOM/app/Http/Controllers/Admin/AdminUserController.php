<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtro por estado de verificación de email
        if ($request->filled('email_verified')) {
            if ($request->email_verified === 'verified') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Filtro por fecha de registro
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->withCount(['comentarios'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        // Estadísticas para el dashboard
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'users_with_companies' => 0, // Valor temporal - la relación empresas no existe en el modelo User
            'users_with_reviews' => User::has('comentarios')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        Log::info('Usuario creado por administrador', [
            'admin_id' => auth()->id(),
            'created_user_id' => $user->id,
            'created_user_email' => $user->email,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['empresas', 'comentarios.empresa']);
        
        $stats = [
            'total_companies' => $user->empresas->count(),
            'total_reviews' => $user->comentarios->count(),
            'avg_rating' => $user->comentarios->avg('calificacion'),
            'last_login' => $user->last_login_at ?? 'Nunca',
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->has('email_verified')) {
            $data['email_verified_at'] = $request->email_verified ? now() : null;
        }

        $user->update($data);

        Log::info('Usuario actualizado por administrador', [
            'admin_id' => auth()->id(),
            'updated_user_id' => $user->id,
            'updated_user_email' => $user->email,
            'changes' => $request->only(['name', 'email', 'role']),
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevenir que el admin se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $userEmail = $user->email;
        $user->delete();

        Log::warning('Usuario eliminado por administrador', [
            'admin_id' => auth()->id(),
            'deleted_user_email' => $userEmail,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Toggle user status (activate/deactivate).
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activado' : 'desactivado';

        Log::info('Estado de usuario cambiado por administrador', [
            'admin_id' => auth()->id(),
            'user_id' => $user->id,
            'new_status' => $user->is_active,
        ]);

        return redirect()->back()
                        ->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Bulk delete users.
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        
        // Prevenir que el admin se elimine a sí mismo
        if (in_array(auth()->id(), $userIds)) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $deletedUsers = User::whereIn('id', $userIds)->get();
        User::whereIn('id', $userIds)->delete();

        Log::warning('Eliminación masiva de usuarios por administrador', [
            'admin_id' => auth()->id(),
            'deleted_count' => count($userIds),
            'deleted_users' => $deletedUsers->pluck('email')->toArray(),
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', count($userIds) . ' usuarios eliminados exitosamente.');
    }
}