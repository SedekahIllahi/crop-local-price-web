<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationSeeder extends Seeder
{
    /* 
    ! ========================================================================================================
    * EXAMPLE DATA FOR NAVIGATION MENUS
    ! ========================================================================================================
    */

    // // Parent Menu
    // [
    //     'id' => 501,
    //     'name' => 'Pengaturan',
    //     'page' => 'admin',
    //     'url' => '#',
    //     'slug' => 'settings',
    //     'icon' => 'settings',
    //     'order' => 501,
    //     'parent_id' => null,
    //     'active' => true,
    //     'display' => true,
    // ],
    // // Child Menus
    // [
    //     'id' => 502,
    //     'name' => 'Pengaturan Lanjutan',
    //     'page' => 'admin',
    //     'url' => '#',
    //     'slug' => 'settings-advanced',
    //     'icon' => 'settings-advanced',
    //     'order' => 1,
    //     'parent_id' => 501,
    //     'active' => true,
    //     'display' => true,
    // ],
    // // Sub Child Menus
    // [
    //     'id' => 503,
    //     'name' => 'Pengaturan Lanjutan - Profil Website',
    //     'page' => 'admin',
    //     'url' => 'settings.advanced.profile.index',
    //     'slug' => 'settings-advanced-profile',
    //     'icon' => 'settings-advanced-profile',
    //     'order' => 1,
    //     'parent_id' => 502,
    //     'active' => true,
    //     'display' => true,
    // ],

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* 
         * ========================================================================================================
         * FOR ICONS HERE USE MATERIAL DESIGN ICONS: https://fonts.google.com/icons
         * ========================================================================================================
         */
        $navigationsAdmin = [
            /* ---------------------------------------------------------
            *                      ADMIN PAGE
            ---------------------------------------------------------*/
            [
                'id' => 1,
                'name' => 'Dashboard',
                'page' => 'admin',
                'url' => 'dashboard',
                'slug' => 'dashboard',
                'icon' => 'dashboard',
                'order' => 1,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],

            /* 
            * ========================================================================================================
            * Pasar Menu and its sub-menus
            * Simplified: Clicking pasar name goes directly to Harga Bapok page
            * ========================================================================================================
            */
            [
                'id' => 100,
                'name' => 'Pasar',
                'page' => 'admin',
                'url' => '#',
                'slug' => 'pasar',
                'icon' => 'storefront',
                'order' => 100,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 101,
                'name' => 'Pasar Bantul',
                'page' => 'admin',
                'url' => 'pasar/bantul/harga-bapok',
                'slug' => 'pasar-bantul',
                'icon' => '',
                'order' => 1,
                'parent_id' => 100,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 102,
                'name' => 'Pasar Niten',
                'page' => 'admin',
                'url' => 'pasar/niten/harga-bapok',
                'slug' => 'pasar-niten',
                'icon' => '',
                'order' => 2,
                'parent_id' => 100,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 103,
                'name' => 'Pasar Imogiri',
                'page' => 'admin',
                'url' => 'pasar/imogiri/harga-bapok',
                'slug' => 'pasar-imogiri',
                'icon' => '',
                'order' => 3,
                'parent_id' => 100,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 104,
                'name' => 'Pasar Piyungan',
                'page' => 'admin',
                'url' => 'pasar/piyungan/harga-bapok',
                'slug' => 'pasar-piyungan',
                'icon' => '',
                'order' => 4,
                'parent_id' => 100,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 105,
                'name' => 'Pasar Pundong',
                'page' => 'admin',
                'url' => 'pasar/pundong/harga-bapok',
                'slug' => 'pasar-pundong',
                'icon' => '',
                'order' => 5,
                'parent_id' => 100,
                'active' => true,
                'display' => true,
            ],


            /* 
            * ========================================================================================================
            * Settings Menu and its sub-menus
            * ========================================================================================================
            */

            [
                'id' => 500,
                'name' => 'Profil Saya',
                'page' => 'admin',
                'url' => 'profile.edit',
                'slug' => 'profile',
                'icon' => 'person_book',
                'order' => 500,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 501,
                'name' => 'Pengaturan',
                'page' => 'admin',
                'url' => '#',
                'slug' => 'settings',
                'icon' => 'settings',
                'order' => 501,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 502,
                'name' => 'Pengguna',
                'page' => 'admin',
                'url' => 'settings.users.index',
                'slug' => 'settings-users',
                'icon' => '', // Assuming no icon specified
                'order' => 501,
                'parent_id' => 501, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 503,
                'name' => 'Peran',
                'page' => 'admin',
                'url' => 'settings.roles.index',
                'slug' => 'settings-roles',
                'icon' => '', // Assuming no icon specified
                'order' => 502,
                'parent_id' => 501, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 504,
                'name' => 'Menu',
                'page' => 'admin',
                'url' => 'settings.navs.index',
                'slug' => 'settings-navs',
                'icon' => '', // Assuming no icon specified
                'order' => 503,
                'parent_id' => 501, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            [
                'id' => 505,
                'name' => 'Preferensi',
                'page' => 'admin',
                'url' => 'settings.preferences.index',
                'slug' => 'settings-preferences',
                'icon' => '', // Assuming no icon specified
                'order' => 504,
                'parent_id' => 501, // Nested under Settings
                'active' => true,
                'display' => true,
            ],
            
        ];

        $navigationLanding = [
            /* ---------------------------------------------------------
            *                      LANDING PAGE
            ---------------------------------------------------------*/
            /* Dashboard */
            [
                'id' => 600,
                'name' => 'Beranda',
                'page' => 'landing',
                'url' => 'beranda.index',
                'slug' => 'beranda',
                'icon' => 'home',
                'order' => 600,
                'parent_id' => null,
                'active' => true,
                'display' => true,
            ],
        ];

        // Insert data into the 'navigations' table
        DB::table('navigations')->insert($navigationsAdmin);
        DB::table('navigations')->insert($navigationLanding);
    }
}
