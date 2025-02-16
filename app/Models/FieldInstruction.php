<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldInstruction extends Model
{
    use HasFactory;
    protected $table ='field_instruction';

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
        return $this->hasMany(FieldInstructionHistory::class, 'field_instruction_id', 'id');
    }
}
