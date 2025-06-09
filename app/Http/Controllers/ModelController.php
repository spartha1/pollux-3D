<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ThreeDModel;
use Illuminate\Support\Facades\Storage;
use App\Services\StlMetadataService;
use Illuminate\Support\Facades\Log;

class ModelController extends Controller
{
    protected $metadataService;

    public function __construct(StlMetadataService $metadataService)
    {
        $this->metadataService = $metadataService;
    }

    public function index()
    {
        $models = ThreeDModel::paginate(10);
        return Inertia::render('Models/Index', ['models' => $models]);
    }

    public function show($id)
    {
        $model = ThreeDModel::findOrFail($id);
        $this->authorize('view', $model);

        return Inertia::render('Models/Viewer', [
            'model' => $model,
            'can' => [
                'edit' => auth()->user()->can('update', $model),
                'delete' => auth()->user()->can('delete', $model),
            ]
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', ThreeDModel::class);

            $request->validate([
                'file' => 'required|file|mimes:stl|max:10240',
                'description' => 'nullable|string|max:500'
            ]);

            $file = $request->file('file');
            $path = $file->store('models', 'public');

            try {
                $metadata = $this->metadataService->extract($file);
            } catch (\Exception $e) {
                Log::error('Metadata extraction failed: ' . $e->getMessage());
                $metadata = null;
            }

            $model = ThreeDModel::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'metadata' => $metadata,
                'user_id' => auth()->id(),
                'description' => $request->description
            ]);

            return redirect()->route('models.show', $model->id)
                           ->with('success', 'Model uploaded successfully');

        } catch (\Exception $e) {
            Log::error('Model upload failed: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Failed to upload model. Please try again.');
        }
    }

    public function getMetadata($id)
    {
        $model = ThreeDModel::findOrFail($id);
        return response()->json($model->metadata);
    }
}
