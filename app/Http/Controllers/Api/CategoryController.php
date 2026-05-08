<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data kategori', [
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:category,name',
            ]);

            $category = Category::create($validated);

            Log::info('Menambah data kategori', [
                'list' => $category
            ]);

            return response()->json([
                'message' => 'Kategori berhasil ditambahkan!!',
                'data' => $category,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Error saat menambah kategori', [
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambah kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $category = Category::with('products')->find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'message' => 'Category retrieved successfully',
                'data' => $category
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Gagal mengambil data kategori', [
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data kategori',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:category,name,' . $id,
            ]);

            $category->update($validated);

            Log::info('Mengupdate data kategori', [
                'list' => $category
            ]);

            return response()->json([
                'message' => 'Kategori berhasil diupdate!!',
                'data' => $category,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat mengupdate kategori', [
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengupdate kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan',
                ], 404);
            }

            // Check if category has products
            if ($category->products()->count() > 0) {
                return response()->json([
                    'message' => 'Kategori tidak dapat dihapus karena masih memiliki produk',
                ], 400);
            }

            $category->delete();

            Log::info('Menghapus data kategori', [
                'id' => $id
            ]);

            return response()->json([
                'message' => 'Kategori berhasil dihapus!!',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error saat menghapus kategori', [
                'message' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus kategori',
            ], 500);
        }
    }
}
