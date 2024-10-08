<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyModel extends Model
{
    use HasFactory;

    protected $table = 'buy';

    protected $fillable = ['product_id', 'user_id'];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function purchasedProducts()
    {
        return $this->belongsToMany(Product::class, 'buy', 'user_id', 'product_id');
    }
}

