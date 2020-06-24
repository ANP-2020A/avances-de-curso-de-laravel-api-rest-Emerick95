<?php

use App\Article;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//}
//});

Route::get('articles', function(){ //nos devuelve toda la lista de articulos
    return Article::all();
});

Route::get('articles/{id}', function($id){//buscar en la tabla el articulo que tenga el Id enviado como parametro
    return Article::find($id);
});

Route::post('articles', function(Request $request){ //crea un articulo y devuelvo el articulo creado
    return Article::create($request->all());
});

Route::put('articles/{id}', function(Request $request, $id){ //Busca el articulo pasando el Id que quiero actualizar y retorno el articulo con datos actualizados
    $article = Article::findOrFail($id);
    $article->update($request->all());
    return$article;
});

Route::delete('articles/{id}', function($id){//Elimina el articulo que envio como parametro el Id y se elimina
    Article::find($id)->delete();
    return 204;//Signigica que la accion se realizo correctamente, no content
});
