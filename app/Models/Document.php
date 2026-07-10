<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [

        'document_code',

        'title',

        'asset_id',

        'document_type',

        'revision',

        'issue_date',

        'expiry_date',

        'file_path',

        'description'

    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
