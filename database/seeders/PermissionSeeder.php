<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Products
            ['name' => 'products.view',        'label' => 'View Products',          'module' => 'Products'],
            ['name' => 'products.create',      'label' => 'Create & Import Products','module' => 'Products'],
            ['name' => 'products.edit',        'label' => 'Edit Products & Stock',  'module' => 'Products'],
            ['name' => 'products.delete',      'label' => 'Delete Products',        'module' => 'Products'],
            ['name' => 'products.bulk-delete', 'label' => 'Bulk Delete Products',   'module' => 'Products'],
            ['name' => 'products.bulk-status', 'label' => 'Bulk Status Update',     'module' => 'Products'],

            // Orders
            ['name' => 'orders.view',          'label' => 'View Orders',            'module' => 'Orders'],
            ['name' => 'orders.edit',          'label' => 'Update Order Status',    'module' => 'Orders'],
            ['name' => 'orders.delete',        'label' => 'Delete Orders',          'module' => 'Orders'],

            // Sliders
            ['name' => 'sliders.view',         'label' => 'View Sliders',           'module' => 'Sliders'],
            ['name' => 'sliders.create',       'label' => 'Create Sliders',         'module' => 'Sliders'],
            ['name' => 'sliders.edit',         'label' => 'Edit & Reorder Sliders', 'module' => 'Sliders'],
            ['name' => 'sliders.delete',       'label' => 'Delete Sliders',         'module' => 'Sliders'],

            // CMS / Website Content
            ['name' => 'cms.view',             'label' => 'View CMS Content',       'module' => 'CMS Content'],
            ['name' => 'cms.edit',             'label' => 'Update CMS Sections',    'module' => 'CMS Content'],

            // FAQs
            ['name' => 'faqs.view',            'label' => 'View FAQs',              'module' => 'FAQs'],
            ['name' => 'faqs.create',          'label' => 'Create FAQs',            'module' => 'FAQs'],
            ['name' => 'faqs.edit',            'label' => 'Edit & Toggle FAQs',     'module' => 'FAQs'],
            ['name' => 'faqs.delete',          'label' => 'Delete FAQs',            'module' => 'FAQs'],

            // Settings & Bank Details
            ['name' => 'settings.view',        'label' => 'View Settings',          'module' => 'Settings'],
            ['name' => 'settings.edit',        'label' => 'Update Settings & Bank', 'module' => 'Settings'],

            // Users
            ['name' => 'users.view',           'label' => 'View Admin Users',       'module' => 'Users'],
            ['name' => 'users.create',         'label' => 'Create Admin Users',     'module' => 'Users'],
            ['name' => 'users.edit',           'label' => 'Edit Users & Permissions','module' => 'Users'],
            ['name' => 'users.delete',         'label' => 'Delete Users',           'module' => 'Users'],
            ['name' => 'users.toggle-active',  'label' => 'Toggle Active Status',   'module' => 'Users'],

            // Queue Monitor
            ['name' => 'queue.view',           'label' => 'View Queue Monitor',     'module' => 'Queue Monitor'],
            ['name' => 'queue.retry',          'label' => 'Retry Failed Jobs',      'module' => 'Queue Monitor'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['name' => $p['name']], $p);
        }

        Cache::forget('permission_names');
    }
}