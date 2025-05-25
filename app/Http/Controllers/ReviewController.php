<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|max:2048',
        ]);

        // შენახვა ფოტოს, თუ ატვირთულია
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('reviews', 'public');
        }

        // შეფასების შენახვა
        Review::create($validated);

        return redirect()->back()->with('success', 'თქვენი შეფასება მიღებულია და გამოქვეყნდება დამტკიცების შემდეგ.');
    }
}