<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    protected $fillable = ['name', 'address', 'contact', 'map_url'];

    // --- Helper: ekstrak koordinat dari berbagai pola URL/teks
    private function extractCoords(?string $s): ?array
    {
        if (!$s) return null;

        // Pola 1: "@-8.65012,115.2213"
        if (preg_match('/@\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/', $s, $m)) {
            return [(float)$m[1], (float)$m[2]];
        }

        // Pola 2: "!3d-8.65012!4d115.2213"
        if (preg_match('/!3d\s*(-?\d+\.\d+)\s*!4d\s*(-?\d+\.\d+)/', $s, $m)) {
            return [(float)$m[1], (float)$m[2]];
        }

        // Pola 3: "q=-8.65012,115.2213" di query string
        if (preg_match('/[?&]q=\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/', $s, $m)) {
            return [(float)$m[1], (float)$m[2]];
        }

        // Pola 4: string murni "lat,lng"
        if (preg_match('/^\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)\s*$/', $s, $m)) {
            return [(float)$m[1], (float)$m[2]];
        }

        return null;
    }

// --- Accessor: $point->map_embed_url untuk <iframe>
    public function getMapEmbedUrlAttribute(): ?string
    {
        // 1) Jika ada koordinat eksplisit di map_url, itu paling akurat
        if ($coords = $this->extractCoords($this->map_url)) {
            [$lat, $lng] = $coords;
            return "https://www.google.com/maps?q={$lat},{$lng}&z=16&output=embed";
        }

        // 2) UTAMAKAN NAMA LOKASI untuk geocoding (sesuai permintaan)
        if ($name = trim((string)$this->name)) {
            return 'https://www.google.com/maps?q=' . urlencode($name) . '&z=16&output=embed';
        }

        // 3) Kalau ada map_url tapi bukan embed, jadikan query
        if ($this->map_url) {
            if (str_contains($this->map_url, 'output=embed') || str_contains($this->map_url, '/embed')) {
                return $this->map_url;
            }
            return 'https://www.google.com/maps?q=' . urlencode($this->map_url) . '&z=16&output=embed';
        }

        // 4) Fallback terakhir: alamat teks
        if ($this->address) {
            return 'https://www.google.com/maps?q=' . urlencode($this->address) . '&z=16&output=embed';
        }

        // Tidak ada apapun
        return null;
    }

    // --- Accessor: $point->map_open_url untuk tombol "Buka di Google Maps"
    public function getMapOpenUrlAttribute(): ?string
    {
        if ($coords = $this->extractCoords($this->map_url)) {
            [$lat, $lng] = $coords;
            return "https://www.google.com/maps/search/?api=1&query={$lat},{$lng}";
        }

        if ($name = trim((string)$this->name)) {
            return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($name);
        }

        if ($this->map_url) {
            return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->map_url);
        }

        if ($this->address) {
            return 'https://www.google.com/maps/search/?api=1&query=' . urlencode($this->address);
        }

        return null;
    }
}

