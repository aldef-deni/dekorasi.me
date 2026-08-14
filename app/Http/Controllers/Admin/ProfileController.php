<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly ImageService $images)
    {
    }

    public function edit(): View
    {
        return view('admin.profile.edit', ['user' => Auth::user()]);
    }

    /** Perbarui nama, email, dan foto profil. */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'   => ['required', 'string', 'max:100'],
            'email'  => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [], [
            'name'   => 'nama',
            'avatar' => 'foto profil',
        ]);

        unset($data['avatar']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->images->store($request->file('avatar'), 'avatars', $user->avatar);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /** Hapus foto profil, kembali memakai inisial nama. */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->avatar) {
            return back();
        }

        $this->images->delete($user->avatar);
        $user->update(['avatar' => null]);

        return back()->with('success', 'Foto profil dihapus.');
    }

    /** Ganti kata sandi; sandi lama wajib dimasukkan sebagai pengaman. */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak sama.',
        ], [
            'current_password' => 'kata sandi saat ini',
            'password'         => 'kata sandi baru',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->string('password')),
        ]);

        // Sesi lain (perangkat lain) ikut dikeluarkan demi keamanan.
        Auth::logoutOtherDevices($request->string('password'));

        return back()->with('success', 'Kata sandi berhasil diganti.');
    }
}
