<?php

// app/Http/ViewComposers/NavigationComposer.php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NavigationComposer
{
    public function compose(View $view)
    {
        $navs = Navigation::with(['child' => function ($query) {
            $query->where('active', 1)
                ->where('display', true)
                ->orderBy('order', 'asc');
        }, 'child.subChild' => function ($query) {
            $query->where('active', 1)
                ->where('display', true)
                ->orderBy('order', 'asc');
        }])
            ->whereNull('parent_id')
            ->where('page', 'admin')
            ->where('active', true)
            ->where('display', true)
            ->orderBy('order')
            ->get()
            ->map(function ($nav) {
                $nav->url = $this->resolveUrl($nav->url);

                $nav->child->each(function ($child) {
                    // Set URL from route name to URL for child
                    $child->url = $this->resolveUrl($child->url);

                    // Set URL from route name to URL for subChild
                    $child->subChild->each(function ($subChild) {
                        $subChild->url = $this->resolveUrl($subChild->url);
                    });
                });

                return $nav;
            });
        $dashNavs = $navs->where('slug', 'dashboard')->first()->toArray();
        
        $filteredNavs = $this->filterPermission($navs->toArray());
        
        // Hapus dashboard dari filteredNavs jika ada (untuk menghindari duplikat)
        $filteredNavs = array_filter($filteredNavs, function($nav) {
            return $nav['slug'] !== 'dashboard';
        });
        $filteredNavs = array_values($filteredNavs); // Re-index array
        
        // Tambahkan dashboard di awal array
        array_unshift($filteredNavs, $dashNavs);

        $view->with('navs',  $filteredNavs);
    }

    private function filterPermission($navs)
    {
        if (empty($navs)) {
            return [];
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $filteredNavs = [];
        foreach ($navs as $key => $nav) {
            // Check if user has permission for this navigation item
            if ($user?->can($nav['slug'] . '.read')) {
                // If there are child items, recursively filter them
                if (!empty($nav['child'])) {
                    $nav['child'] = $this->filterPermission($nav['child']);
                }
                // If there are subChild items, recursively filter them
                if (!empty($nav['sub_child'])) {
                    $nav['sub_child'] = $this->filterPermission($nav['sub_child']);
                }
                $filteredNavs[] = $nav; // Add to the result array
            }
        }

        return $filteredNavs; // Return the filtered array
    }

    /**
     * Resolve URL - supports both route names and direct URL paths
     * 
     * @param string $urlOrRouteName
     * @return string
     */
    private function resolveUrl($urlOrRouteName)
    {
        // If it's a hash, return as-is
        if ($urlOrRouteName === '#' || empty($urlOrRouteName)) {
            return '#';
        }

        // If it contains a slash, treat as direct URL path
        if (str_contains($urlOrRouteName, '/')) {
            return url($urlOrRouteName);
        }

        // Otherwise, try to resolve as route name
        try {
            return route($urlOrRouteName);
        } catch (\Exception $e) {
            // If route not found, treat as direct path
            return url($urlOrRouteName);
        }
    }
}
