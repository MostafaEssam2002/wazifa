<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    // ... (بقية الأساليب كما هي)

    // Store a newly created comment in storage
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png', // Allowed file types
            'post_id' => 'required|exists:posts,id' // Ensure post_id is provided and exists in the posts table
        ]);

        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/comments', $fileName);
        }

        Comment::create([
            'content' => $request->input('content'),
            'file' => $fileName,
            'post_id' => $request->input('post_id'), // Include the post_id
        ]);

        return redirect()->route('comments.index')->with('success', 'Comment created successfully.');
    }


    // Update the specified comment in storage
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png', // قم بتحديد أنواع الملفات المسموح بها
        ]);

        $comment = Comment::findOrFail($id);
        $fileName = $comment->file;

        if ($request->hasFile('file')) {
            if ($fileName) {
                Storage::delete('public/comments/' . $fileName);
            }

            $file = $request->file('file');
            $fileName = now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/comments', $fileName);
        }

        $comment->update([
            'content' => $request->input('content'),
            'file' => $fileName,
        ]);

        return redirect()->route('comments.index')->with('success', 'Comment updated successfully.');
    }

    // Remove the specified comment from storage
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->file) {
            Storage::delete('public/comments/' . $comment->file);
        }
        $comment->delete();

        return redirect()->route('comments.index')->with('success', 'Comment deleted successfully.');
    }
}
