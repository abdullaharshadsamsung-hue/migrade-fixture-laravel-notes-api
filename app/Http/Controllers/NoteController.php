<?php

namespace App\Http\Controllers;

use App\Models\NoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController
{
    public function __construct(private NoteRepository $notes)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->notes->all());
    }

    public function show(int $id): JsonResponse
    {
        $note = $this->notes->find($id);

        return $note ? response()->json($note) : response()->json(['error' => 'not found'], 404);
    }

    public function store(Request $request): JsonResponse
    {
        $title = $request->input('title');
        if (! is_string($title) || $title === '') {
            return response()->json(['error' => 'title is required'], 400);
        }

        return response()->json($this->notes->create($title, (string) $request->input('body', '')), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $fields = array_filter($request->only(['title', 'body']), fn ($v) => $v !== null);
        if (array_key_exists('title', $fields) && (! is_string($fields['title']) || $fields['title'] === '')) {
            return response()->json(['error' => 'title must not be empty'], 400);
        }
        $note = $this->notes->update($id, $fields);

        return $note ? response()->json($note) : response()->json(['error' => 'not found'], 404);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->notes->delete($id)
            ? response()->json(null, 204)
            : response()->json(['error' => 'not found'], 404);
    }
}
