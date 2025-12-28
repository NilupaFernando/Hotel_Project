<?php

namespace App\Http\Controllers\imageTest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Test\TestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TestImage;

use Inertia\Inertia;
use function Pest\PendingCalls\pr;
use function Termwind\render;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Auth;


class testImageControlle extends Controller
{
    public function create()
    {
        try {
           $testImage = TestImage::all();
            return Inertia::render('Admin/imageTest/Create',[
                'testImage' => $testImage,
            ]);
        } catch (\Exception $exception) {
            Log::info('Hotel created successfully', [$exception]);
            return Inertia::render('Admin/Hotel/Index');
        }
    }


    public function store(TestRequest $request)
    {
        try {
            $validatedData = $request->validated();

            TestImage::create($validatedData);

            return back()->with('success', 'Record saved successfully!');
        } catch (\Exception $exception) {
            Log::error('Error in storing record', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);

            return back()->with('error', 'Something went wrong!');
        }
    }


    public function index()
    {
        try {
            $testImages = TestImage::paginate(10); // Corrected pagination

            return Inertia::render('Admin/imageTest/Index', [
                'testImages' => $testImages,
            ]);
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id)
    {
//        dd($id);
        try {
            $testImages = TestImage::findOrFail($id);
            $testImages->images = json_decode($testImages->images, true);

            return Inertia::render('Admin/imageTest/edit',[
                'testImages' => $testImages
            ]);
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong!');
        }
    }


    public function update(TestRequest $request, $id)
    {
        try {
            // Find the record by ID
            $test = TestImage::findOrFail($id);

            // Update fields
            $test->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

        } catch (\Exception $exception) {
            Log::error('Error updating image', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
}
