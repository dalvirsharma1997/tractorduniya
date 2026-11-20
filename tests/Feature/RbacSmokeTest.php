<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class RbacSmokeTest extends TestCase
{
    public function test_content_manager_cannot_access_leads_or_users(): void
    {
        $role = Role::where('slug', 'content_manager')->first();
        $user = User::firstOrCreate(
            ['email' => 'cm@test.local'],
            ['name' => 'Content Mgr', 'password' => 'password', 'role_id' => $role->id, 'status' => 'active']
        );
        $this->actingAs($user);

        $this->assertEquals(200, $this->get('/admin/tractors')->getStatusCode());
        $this->assertEquals(200, $this->get('/admin/blogs')->getStatusCode());
        $this->assertEquals(403, $this->get('/admin/leads')->getStatusCode());
        $this->assertEquals(403, $this->get('/admin/users')->getStatusCode());
        $this->assertEquals(403, $this->get('/admin/manage-settings')->getStatusCode());
    }

    public function test_lead_manager_can_access_leads_but_not_tractors_edit(): void
    {
        $role = Role::where('slug', 'lead_manager')->first();
        $user = User::firstOrCreate(
            ['email' => 'lm@test.local'],
            ['name' => 'Lead Mgr', 'password' => 'password', 'role_id' => $role->id, 'status' => 'active']
        );
        $this->actingAs($user);

        $this->assertEquals(200, $this->get('/admin/leads')->getStatusCode());
        $this->assertEquals(403, $this->get('/admin/tractors/create')->getStatusCode());
    }

    public function test_super_admin_can_access_everything(): void
    {
        $user = User::where('email', 'admin@tractorduniya.in')->first();
        $this->actingAs($user);

        $this->assertEquals(200, $this->get('/admin/leads')->getStatusCode());
        $this->assertEquals(200, $this->get('/admin/users')->getStatusCode());
        $this->assertEquals(200, $this->get('/admin/manage-settings')->getStatusCode());
    }
}
