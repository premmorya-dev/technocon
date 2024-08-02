<?php

namespace App\Models\DropDown;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class StudentSelectionModel extends Model
{
    use HasFactory;

    use Sortable;
    protected $table = 'students_selection_status';
    protected $primaryKey = 'student_selection_status_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_selection_status_id',
        'selection_status',
        'bootstrap_class',
        'selection_status_backend',
        'selection_status_description',
        'created_at',
        'updated_at',
     
    
    ];

    public $sortable = [
        'student_selection_status_id',
        'selection_status',
        'bootstrap_class',
        'selection_status_backend',
        'selection_status_description',
        'created_at',
        'updated_at',
     
        
        
    ];



}
