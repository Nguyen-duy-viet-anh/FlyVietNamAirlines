<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Airport;

class AirportController extends Controller
{
    /**
     * Hiển thị danh sách sân bay
     */
    public function index()
    {
        $airports = Airport::all();
        return view('admin.airports.index', compact('airports'));
    }

    /**
     * Form chỉnh sửa thông tin sân bay
     */
    public function edit($id)
    {
        $airport = Airport::findOrFail($id);
        return view('admin.airports.edit', compact('airport'));
    }

    /**
     * Cập nhật thông tin sân bay (Ảnh & Mô tả)
     */
    public function update(Request $request, $id)
    {
        $airport = Airport::findOrFail($id);
        
        $request->validate([
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('upload_image')) {
            $image = $request->file('upload_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/airports'), $imageName);
            
            // Lưu đường dẫn ảnh vào DB
            $airport->image = '/uploads/airports/' . $imageName;
        }

        $airport->description = $request->description;
        $airport->save();

        return redirect()->route('admin.airports.index')->with('success', 'Cập nhật địa điểm thành công!');
    }
}
