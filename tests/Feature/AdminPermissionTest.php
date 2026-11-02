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
