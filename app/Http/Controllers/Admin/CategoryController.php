<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function categoryList(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories);
    }


    /**
     * Create a new category.
     *
     * @param Request $request
     * @return Response
     */

    public function createCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category = Category::create([
            'name' => $request->name,
            'user_id' => $request->header('user_id'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    public function categoryById($id): JsonResponse
    {
        try{
            $category = Category::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'category' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
            ], 404);
        }
    }


    public function updateCategory(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:25',
        ]);
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'user_id' => $request->header('user_id'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    public function deleteCategory($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
        ]);
    }
}
