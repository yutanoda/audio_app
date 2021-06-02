<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $with = ['category', 'author'];

    protected $guarded = ['id']; //everything is fillable except id

    public function scopeFilter($query, array $filters) //Post::newQuery()->filter()
    {
        $query->when($filters['search'] ?? false, fn ($query, $search) => 
            $query
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('body', 'like', '%' . $search . '%'));
        
        $query->when($filters['category'] ?? false, fn($query, $category) => 
            $query
                ->whereExists(fn($query) => 
                    $query->from('categories')
                        ->whereColumn('categories.id', 'posts.category_id')
                        ->where('categories.slug', $category))
            );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // protected $fillable = ['title', 'excerpt', 'body'];
    
}
