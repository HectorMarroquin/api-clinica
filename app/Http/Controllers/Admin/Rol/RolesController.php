<?php

namespace App\Http\Controllers\Admin\Rol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtrar por nombre de rol
        $name = $request->search;

        // Obtener roles ordenados por id de forma descendente
        $roles = Role::where('name', 'like', "%$name%")
                    ->orderByDesc('id')
                    ->get();

        // Transformar la respuesta JSON
        $formattedRoles = $roles->map(function ($role) {
            return [
                'id'              => $role->id,
                'name'            => $role->name,
                'permision'       => $role->permissions,
                'permision_pluck' => $role->permissions->pluck("name"),
                'created_at'      => $role->created_at->format('Y-m-d h:i:s'),
            ];
        });

        return response()->json(['roles' => $formattedRoles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Verificar si el rol ya existe
        $existingRole = Role::where("name", $request->name)->first();
    
        if ($existingRole) {
            return response()->json([
                'message'      => 403,
                'message_text' => 'El nombre del rol ya existe.',
            ]);
        }
    
        // Crear el nuevo rol
        $role = Role::create([
            'guard_name' => 'api',
            'name'       => $request->name,
        ]);
    
        // Asignar permisos al nuevo rol
        if ($request->has('permisions') && is_array($request->permisions)) {
            $role->givePermissionTo($request->permisions);
        }
    
        return response()->json([
            'message'      => 200,
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $existingRole = Role::where("id","<>",$id)->where("name", $request->name)->first();

        if ($existingRole) {
            return response()->json([
                'message'      => 403,
                'message_text' => 'El nombre del rol ya existe.',
            ]);
        }
    
        $role = Role::findOrFail($id);
        $role->update($request->all());

        $role->syncPermissions($request->permisions);
        
        return response()->json([
            'message'      => 200,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json([
            'message'      => 200,
        ]);
    }
}
