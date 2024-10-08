<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules';

    protected $fillable = [
        'rule', 
        'support', 
        'confidence'
    ];

    public function get_product(){
        return $this->belongsTo(Product::class, 'rule', 'id');
    }

    // //delete
    // public function delete(){
    //     return $this->delete();
    // }
}
