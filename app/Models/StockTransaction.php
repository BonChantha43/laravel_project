<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $guarded = []; // អនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យគ្រប់ Column

    // ១. ទំនាក់ទំនងទៅកាន់ Product (ទំនិញ)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ២. ទំនាក់ទំនងទៅកាន់ User (អ្នកធ្វើប្រតិបត្តិការ)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ៣. ទំនាក់ទំនងទៅកាន់ Supplier (អ្នកផ្គត់ផ្គង់ - បើមាន)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}