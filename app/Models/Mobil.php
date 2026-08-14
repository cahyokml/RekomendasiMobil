<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $table    = 'mobil';
    protected $fillable = ['merek', 'nama', 'harga', 'cc', 'bahanbakar', 'kursi', 'foto'];

    /**
     * URL foto dari kolom database — bukan generate random.
     * Pastikan setiap record punya nilai 'foto'.
     */
    public function getFotoUrlAttribute()
    {
        return $this->foto
            ? $this->foto
            : 'https://placehold.co/400x300?text=Mobil';
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getBahanbakarLabelAttribute(): string
    {
        return ucfirst($this->bahanbakar ?? '-');
    }
}
