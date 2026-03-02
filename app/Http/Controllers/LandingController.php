<?php

namespace App\Http\Controllers;

use App\Models\Program;

class LandingController extends Controller
{
    public function index()
    {
        // Show a few featured programs to guests
        $featuredPrograms = Program::where('is_published', true)
            ->where('access_level', 'free')
            ->latest()
            ->take(3)
            ->get();

        return view('landing.index', compact('featuredPrograms'));
    }
}
