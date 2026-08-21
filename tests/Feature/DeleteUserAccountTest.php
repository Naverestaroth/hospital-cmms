<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Technician;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUserAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_delete_other_user_account()
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $targetUser = User::factory()->create([
            'name' => 'Target User',
            'email' => 'target@hospital.com',
            'role' => 'user'
        ]);

        $response = $this->actingAs($developer)->delete("/settings/users/{$targetUser->id}");

        $response->assertRedirect(route('settings', ['tab' => 'user_role']));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_developer_deleting_teknisi_user_removes_linked_technician()
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $techUser = User::factory()->create([
            'name' => 'Teknisi Delete Test',
            'email' => 'techdelete@hospital.com',
            'role' => 'teknisi'
        ]);

        $this->assertDatabaseHas('technicians', ['user_id' => $techUser->id]);

        $response = $this->actingAs($developer)->delete("/settings/users/{$techUser->id}");

        $response->assertRedirect(route('settings', ['tab' => 'user_role']));
        $this->assertDatabaseMissing('users', ['id' => $techUser->id]);
        $this->assertDatabaseMissing('technicians', ['user_id' => $techUser->id]);
    }

    public function test_developer_cannot_delete_own_account()
    {
        $developer = User::factory()->create(['role' => 'developer']);

        $response = $this->actingAs($developer)->delete("/settings/users/{$developer->id}");

        $response->assertRedirect(route('settings', ['tab' => 'user_role']));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $developer->id]);
    }

    public function test_non_developer_cannot_delete_user_account()
    {
        $kepalaIpsrs = User::factory()->create(['role' => 'kepala_ipsrs']);
        $targetUser = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($kepalaIpsrs)->delete("/settings/users/{$targetUser->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $targetUser->id]);
    }
}
