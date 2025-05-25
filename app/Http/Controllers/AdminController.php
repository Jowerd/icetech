<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // ✅ სწორად მოვარგეთ ფაილს
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard'); // ✅ წარმატებული შესვლა
        }

        // ❌ არასწორი მონაცემების შეტყობინება
        return redirect()->route('admin.login')->with('error', 'ელფოსტა ან პაროლი არასწორია.');
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success', '✅ წარმატებით გამოხვედით სისტემიდან.');
    }
}
