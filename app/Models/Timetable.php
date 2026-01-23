<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = ['company_id', 'day', 'time_slot', 'activity', 'venue', 'instructor'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
