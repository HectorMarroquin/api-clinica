<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserCollection;
use Illuminate\Support\Facades\Storage;

class StaffsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $search = $request->search;

        $users = User::query()
        ->where('name', 'like', "%{$search}%")
        ->orWhere('surname', 'like', "%{$search}%")
        ->orderByDesc('id')
        ->get(); // Obtener todos los resultados sin paginación

        return response()->json([
            "user" => UserCollection::make($users),
        ]);
    }
    public function config(){

        $roles = Role::all();

        return response()->json([
            "roles" => $roles
        ]);

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users_is_valid = User::where("email",$request->email)->first();

        if($users_is_valid){
            return response()->json([
                "message"      => 403,
                "message_text" => 'El usuario con este email, ya existe'
            ]);
        }
        
        if($request->hasFile("imagen")){
            $path = Storage::putFile("staffs",$request->file("imagen"));
            $request->request->add(["avatar" => $path]);
        }
        
        if($request->password){
            $request->request->add(['password' => bcrypt($request->password)]);
        }

        $user = User::create($request->all());

        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);

        return response()->json([
            "message"      => 200
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFile($id);

        return response()->json([
            "user" => $user
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users_is_valid = User::where("id","<>",$id)->where("email",$request->email)->first();

        if($users_is_valid){
            return response()->json([
                "message"      => 403,
                "message_text" => 'El usuario con este email, ya existe'
            ]);
        }
        $user = User::findOrFail($id);

        if($request->hasFile("imagen")){
            if($user->avatar){
                Storage::delete($user->avatar);
            }
            $path = Storage::putFile("staffs",$request->file("imagen"));
            $request->request->add(["avatar" => $path]);
        }

        if($request->password){
            $request->request->add(['password' => bcrypt($request->password)]);
        }

         $user->update($request->all());

        if($request->role_id != $user->roles()->first()->id){

            $role_old = Role::findOrFail($user->roles()->first()->id);
            $user->removeRole($role_old);

            $role_new = Role::findOrFail($request->role_id);
            $user->assignRole($role_new);
            
        }

        return response()->json([
            "message"      => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if($user->avatar){
            Storage::delete($user->avatar);
        }
        $user->delete();
        return response()->json([
            "message" => 200
        ]);
    }
}
