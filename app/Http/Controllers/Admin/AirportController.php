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
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string',
            'landmarks' => 'nullable|array'
        ], [
            'upload_image.image' => 'File tải lên phải là hình ảnh.',
            'upload_image.mimes' => 'Chỉ chấp nhận ảnh định dạng: JPG, JPEG, PNG, WEBP.',
            'upload_image.max' => 'Ảnh không được vượt quá 5MB.',
        ]);

        if ($request->hasFile('upload_image')) {
            // Xóa ảnh cũ trong storage nếu tồn tại
            if ($airport->image && str_starts_with($airport->image, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $airport->image));
            }

            $path = $request->file('upload_image')->store('uploads/airports', 'public');
            
            // Lưu đường dẫn ảnh vào DB (Dùng /storage/ vì đã tạo symlink)
            $airport->image = '/storage/' . $path;
        }

        $airport->description = $request->description;
        
        // Xử lý upload ảnh cho từng địa danh (Landmarks)
        $processedLandmarks = [];
        if ($request->has('landmarks')) {
            foreach ($request->landmarks as $key => $landmarkData) {
                $imagePath = $landmarkData['image'] ?? '';

                // Kiểm tra xem có file ảnh mới được upload cho địa danh này không
                if ($request->hasFile("landmarks.{$key}.image_file")) {
                    $file = $request->file("landmarks.{$key}.image_file");
                    $path = $file->store('uploads/landmarks', 'public');
                    $imagePath = '/storage/' . $path;
                }

                $processedLandmarks[] = [
                    'name' => $landmarkData['name'] ?? '',
                    'image' => $imagePath,
                    'description' => $landmarkData['description'] ?? '',
                ];
            }
        }

        $airport->landmarks = $processedLandmarks;
        $airport->save();

        return redirect()->route('admin.airports.index')->with('success', 'Cập nhật địa điểm thành công!');
    }
}
