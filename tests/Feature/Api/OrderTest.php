<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Menu;
use App\Models\User;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_order_with_authenticated_user()
    {
        $user = User::create([
            'username' => 'kasir',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $partner = \App\Models\Partner::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'nama_manager' => 'Kasir Partner',
            'no_wa' => '081234567890',
            'email' => 'partner@example.com',
        ]);

        $menu = Menu::create([
            'partner_id' => $partner->id,
            'nama_menu' => 'Test Item',
            'deskripsi' => 'Test description',
            'kategori' => 'Minuman',
            'harga' => 10000,
            'tipe_menu' => 'minuman',
            'status_menu' => 'tersedia',
            'stok_menu' => 20,
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/orders', [
                'nama_pelanggan' => 'Customer A',
                'no_whatsapp' => '08123456789',
                'items' => [
                    ['menu_id' => $menu->id, 'qty' => 2, 'harga' => 10000],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'nama_pelanggan', 'total_harga'],
            ]);
    }
}
