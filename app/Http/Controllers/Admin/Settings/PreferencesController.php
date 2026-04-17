<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Models\Preference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Settings\PreferencesService;
use App\Http\Requests\Admin\Settings\Preferences\UpdateRequest;

class PreferencesController extends Controller
{
    public function __construct(protected PreferencesService $preferencesService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('settings-preferences.read');

        // Get all preferences
        $preferences = $this->preferencesService->getAllPreferences();
        return view('admin.settings.preferences.index', compact('preferences'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($preferenceId = null)
    {
        $this->setRule('settings-preferences.update');
        return $this->preferencesService->getPreferenceById($preferenceId);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $preferenceId = null)
    {
        $this->setRule('settings-preferences.update');
        // Update process
        return $this->preferencesService->update($preferenceId, $request->validated());
    }
}
