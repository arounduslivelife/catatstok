<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToWorkspace;

class Product extends Model
{
    use BelongsToWorkspace;

    protected $fillable = ['workspace_id', 'code', 'name', 'unit'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getStockAttribute()
    {
        $in = $this->transactionItems()->whereHas('transaction', function($q) {
            $q->where('type', 'IN');
        })->sum('qty');
        
        $out = $this->transactionItems()->whereHas('transaction', function($q) {
            $q->where('type', 'OUT');
        })->sum('qty');
        
        return $in - $out;
    }
}
