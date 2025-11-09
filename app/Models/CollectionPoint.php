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
        // 1) Coba ambil koordinat dari map_url
        if ($coords = $this->extractCoords($this->map_url)) {
            [$lat, $lng] = $coords;
            return "https://www.google.com/maps?q={$lat},{$lng}&z=16&output=embed";
        }

        // 2) Kalau tidak ada koordinat, coba dari address (biasanya lebih bisa di-geocode)
        if ($this->address) {
            return 'https://www.google.com/maps?q=' . urlencode($this->address) . '&z=16&output=embed';
        }

        // 3) Fallback terakhir: pakai map_url apa adanya (kalau sudah /embed atau output=embed)
        if ($this->map_url) {
            if (str_contains($this->map_url, 'output=embed') || str_contains($this->map_url, '/embed')) {
                return $this->map_url;
            }
            // upaya terakhir: jadikan query
            return 'https://www.google.com/maps?q=' . urlencode($this->map_url) . '&output=embed';
        }

        // Tidak ada apapun
        return null;
    }
}

