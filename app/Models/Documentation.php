<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Documentation extends Model
{
    protected $table = 'documentation';

    protected $fillable = [
        'path',
        'title',
        'description',
        'content_raw',
        'content_html',
        'sha',
        'order',
    ];

    /**
     * Search documentation by query.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        $search = '%' . $search . '%';
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', $search)
              ->orWhere('content_raw', 'like', $search);
        });
    }

    /**
     * Get the edit URL for this documentation page.
     */
    public function getEditUrlAttribute(): string
    {
        return "https://github.com/laravilt/laravilt/edit/master/docs/{$this->path}.md";
    }
}
