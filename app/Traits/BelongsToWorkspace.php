<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToWorkspace
{
    protected static function bootBelongsToWorkspace()
    {
        static::addGlobalScope('workspace', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role !== 'superadmin') {
                $builder->where('workspace_id', auth()->user()->workspace_id);
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->role !== 'superadmin') {
                $model->workspace_id = auth()->user()->workspace_id;
            }
        });
    }
}
