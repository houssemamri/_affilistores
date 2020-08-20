<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use Input;
use Hash;
use Session;
use Crypt;
use Purifier;
use App\Article;
use App\User;
use App\UserDetail;
use App\MemberDetail;

class ArticleController extends GlobalController
{
    public function index(){
        $articles = Article::all();

        return view('admin.articles.index', compact('articles'));
    }

    public function create(Request $request){

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
            ]);

            Article::create([
                'title' => $request->title,
                'body' => Purifier::clean(htmlspecialchars($request->content))
            ]);

            Session::flash('success', 'Successfully added article');
            return redirect()->route('articles.index');
        }
        return view('admin.articles.create');
    }

    public function edit(Request $request, $id){
        $decrypted = Crypt::decrypt($id);
        $article = Article::find($decrypted);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
            ]);
            
            $article->update([
                'title' => $request->title,
                'body' => Purifier::clean(htmlspecialchars($request->content))
            ]);

            Session::flash('success', 'Successfully updated article ' . $article->title);
            return redirect()->route('articles.index');
        }

        return view('admin.articles.edit', compact('article', 'id'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->article_id);
        $article = Article::find($decrypted);
        $article->delete();

        Session::flash('success', 'Successfully deleted article ' . $article->title);
        return redirect()->route('articles.index');
    }
}
