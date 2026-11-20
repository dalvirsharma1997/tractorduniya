<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class DealerLeadScopeTest extends TestCase
{
    public function test_dealer_sees_only_own_leads(): void
    {
        $dealerRole = Role::where('slug', 'dealer')->first();
        $dealer = Dealer::first();
        $otherDealer = Dealer::skip(1)->first();

        $dealerUser = User::firstOrCreate(
            ['email' => 'dealeruser@test.local'],
            ['name' => 'Dealer User', 'password' => 'password', 'role_id' => $dealerRole->id, 'dealer_id' => $dealer->id, 'status' => 'active']
        );

        $myLead = Lead::create(['name' => 'Mine', 'phone' => '9000000001', 'status' => 'new', 'assigned_dealer_id' => $dealer->id]);
        $otherLead = Lead::create(['name' => 'Other', 'phone' => '9000000002', 'status' => 'new', 'assigned_dealer_id' => $otherDealer->id]);

        $this->actingAs($dealerUser);

        $response = $this->get('/admin/leads');
        $response->assertSee('Mine');
        $response->assertDontSee('Other');

        $this->assertEquals(200, $this->get("/admin/leads/{$myLead->id}/edit")->getStatusCode());
        // Scoped out of the query entirely, so Filament 404s rather than 403s.
        $this->assertEquals(404, $this->get("/admin/leads/{$otherLead->id}/edit")->getStatusCode());
    }
}
