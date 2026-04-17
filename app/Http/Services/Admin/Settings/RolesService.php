<?php

namespace App\Http\Services\Admin\Settings;

use App\Models\Navigation;
use Spatie\Permission\Models\Role;

class RolesService
{
    /* Get All Roles */
    public function getAllRoles()
    {
        return Role::where('name', '!=', 'developer')->orderBy('name')->get();
    }

    /* Get all permissions */
    public function getAllPermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        return $role->permissions->pluck('name')->toArray();
    }

    /* Get all navigations */
    public function getAllNavigations()
    {
        return Navigation::where('parent_id', null)->with('child')->get();
    }

    /* Get role by Id */
    public function getRoleById($roleId)
    {
        return Role::findOrFail($roleId);
    }

    /* Store */
    public function create(array $data)
    {
        try{
            Role::create($data);
            return redirect()->back()->with('success', 'Peran berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'Peran gagal ditambahkan. Error : ' . $e->getMessage());
        }
    }

    /* Update */
    public function update($roleId, array $data)
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->update($data);
            return redirect()->back()->with('success', 'Peran berhasil diperbarui');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'Peran gagal diperbarui. Error : ' . $e->getMessage());
        }
    }

    /* Delete */
    public function delete($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);

            // Disabled delete when role == developer
            if ($role->name === 'developer') {
                return redirect()->back()->with('error', 'Peran developer tidak dapat dihapus');
            }

            $role->delete();
            return redirect()->back()->with('success', 'Peran berhasil dihapus');
        } catch (\Exception $e) {
            // Handle the exception
            return redirect()->back()->with('error', 'Peran gagal dihapus. Error : ' . $e->getMessage());
        }
    }

    /* Give permissions */
    public function givePermission($roleId, array $request)
    {
        try {
            // Create permissions if not exist
            foreach ($request->permissions ?? [] as $key => $permission) {
                $permission = Permission::firstOrCreate(['name' => $permission]);
            }

            // Assign role permissions
            $role = Role::findOrFail($roleId);
            $role->syncPermissions($request['permissions']);

            // Return Success
            return redirect()->back()->with('success', 'Hak akses berhasil diberikan');
        } catch (\Exception $e) {
            // Return Error
            return redirect()->back()->with('error', 'Gagal memberikan hak akses. Error: ' . $e->getMessage());
        }
    }
}