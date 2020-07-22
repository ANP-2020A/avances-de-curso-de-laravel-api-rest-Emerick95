<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Resources\ArticleCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    private static  $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'body.required' => 'El body no es válido.',
    ];
    public function index(){
        return new ArticleCollection(Article::paginate(2));
    }

    public function show(Article $article){
        //para un articulo individual
        return response()->json(new ArticleResource($article),200);
    }

    //funciona para poder descargar la imagen
    public function image(Article $article) {
        return response()->download(public_path(Storage::url($article->image)), $article->title);
    }

    public function store(Request $request){
        //Validacion de datos para insertar datos

        $request->validate([
            'title' => 'required|string|unique:articles|max:255',
            'body' => 'required',
            'category_id'=>'required|exists:categories,id',
            'image' => 'required|image|dimensions:min_width=200,min_height=200', //mandatory validation for images
        ],self::$messages);


        //$article = Article::create($request->all());
        $article = new Article($request->all()); //crea una nueva instancia con los datos que llegan
        $path = $request->image->store('public/articles'); //se encarga de subir el archivo al servidor

        $article->image = $path;//asiga la ruta de path
        $article->save();
        return response()->json(new ArticleResource($article), 201); //retorna el recurso del articulo
    }

    public function update(Request $request, Article $article){

        $request->validate([
            'title' => 'required|string|unique:articles,title,'.$article->id.'|max:255',
            'body' => 'required',
            'category_id'=>'required|exists:categories,id'
        ],self::$messages);

        $article->update($request->all());
        return response()->json($article, 200);
    }

    public function delete(Request $request, Article $article){
        $article->delete();
        return response()->json(null, 204);
    }
}
