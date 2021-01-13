<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::getAllAttributes();
        return view('backend.attribute.index')->with('attributes', $attributes);
    }
}
