<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionDocument extends Model
{
    use HasFactory;

    protected $table ='construction_document';

    protected $fillable = [
        'document_number',
        'description', 
        'discipline', 
        'version',
        'author',
        'tanggal', 
        'category',
        'status',
        'path',
        'ext',
        'size',
        'checker',
        'reviewer',
        'approver',
        'uploader'
    ];

    public function r_discipline()
    {
        return $this->hasOne(MasterDiscipline::class, 'id', 'discipline');
    }
    public function r_category()
    {
        return $this->hasOne(MasterCategory::class, 'id', 'category');
    }
    public function r_status()
    {
        return $this->hasOne(MasterStatus::class, 'code', 'status');
    }
    public function r_history()
    {
        return $this->hasMany(ConstructionDocumentHistory::class, 'construction_document_id', 'id');
    }
}
