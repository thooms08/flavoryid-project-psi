<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_product_index_returns_products()
    {
        $demoUser = User::create([
            'username' => 'demo_partner',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $partner = \App\Models\Partner::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => $demoUser->id,
            'nama_manager' => 'Demo Partner',
            'no_wa' => '081234567890',
            'email' => 'demo@example.com',
        ]);

        Menu::create([
            'partner_id' => $partner->id,
            'nama_menu' => 'Test Product',
            'deskripsi' => 'Sample menu item',
            'kategori' => 'Makanan',
            'harga' => 15000,
            'tipe_menu' => 'makanan',
            'status_menu' => 'tersedia',
            'stok_menu' => 10,
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data',
            ]);
    }

    public function test_protected_product_store_requires_auth()
    {
        $user = User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $partner = \App\Models\Partner::create([
            'uuid' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'nama_manager' => 'Admin Partner',
            'no_wa' => '081234567890',
            'email' => 'partner@example.com',
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/products', [
                'partner_id' => $partner->id,
                'nama_menu' => 'New Product',
                'harga' => 25000,
                'stok_menu' => 5,
                'deskripsi' => 'Fresh product',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'nama_menu', 'harga'],
            ]);
    }
}
