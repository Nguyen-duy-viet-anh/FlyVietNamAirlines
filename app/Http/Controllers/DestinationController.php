<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;

class DestinationController extends Controller
{
    public function index()
    {
        // Chỉ lấy những thành phố có ảnh đại diện để hiển thị cho đẹp
        $destinations = Airport::whereNotNull('image')->get();
        
        return view('destinations.index', compact('destinations'));
    }
    public function show($id)
    {
        // Chỉ cần findOrFail là đủ, landmarks giờ là 1 cột trong bảng airports
        $destination = Airport::findOrFail($id);
        
        return view('destinations.show', compact('destination'));
    }
}