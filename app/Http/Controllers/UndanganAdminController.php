<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Undangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UndanganAdminController extends Controller
{
    // Tambahkan method edit
    public function edit()
    {
        $undangan = Undangan::first();
        return view('undangan.admin-edit', compact('undangan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_title' => 'required',
            'foto_pria' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_prmp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_akad' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_resepsi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $undangan = Undangan::first();
        
        // Update data non-file
        $undangan->fill($request->except(['foto_pria', 'foto_prmp', 'foto_akad', 'foto_resepsi']));

        // Handle foto pria
        if ($request->hasFile('foto_pria')) {
            if ($undangan->foto_pria) {
                Storage::disk('public')->delete($undangan->foto_pria);
            }
            $file = $request->file('foto_pria');
            $filename = 'pria_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $undangan->foto_pria = $path;
        }

        // Lakukan hal yang sama untuk foto lainnya
        if ($request->hasFile('foto_prmp')) {
            if ($undangan->foto_prmp) {
                Storage::disk('public')->delete($undangan->foto_prmp);
            }
            $file = $request->file('foto_prmp');
            $filename = 'prmp_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $undangan->foto_prmp = $path;
        }

        if ($request->hasFile('foto_akad')) {
            if ($undangan->foto_akad) {
                Storage::disk('public')->delete($undangan->foto_akad);
            }
            $file = $request->file('foto_akad');
            $filename = 'akad_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $undangan->foto_akad = $path;
        }

        if ($request->hasFile('foto_resepsi')) {
            if ($undangan->foto_resepsi) {
                Storage::disk('public')->delete($undangan->foto_resepsi);
            }
            $file = $request->file('foto_resepsi');
            $filename = 'resepsi_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $undangan->foto_resepsi = $path;
        }

        $undangan->save();

        return redirect()->route('undangan.edit')->with('success', 'Data berhasil diperbarui');
    }
    private function handlePhotoUpload(Request $request, $undangan, $field)
    {
        if ($request->hasFile($field)) {
            // Delete old file if exists
            if ($undangan->$field) {
                Storage::disk('public')->delete($undangan->$field);
            }
            
            $file = $request->file($field);
            $filename = substr($field, 5) . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('photos', $filename, 'public');
            $undangan->$field = $path;
        }
    }
}