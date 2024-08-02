<?php

namespace App\Models\DropDown;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class RegistrationStatusModel extends Model
{
    use HasFactory;
    use Sortable;
    protected $table = 'technocon_students_registration_status';
    protected $primaryKey = 'student_registration_status_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_registration_status_id',
        'registration_status',
        'bootstrap_class',
        'registration_status_backend',
        'registration_status_description',
        'added_datetime',    
    ];

    public $sortable = [
        'student_registration_status_id',
        'registration_status',
        'bootstrap_class',
        'registration_status_backend',
        'registration_status_description',
        'added_datetime',   
        
        
    ];





}
