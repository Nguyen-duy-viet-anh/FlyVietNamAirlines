<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách tất cả sân bay để hiển thị ở Select box
        $airports = Airport::all();
        return view('home', compact('airports'));
    }
}