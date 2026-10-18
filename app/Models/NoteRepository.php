<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

/**
 * Notes kept in one JSON file, so the app needs no database.
 */
class NoteRepository
{
    private const FILE = 'notes.json';

    private function load(): array
    {
        if (! Storage::exists(self::FILE)) {
            return ['next_id' => 1, 'notes' => []];
        }

        return json_decode(Storage::get(self::FILE), true);
    }

    private function save(array $data): void
    {
        Storage::put(self::FILE, json_encode($data));
    }

    public function all(): array
    {
        return array_values($this->load()['notes']);
    }

    public function find(int $id): ?array
    {
        return $this->load()['notes'][$id] ?? null;
    }

    public function create(string $title, string $body): array
    {
        $data = $this->load();
        $note = ['id' => $data['next_id'], 'title' => $title, 'body' => $body];
        $data['notes'][$note['id']] = $note;
        $data['next_id']++;
        $this->save($data);

        return $note;
    }

    public function update(int $id, array $fields): ?array
    {
        $data = $this->load();
        if (! isset($data['notes'][$id])) {
            return null;
        }
        $data['notes'][$id] = array_merge($data['notes'][$id], $fields);
        $this->save($data);

        return $data['notes'][$id];
    }

    public function delete(int $id): bool
    {
        $data = $this->load();
        if (! isset($data['notes'][$id])) {
            return false;
        }
        unset($data['notes'][$id]);
        $this->save($data);

        return true;
    }
}
