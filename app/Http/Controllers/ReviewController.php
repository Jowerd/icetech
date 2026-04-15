<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'   => 'nullable|exists:products,id',
            'author_name'  => 'required|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'content'      => 'required|string',
            'rating'       => 'required|integer|min:1|max:5',
        ]);

        // შეფასების შენახვა
        Review::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'თქვენი შეფასება მიღებულია და გამოქვეყნდება დამტკიცების შემდეგ.');
    }
}