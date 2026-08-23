<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InternalController extends Controller
{
    /**
     * Verifikasi API key internal.
     * Key disimpan di .env sebagai INTERNAL_API_KEY.
     */
    private function verifyApiKey(Request $request): bool
    {
        $secret = env('INTERNAL_API_KEY');
        if (!$secret) return false;
        return $request->header('X-Internal-Key') === $secret;
    }

    /**
     * POST /api/v1/internal/register-manager
     *
     * Dipanggil oleh key.flavory.id setelah manager mendaftar.
     * Menyimpan user (role=kasir) + partner ke database flavory.id
     * agar ekosistem tetap 1 platform.
     *
     * Request body:
     *   username, password (plain), nama_manager, email, no_wa, jenis_bisnis
     */
    public function registerManager(Request $request)
    {
        if (!$this->verifyApiKey($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username',
            'password'     => 'required|string|min:8',
            'nama_manager' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'no_wa'        => 'required|string|max:25',
            'jenis_bisnis' => 'required|string|max:255',
        ]);

        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'kasir',
            ]);

            $partner = Partner::create([
                'user_id'      => $user->id,
                'uuid'         => (string) Str::uuid(),
                'nama_manager' => $request->nama_manager,
                'no_wa'        => $request->no_wa,
                'email'        => $request->email,
            ]);

            return response()->json([
                'success'      => true,
                'message'      => 'Akun partner berhasil dibuat di Flavory.id.',
                'partner_uuid' => $partner->uuid,
            ]);

        } catch (\Exception $e) {
            Log::error('Internal API registerManager error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    /**
     * POST /api/v1/internal/check-credentials
     *
     * Dipanggil oleh key.flavory.id setelah manager login,
     * untuk mengecek apakah credentials-nya masih sama
     * dengan yang tersimpan di database flavory.id.
     *
     * Request body: username, password (plain)
     * Response: { "same": true/false }
     */
    public function checkCredentials(Request $request)
    {
        if (!$this->verifyApiKey($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            // Akun ini tidak ada di flavory.id — tidak perlu paksa ganti password
            return response()->json(['same' => false]);
        }

        $same = Hash::check($request->password, $user->password);

        return response()->json(['same' => $same]);
    }
}
