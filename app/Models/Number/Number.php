<?php

namespace App\Models\Number;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    public $table = 'numbers_numbers';

    public $timestamps = true;

    public $fillable = [
        'number',
        'comment_count',
        'view_count',
        'view_last_ip'
    ];

    public static function getNumbersByCode($code) {
        $array = [];

        for ($i = 1; $i <= 99999; $i++) {
            $number = '370' . $code . str_pad($i, 5, '0', STR_PAD_LEFT);

            $array[]['number'] = $number;
        }

        return collect($array);
    }

    /*
     * Relationships
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->orderBy('created_at', 'DESC');
    }
}
