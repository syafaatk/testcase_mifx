<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'description',
        'published_year'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $orderField = $filters['sortColumn'] ?? 'books.id';
        $orderDirection = $filters['sortDirection'] ?? 'asc';

        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->where('title', 'like', '%' . $title . '%');
        })->when($filters['authors'] ?? null, function ($query, $authors) {
            $authorsId = explode(",", $authors);
            $query->whereHas('authors', function ($query) use ($authorsId) {
                $query->whereIn('id', $authorsId);
            });
        })
        ->leftJoin('book_reviews','book_reviews.book_id','=','books.id')
        ->addSelect(DB::raw('AVG(book_reviews.review) as avg_review'),'books.*')
        ->groupBy('books.id')
        ->orderBy($orderField, $orderDirection);
    }
}
