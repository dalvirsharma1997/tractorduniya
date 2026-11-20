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
        $dealer = \App\Models\Dealer::first();
        $lead = \App\Models\Lead::create([
            'name' => 'Test Lead', 'phone' => '9000000000', 'status' => 'new',
        ]);
        $usedListing = \App\Models\UsedListing::create([
            'listing_type' => 'tractor', 'tractor_id' => $tractor->id,
            'seller_name' => 'Test Seller', 'seller_phone' => '9111111111',
            'city' => 'Pune', 'condition' => 'good', 'asking_price' => 300000, 'status' => 'pending',
        ]);
        $rule = \App\Models\LeadAssignmentRule::first();

        foreach ([
            '/admin',
            '/admin/brands', '/admin/brands/create',
            '/admin/categories', '/admin/categories/create',
            '/admin/states', '/admin/states/create',
            '/admin/tractors', '/admin/tractors/create', "/admin/tractors/{$tractor->id}/edit",
            '/admin/implements', '/admin/implements/create', "/admin/implements/{$implement->id}/edit",
            '/admin/dealers', '/admin/dealers/create', "/admin/dealers/{$dealer->id}/edit",
            '/admin/used-listings', '/admin/used-listings/create', "/admin/used-listings/{$usedListing->id}/edit",
            '/admin/leads', '/admin/leads/create', "/admin/leads/{$lead->id}/edit",
            '/admin/lead-assignment-rules', '/admin/lead-assignment-rules/create', "/admin/lead-assignment-rules/{$rule->id}/edit",
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
