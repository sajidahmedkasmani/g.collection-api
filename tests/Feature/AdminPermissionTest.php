<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_only_open_assigned_sections(): void
    {
        $user = $this->createAdminUser();
        $this->givePermission($user, 'products.view');

        $this->actingAs($user)
            ->get('/admin/products')
            ->assertOk();

        $this->get('/admin/orders')->assertForbidden();
        $this->get('/admin/products/create')->assertForbidden();
    }

    public function test_dashboard_only_shows_data_for_assigned_permissions(): void
    {
        $user = $this->createAdminUser();
        $this->givePermission($user, 'products.view');

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Products')
            ->assertDontSee('Recent orders')
            ->assertDontSee('Orders');
    }

    public function test_admin_without_permissions_cannot_open_dashboard(): void
    {
        $user = $this->createAdminUser();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_user_with_user_edit_permission_can_create_and_deactivate_permissions(): void
    {
        $user = $this->createAdminUser();
        $this->givePermission($user, 'users.edit');

        $this->actingAs($user)
            ->post('/admin/permissions', [
                'name' => 'reports.view',
                'label' => 'View Reports',
                'module' => 'Reports',
            ])
            ->assertRedirect('/admin/permissions');

        $permission = Permission::where('name', 'reports.view')->firstOrFail();
        $this->assertTrue($permission->is_active);
        $this->assertTrue($user->can('reports.view'));

        $this->post("/admin/permissions/{$permission->id}/toggle-active")
            ->assertRedirect();

        $this->assertFalse($permission->fresh()->is_active);
        $this->assertFalse($user->fresh()->can('reports.view'));
    }

    public function test_deactivated_permission_no_longer_grants_access(): void
    {
        $user = $this->createAdminUser();
        $this->givePermission($user, 'products.view');
        $this->givePermission($user, 'users.edit');
        $permission = Permission::where('name', 'products.view')->firstOrFail();

        $this->actingAs($user)
            ->post("/admin/permissions/{$permission->id}/toggle-active")
            ->assertRedirect();

        $this->get('/admin/products')->assertForbidden();
    }

    private function createAdminUser(): User
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'super@example.test',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::create([
            'name' => 'Limited Admin',
            'email' => 'limited@example.test',
            'password' => 'password',
        ]);
        $user->is_admin = true;
        $user->save();

        return $user;
    }

    private function givePermission(User $user, string $name): void
    {
        $permission = Permission::create([
            'name' => $name,
            'label' => $name,
            'module' => explode('.', $name, 2)[0],
        ]);
        $user->permissions()->attach($permission);
    }
}
