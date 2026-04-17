<?php

namespace App\Http\Services\Admin\Settings;

use App\Models\User;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Container\Attributes\DB;
use Yajra\DataTables\Facades\DataTables;

class UsersService
{
    /* Get all users */
    public function getAllUsersForDataTable()
    {
        $users = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', RoleEnum::DEVELOPER->value);
            })
            ->orderBy('name');
        
        return Datatables::eloquent($users)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y H:i');
            })
            ->addColumn('role', function ($row) {
                return $row->getRoleNames()->isNotEmpty() ? $row->getRoleNames()->implode(', ') : '-';
            })
            ->addColumn('status', function ($row) {
                if ($row->is_active == 1) {
                    return '<span class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">Aktif</span>';
                }
                return '<span class="px-[8px] py-[3px] inline-block bg-orange-100 dark:bg-[#15203c] text-orange-600 rounded-sm font-medium text-xs">Tidak Aktif</span>';
            })
            ->addColumn('aksi', function ($row) {
                $wrapperStart = '<div class="flex items-center gap-[9px] justify-center">';
                $btnEdit = '';
                $btnDelete = '';
                // Btn Edit
                if (auth()->user()->can('settings-users.update')) {
                    $btnEdit = '<button type="button" title="Edit data pengguna" id="btn-modal-edit-user"
                        data-id="' . $row->id . '"  data-url-action="' . route('settings.users.update', $row->id) . '" data-url-get="' . route('settings.users.edit', $row->id) . '"
                        class="btn-modal-edit-user text-warning-500 dark:text-warning-400 leading-none custom-tooltip">
                            <i class="material-symbols-outlined !text-md">
                                edit
                            </i>
                        </button>';
                }

                // Btn Delete
                if (auth()->user()->can('settings-users.delete')) {
                    $btnDelete = '<button type="button" title="Hapus data pengguna" id="btn-delete"
                        data-id="' . $row->id . '"  data-url-action="' . route('settings.users.destroy', $row->id) . '"
                        class="text-danger-500 leading-none custom-tooltip">
                            <i class="material-symbols-outlined !text-md">
                                delete
                            </i>
                        </button>';
                }

                $wrapperBottom = '</div>';

                return $wrapperStart . $btnEdit . ' ' . $btnDelete . $wrapperBottom;
            })
            ->escapeColumns([])
            ->make(true);
    }

    /* Get all roles (except developer) */
    public function getAllRoles()
    {
        return Role::where('name', '!=', 'developer')->get();
    }

    /* Get user by ID */
    public function getUserById(int $id)
    {
        $user = User::findOrFail($id);
        // If you want to include role names, you can add them as an attribute
        $user->role_names = $user->roles->pluck('name')->toArray();
        return $user;
    }

    /* Store new user data */
    public function store(array $data)
    {
        try {
            // DB Transaction
            \DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'is_active' => 1,
            ]);
            
            // Assign roles
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            // Return success response
            \DB::commit();
            return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            // Return error response
            \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Pengguna gagal ditambahkan. Error :' . $e->getMessage()]);
        }
    }

    /* Update user data */
    public function update($userId, array $data)
    {
        try {
            // DB Transaction
            \DB::beginTransaction();

            // Get data user
            $user = User::findOrFail($userId);
            // Update user data
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'username' => $data['username'] ?? $user->username,
                'email' => $data['email'] ?? $user->email,
                'status' => isset($data['status']) ? (int) $data['status'] : $user->status,
            ]);

            // Assign roles
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            // Return success response
            \DB::commit();
            return redirect()->back()->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            // Return error response
            \DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Pengguna gagal diperbarui. Error :' . $e->getMessage()]);
        }
    }

    /* Delete user data */
    public function delete($userId)
    {
        try {
            // DB Transaction
            \DB::beginTransaction();

            // Get data user
            $user = User::findOrFail($userId);
            $user->delete();

            // Return success response
            \DB::commit();
            return redirect()->route('settings.users.index')->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            // Return error response
            \DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Pengguna gagal dihapus. Error :' . $e->getMessage()]);
        }
    }
}