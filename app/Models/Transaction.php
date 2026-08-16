<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToWorkspace;

class Transaction extends Model
{
    use BelongsToWorkspace;

    protected $fillable = ['workspace_id', 'type', 'photo_url', 'notes', 'created_by'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
