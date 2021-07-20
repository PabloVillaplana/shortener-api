<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlShortener extends Model
{
    use HasFactory;

    protected $table = 'url_short';

    protected $primaryKey = 'id';

    protected $fillable = ['url','short_url','visits','is_nsfw'];

}
