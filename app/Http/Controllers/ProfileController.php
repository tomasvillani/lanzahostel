<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Si el usuario NO es cliente, eliminamos el teléfono para asegurarnos
        if ($request->user()->tipo !== 'c') {
            unset($data['telefono']);
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $messages = [
            'password.required' => 'La contraseña es obligatoria.',
            'password.current_password' => 'La contraseña actual es incorrecta.',
        ];

        $request->validate([
            'password' => ['required', 'current_password'],
        ], $messages);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'account-deleted');
    }

    public function uploadFiles(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'foto_perfil' => ['nullable', 'image', 'max:2048'], // max 2MB
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // max 5MB
            'remove_foto_perfil' => ['nullable', 'boolean'],
            'remove_cv' => ['nullable', 'boolean'],
        ], [
            'foto_perfil.image' => 'La foto debe ser una imagen válida.',
            'foto_perfil.max' => 'La foto no debe superar los 2MB.',
            'cv.mimes' => 'El CV debe estar en formato PDF, DOC o DOCX.',
            'cv.max' => 'El CV no debe superar los 5MB.',
        ]);

        if ($request->boolean('remove_foto_perfil')) {
            if ($user->foto_perfil && Storage::exists('public/' . $user->foto_perfil)) {
                Storage::delete('public/' . $user->foto_perfil);
            }
            $user->foto_perfil = null;
        }

        if ($request->boolean('remove_cv')) {
            if ($user->cv && Storage::exists('public/' . $user->cv)) {
                Storage::delete('public/' . $user->cv);
            }
            $user->cv = null;
        }

        if ($request->hasFile('foto_perfil')) {
            if ($user->foto_perfil && Storage::exists('public/' . $user->foto_perfil)) {
                Storage::delete('public/' . $user->foto_perfil);
            }
            $pathFoto = $request->file('foto_perfil')->store('profile_pictures', 'public');
            $user->foto_perfil = $pathFoto;
        }

        if ($request->hasFile('cv')) {
            if ($user->cv && Storage::exists('public/' . $user->cv)) {
                Storage::delete('public/' . $user->cv);
            }
            $pathCV = $request->file('cv')->store('cvs', 'public');
            $user->cv = $pathCV;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'files-updated');
    }

}
