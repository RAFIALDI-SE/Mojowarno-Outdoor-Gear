<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Content;
use App\Models\TermsCondition;

class HomeController extends Controller
{
    public function index()
    {
        // $user = Auth::user();

        $products = Product::with('images')->latest()->get();
        $contents = Content::all();
        $terms = TermsCondition::latest()->get();

        return view('member.home', compact(


            'products',
            'contents',
            'terms'
        ));
    }
}
