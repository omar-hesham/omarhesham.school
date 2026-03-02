<?php

namespace App\Http\Controllers;

class PolicyController extends Controller
{
    public function privacy()    { return view('policies.privacy'); }
    public function terms()      { return view('policies.terms'); }
    public function childSafety(){ return view('policies.child-safety'); }
    public function cookies()    { return view('policies.cookies'); }
}
