<?php

namespace App\Models\YearBookDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearbookShipment extends Model
{
    use HasFactory;

    protected $connection = 'yearbook';
    protected $table = 'yearbook_shipments';

    protected $fillable = [
        'yearbook_id',
        'supplier_name',
        'tracking_number',
        'quantity_sent',
        'quantity_received',
        'status',
        'released_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'released_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function yearbook()
    {
        return $this->belongsTo(Yearbooks::class, 'yearbook_id');
    }

    // Helper method to release shipment from supplier
    public function markAsReleased(): void
    {
        $this->update([
            'status' => 'released_by_supplier',
            'released_at' => now(),
        ]);
    }

    // Helper method to receive shipment at the office and update inventory
    public function markAsReceived(int $quantityReceived, ?string $notes = null): void
    {
        $this->update([
            'quantity_received' => $quantityReceived,
            'status' => 'received_by_office',
            'received_at' => now(),
            'notes' => $notes,
        ]);

        // Increment the main yearbook inventory total
        $this->yearbook()->increment('total_received', $quantityReceived);
    }
}
