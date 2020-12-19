<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
     * Return the list of authors
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $book = Book::all();
        return $this->successResponse($book);
    }

    /*
     * Create a book
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules =[
            'title' => 'required | max:255',
            'description' => 'required | max:255',
            'price' => 'required | min:1',
            'author_id' => 'required | min:1',
        ];

        $this->validate($request, $rules);


        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);


    }

    /*
     * Return a single book
     * @return Illuminate\Http\Response
     */
    public function show($book)
    {
        $book = Book::findOrFail($book);
        return $this->successResponse($book);
    }

    /*
     * Update a  book
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $book)
    {
        $rules =[
            'title' => ' max:255',
            'description' => 'max:255',
            'price' => 'min:1',
            'author_id' => 'min:1',
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($book);

        $book->fill($request->all());

        if ($book->isClean()){
            return $this->errorresponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $book->save();
        return $this->successResponse($book);
    }

    /*
     * Delete a book
     * @return Illuminate\Http\Response
     */
    public function destroy($book)
    {
        $book = Book::findOrFail($book);

        $book->delete();
        return $this->successResponse($book);
    }
}
