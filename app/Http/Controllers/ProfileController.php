<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . auth()->id(),
            'organization' => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required'  => 'Kolom nama wajib diisi.',
            'email.required' => 'Kolom email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan, coba gunakan email lain.',
            'avatar.image'   => 'File harus berupa gambar (JPG atau PNG).',
            'avatar.mimes'   => 'File harus berupa gambar (JPG atau PNG).',
            'avatar.max'     => 'Ukuran file tidak boleh lebih dari 2 MB.',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars/' . $user->id, 'public');
        } else {
            unset($validated['avatar']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password'      => 'required|current_password',
            'password'              => 'required|min:8|confirmed|different:current_password',
            'password_confirmation' => 'required',
        ], [
            'current_password.required'       => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required'               => 'Kata sandi baru wajib diisi.',
            'password.min'                    => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'              => 'Konfirmasi kata sandi tidak sesuai.',
            'password.different'              => 'Kata sandi baru harus berbeda dari kata sandi saat ini.',
            'password_confirmation.required'  => 'Konfirmasi kata sandi wajib diisi.',
        ]);

        $request->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }
}
