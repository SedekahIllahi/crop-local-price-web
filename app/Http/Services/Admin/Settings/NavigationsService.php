<?php

namespace App\Http\Services\Admin\Settings;

use App\Models\Navigation;

class NavigationsService
{
    /* Get data all navigations */
    public function getAllNavigations()
    {
        return Navigation::with('child')
                    ->where('parent_id', null)    
                    ->get();
    }

    /* Get data a navigation by id */
    public function getNavigationById($id)
    {
        return Navigation::findOrFail($id);
    }

    /* Store new navigation */
    public function store($data)
    {
        try {
            $navigation = Navigation::create($data);
            return redirect()->back()->with('success', 'Navigasi ' . $navigation->name . ' berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat navigasi. Error: ' . $e->getMessage());
        }
    }

    /* Update navigation */
    public function update($id, $data)
    {
        try {
            $navigation = Navigation::findOrFail($id);
            $navigation->update($data);
            return redirect()->back()->with('success', 'Navigasi ' . $navigation->name . ' berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui navigasi. Error: ' . $e->getMessage());
        }
    }

    /* Destroy navigation */
    public function destroy($id)
    {
        try {
            $navigation = Navigation::findOrFail($id);
            $navigation->delete();
            return redirect()->back()->with('success', 'Navigasi ' . $navigation->name . ' berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus navigasi. Error: ' . $e->getMessage());
        }
    }
}