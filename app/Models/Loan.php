<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'requested_at' => 'datetime',
        'expected_return_at' => 'datetime',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'loan_items')->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'borrowed' && $this->expected_return_at && now()->isAfter($this->expected_return_at);
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (empty($this->phone_number)) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->phone_number);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        $itemsList = $this->relationLoaded('items')
            ? $this->items->pluck('name')->implode(', ')
            : $this->items()->pluck('name')->implode(', ');

        $time = $this->expected_return_at ? $this->expected_return_at->format('H:i') : '-';

        $text = "Halo {$this->borrower_name}, kami dari loket TU INVENTA ingin mengonfirmasi terkait peminjaman barang ({$itemsList}) dengan kode {$this->loan_code}. Mengingat waktu pengembalian ({$time} WIB) telah terlewati, apakah pemakaian di ruang {$this->destination_room} sudah selesai? Ditunggu pengembaliannya di loket ya. Terima kasih.";

        return 'https://wa.me/' . $phone . '?text=' . urlencode($text);
    }
}
