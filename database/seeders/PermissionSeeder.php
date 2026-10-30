<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Products
            ['name' => 'products.view',        'label' => 'View Products',         'module' => 'products'],
            ['name' => 'products.create',      'label' => 'Create Products',       'module' => 'products'],
            ['name' => 'products.edit',        'label' => 'Edit Products',         'module' => 'products'],
            ['name' => 'products.delete',      'label' => 'Delete Products',       'module' => 'products'],
            ['name' => 'products.bulk-delete', 'label' => 'Bulk Delete Products',  'module' => 'products'],
            ['name' => 'products.bulk-status', 'label' => 'Bulk Status Update',    'module' => 'products'],

            // Orders
            ['name' => 'orders.view',   'label' => 'View Orders',   'module' => 'orders'],
            ['name' => 'orders.edit',   'label' => 'Edit Orders',   'module' => 'orders'],
            ['name' => 'orders.delete', 'label' => 'Delete Orders', 'module' => 'orders'],

            // Users
            ['name' => 'users.view',              'label' => 'View Users',              'module' => 'users'],
            ['name' => 'users.create',            'label' => 'Create Users',            'module' => 'users'],
            ['name' => 'users.edit',              'label' => 'Edit Users',              'module' => 'users'],
            ['name' => 'users.delete',            'label' => 'Delete Users',            'module' => 'users'],
            ['name' => 'users.toggle-active',     'label' => 'Activate/Deactivate Users','module' => 'users'],
            ['name' => 'users.manage-permissions','label' => 'Manage User Permissions', 'module' => 'users'],

            // Settings
            ['name' => 'settings.view', 'label' => 'View Settings', 'module' => 'settings'],
            ['name' => 'settings.edit', 'label' => 'Edit Settings', 'module' => 'settings'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}