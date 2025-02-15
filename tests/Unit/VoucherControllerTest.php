<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\VoucherCode;
use App\Services\VoucherCodeService;
use App\Http\Controllers\VoucherController;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\VoucherCodeResource;

class VoucherControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $voucherCodeService;
    private $controller;
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->voucherCodeService = Mockery::mock(VoucherCodeService::class);
        $this->controller = new VoucherController($this->voucherCodeService);
        $this->user = User::create([
            'username' => 'burttest',
            'first_name' => 'Burt User',
            'email' => 'burt@emailme.com',
            'password' => bcrypt('admin123'),
        ]);

        $this->actingAs($this->user, 'sanctum');

        $this->app->instance(VoucherCodeService::class, $this->voucherCodeService);
    }

    public function testIndexReturnsVouchers()
    {
        $testDataVoucher = VoucherCode::hydrate([
            ['id' => 1, 'code' => 'abcd1', 'user_id' => $this->user->id, 'created_at' => now()],
            ['id' => 2, 'code' => '1dcba', 'user_id' => $this->user->id, 'created_at' => now()],
        ]); # to create an Eloquent collection like in the controller

        $this->voucherCodeService
            ->shouldReceive('getUserVouchers')
            ->with($this->user)
            ->andReturn($testDataVoucher); # check if eloquent and it should hydrated eloquent collection

        $expectedResponse = VoucherCodeResource::collection($testDataVoucher)->response()->getData(true);

        $this->actingAs($this->user)
            ->getJson('/api/vouchers')
            ->assertStatus(200)
            ->assertJson($expectedResponse);
    }

    public function testDestroyDeletesVoucher()
    {
        $voucher = VoucherCode::create([
            'code' => 'AbcE1',
            'user_id' => $this->user->id,
        ]);

        $this->voucherCodeService
            ->shouldReceive('deleteUserVoucher')
            ->once()
            ->andReturn(true);

        $this->actingAs($this->user)
            ->deleteJson("/api/voucher/delete/{$voucher->id}")
            ->assertStatus(204);
    }

    public function testGenerateVoucher()
    {
        $mockVoucher = new VoucherCode(['code' => 'bacd1']);

        $this->voucherCodeService
            ->shouldReceive('generateUniqueCode')
            ->with($this->user)
            ->once()
            ->andReturn($mockVoucher);

        $this->actingAs($this->user)
            ->postJson('/api/vouchers/generate')
            ->assertStatus(200)
            ->assertJson(['code' => 'bacd1']);
    }

    public function testGenerateFailsWhenLimitExceeded()
    {
        $this->voucherCodeService
            ->shouldReceive('generateUniqueCode')
            ->with($this->user)
            ->once()
            ->andReturn(null);

        $this->actingAs($this->user)
            ->postJson('/api/vouchers/generate')
            ->assertStatus(422)
            ->assertJson(['message' => 'Limit reached.']);
    }
}
