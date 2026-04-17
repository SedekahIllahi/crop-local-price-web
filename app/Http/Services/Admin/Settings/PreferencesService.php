<?php

namespace App\Http\Services\Admin\Settings;

use App\Models\Preference;

class PreferencesService
{
    /* Get all preferences */
    public function getAllPreferences()
    {
        return Preference::all();
    }

    /* Get preference by id */
    public function getPreferenceById($preferenceId)
    {
        return Preference::findOrFail($preferenceId);
    }

    /* Update preference data */
    public function update($preferenceId, array $data)
    {
        try {
            $preference = Preference::findOrFail($preferenceId);

            if($data['is_asset'] == "1" && isset($data['file_asset'])) {
                // Handle file upload
                $file = $data['file_asset'];
                $filename = $data['file_name'] ?? $file->getClientOriginalName();
                $destinationPath = public_path($data['path']);
                $file->move($destinationPath, $filename);
            } else {
                $preference->update($data);
            }

            return redirect()->back()->with('success', 'Preferensi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui preferensi. Error: ' . $e->getMessage());
        }
    }

}