<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Ensure this is imported
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Add any additional columns
}
