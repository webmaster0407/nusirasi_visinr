<?php

namespace App\Models\Number;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = 'numbers_comments';

    public $timestamps = true;

    public $fillable = [
        'author',
        'date',
        'content',
        'ip',
        'user_agent',
        'created_at',
        'updated_at'
    ];

    /*
     * Relationships
     */
    public function number()
    {
        return $this->belongsTo(Number::class);
    }
}
