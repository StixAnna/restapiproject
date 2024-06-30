<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sound extends Model
{
    use HasFactory;

    // add fileName, fileSize, fpath
    protected $fillable = ['fname', 'fsize', 'fpath', 'fduration', 'stag'];
}
