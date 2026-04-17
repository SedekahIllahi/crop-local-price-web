<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Dashboard
 */

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});


/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Profile
 */
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profil Saya', route('profile.edit'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Settings
 */

Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Pengaturan', "javascript:void(0)");
});

Breadcrumbs::for('navigations', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Menu', route('settings.navs.index'));
});

Breadcrumbs::for('navigation-edit', function (BreadcrumbTrail $trail, $nav, $title = null) {
    $trail->parent('navigations');
    $trail->push("Edit Menu $title", route('settings.navs.edit', $nav));
});

Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Pengguna', route('settings.users.index'));
});

Breadcrumbs::for('users-create', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push('Tambah Pengguna', route('settings.users.create'));
});

Breadcrumbs::for('users-edit', function (BreadcrumbTrail $trail, $user, $name = null) {
    $trail->parent('users');
    $trail->push("Edit Pengguna $name", route('settings.users.edit', $user));
});

Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Peran', route('settings.roles.index'));
});

Breadcrumbs::for('roles-permissions', function (BreadcrumbTrail $trail, $roleId, $name) {
    $trail->parent('roles');
    $trail->push("Hak Akses Peran $name", route('settings.roles.show', $roleId));
});

Breadcrumbs::for('preferences', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Preferensi', route('settings.preferences.index'));
});