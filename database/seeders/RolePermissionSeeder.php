<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'content_manager' => 'Content Manager',
            'seo_manager' => 'SEO Manager',
            'lead_manager' => 'Lead Manager',
            'dealer_manager' => 'Dealer Manager',
            'dealer' => 'Dealer',
        ];

        foreach ($roles as $slug => $name) {
            Role::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $permissions = [
            'tractors.manage' => 'Manage Tractors',
            'implements.manage' => 'Manage Implements',
            'content.manage' => 'Manage Blog/News/Video/FAQ/Reviews',
            'seo.manage' => 'Manage SEO Meta & Redirects',
            'leads.manage' => 'Manage Leads',
            'leads.view_own' => 'View Own Leads (Dealer)',
            'dealers.manage' => 'Manage Dealers',
            'used_listings.manage' => 'Approve/Reject Used Listings',
            'users.manage' => 'Manage Users & Roles',
            'settings.manage' => 'Manage Settings',
        ];

        foreach ($permissions as $slug => $name) {
            Permission::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $map = [
            'content_manager' => ['tractors.manage', 'implements.manage', 'content.manage'],
            'seo_manager' => ['seo.manage'],
            'lead_manager' => ['leads.manage'],
            'dealer_manager' => ['dealers.manage', 'used_listings.manage'],
            'dealer' => ['leads.view_own'],
        ];

        foreach ($map as $roleSlug => $permissionSlugs) {
            $role = Role::where('slug', $roleSlug)->first();
            $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id');
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }

        $superAdmin = Role::where('slug', 'super_admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@tractorduniya.in'],
            [
                'name' => 'TractorDuniya Admin',
                'role_id' => $superAdmin->id,
                'status' => 'active',
                'password' => 'ChangeMe@2026', // hashed via the User model cast; CHANGE IMMEDIATELY after first login
            ]
        );
    }
}
