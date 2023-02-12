<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;
    public function test_setting_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/setting');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function setting_update에_PATCH_메서드로_접근_시_정상접근_확인()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/setting', [
                'mqv_id' => '',
                'mqv_password' => '',
            ]);
        $response->assertSessionHasErrors(['mqv_id', 'mqv_password']);

    }

    /**
     * @test
     */
    public function setting_update에_PATCH_메서드_유효값_확인()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/setting', [
                'mqv_id' => 123,
                'mqv_password' => 123,
            ]);

        $response->assertSessionHasErrors(['mqv_id', 'mqv_password']);
    }
}
