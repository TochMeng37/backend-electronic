<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = ['productName', 'description', 'price', 'image','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function buy()
    {
        return $this->hasMany(BuyModel::class); // Assuming 'Buy' is the related model
    }

}
