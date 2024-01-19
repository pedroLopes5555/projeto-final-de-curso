<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    const PER_PAGE = 5;
    const MAX_PER_PAGE = 10;
    public function search(Request $request){
        $page = $request->page ?? 1;
        $page--;

        $tags = $request->tags ?? [];

        $per_page = $request->per_page ?? self::PER_PAGE;
        if($per_page>self::MAX_PER_PAGE){
            $per_page = self::MAX_PER_PAGE;
        }

        $query = Book::where('book_title', 'like', '%' . $request->q . '%')
    ->orWhere('book_isbn', 'like', '%' . $request->q . '%')
    ->orWhereHas('authors', function ($query) use ($request) {
        $query->where('person_name', 'like', '%' . $request->q . '%');
    })
    ->orWhereHas('bookFunctions', function ($query) use ($request) {
        $query->where('book_function_name', 'like', '%' . $request->q . '%');
    })
    ->with('authors');

    
        $count = (clone $query)->count();

        if (count($tags) != 0) {
            $query->withCount([
                'tags' => function ($q) use ($tags) {
                    $q->whereIn('tags.tag_id', $tags);
                }
            ])->having('tags_count', '=', count($tags));
            // get request tags
            $request_tags = $request->tags;
            $request_name_tags = Tag::whereIn('tag_id', $request_tags)->get();
        }
        

        if(isset($request->initial_year)){
            $query->where('book_year', '>=', $request->initial_year)
                ->where('book_year', '<=', $request->final_year);
        }

        $books = $query
            ->limit($per_page)
            ->skip($per_page * $page)
            ->with('pictures')
            ->get();
        
        if(!isset($request_name_tags)){
            $request_name_tags = [];
        }

        return response()->json([
            'books' => $books,
            'count' => $count,
            'per_page' => $per_page,
            'tags' => $request_name_tags,
        ]);
    }

}
