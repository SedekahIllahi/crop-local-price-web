<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Users\CreateRequest;
use App\Http\Requests\Admin\Settings\Users\UpdateRequest;
use App\Http\Services\Admin\Settings\UsersService;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function __construct(protected UsersService $usersService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('settings-users.read');

        // Get data users for data table
        if (request()->ajax()) {
            return $this->usersService->getAllUsersForDataTable();
        }
        // Get data roles
        $roles = $this->usersService->getAllRoles();

        // Return view
        return view('admin.settings.user.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $this->setRule('settings-users.create');

        // Store process
        return $this->usersService->store($request->validated());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($userId = null)
    {
        $this->setRule('settings-users.update');

        return $this->usersService->getUserById($userId);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $userId)
    {
        $this->setRule('settings-users.update');

        // Update process
        return $this->usersService->update($userId, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {
        $this->setRule('settings-users.delete');

        // Delete Process
        return $this->usersService->delete($userId);
    }
}
