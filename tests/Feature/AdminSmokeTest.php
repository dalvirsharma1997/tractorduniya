<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    public function test_admin_pages_load(): void
    {
        $user = User::where('email', 'admin@tractorduniya.in')->first();
        $this->actingAs($user);

        $tractor = \App\Models\Tractor::first();
        $implement = \App\Models\Implement::first();

        foreach ([
            '/admin',
            '/admin/brands', '/admin/brands/create',
            '/admin/categories', '/admin/categories/create',
            '/admin/states', '/admin/states/create',
            '/admin/tractors', '/admin/tractors/create', "/admin/tractors/{$tractor->id}/edit",
            '/admin/implements', '/admin/implements/create', "/admin/implements/{$implement->id}/edit",
        ] as $url) {
            $response = $this->get($url);
            $status = $response->getStatusCode();
            echo "$url -> $status\n";
            if ($status === 500) {
                echo $response->getContent();
            }
            $this->assertNotEquals(500, $status, "Failed: $url");
        }
    }
}
