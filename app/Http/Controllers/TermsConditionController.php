<?php

namespace App\Http\Controllers;

use App\Models\TermsCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TermsConditionController extends Controller
{
    public function index()
    {
        $terms = TermsCondition::latest()->get();
        return view('admin.terms.index', compact('terms'));
    }

    public function create()
    {
        return view('admin.terms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = $request->file('image')->store('terms', 'public');

        TermsCondition::create([
            'image' => $imagePath
        ]);

        return redirect()->route('terms.index')
            ->with('success', 'Syarat & ketentuan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $term = TermsCondition::findOrFail($id);
        return view('admin.terms.edit', compact('term'));
    }

    public function update(Request $request, $id)
    {
        $term = TermsCondition::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($term->image)) {
                Storage::disk('public')->delete($term->image);
            }

            $term->image = $request->file('image')->store('terms', 'public');
        }

        $term->save();

        return redirect()->route('terms.index')
            ->with('success', 'Syarat & ketentuan berhasil diupdate');
    }

    public function destroy($id)
    {
        $term = TermsCondition::findOrFail($id);

        if (Storage::disk('public')->exists($term->image)) {
            Storage::disk('public')->delete($term->image);
        }

        $term->delete();

        return redirect()->route('terms.index')
            ->with('success', 'Syarat & ketentuan berhasil dihapus');
    }
}
