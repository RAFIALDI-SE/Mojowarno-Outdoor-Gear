<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::latest()->get();
        return view('admin.contents.index', compact('contents'));
    }

    public function create()
    {
        return view('admin.contents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'image'        => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'redirect_url' => 'required|url'
        ]);

        $imagePath = $request->file('image')->store('contents', 'public');

        Content::create([
            'title'        => $request->title,
            'image'        => $imagePath,
            'redirect_url' => $request->redirect_url,
        ]);

        return redirect()->route('contents.index')
            ->with('success', 'Konten berhasil ditambahkan');
    }

    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return view('admin.contents.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'redirect_url' => 'required|url'
        ]);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            $content->image = $request->file('image')->store('contents', 'public');
        }

        $content->update([
            'title'        => $request->title,
            'redirect_url' => $request->redirect_url,
            'image'        => $content->image
        ]);

        return redirect()->route('contents.index')
            ->with('success', 'Konten berhasil diupdate');
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);

        if (Storage::disk('public')->exists($content->image)) {
            Storage::disk('public')->delete($content->image);
        }

        $content->delete();

        return redirect()->route('contents.index')
            ->with('success', 'Konten berhasil dihapus');
    }
}
