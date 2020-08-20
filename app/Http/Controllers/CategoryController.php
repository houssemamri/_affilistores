<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use Crypt;
use Route;
use Purifier;
use File;
use App\Store;
use App\Category;
use App\CategoryMetaKeyword;
use App\ProductCategory;
use App\StoreCategoryMenu;

class CategoryController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }
    
    public function index(){
        $categories = Category::where('store_id', $this->store->id)->get();
        
        return view('products.category.index', compact('categories'));
    }

    public function create(Request $request){
        $pixabayCategories = [
            'all', 'fashion', 'nature', 'backgrounds', 'science', 'education', 'people', 'feelings', 'religion', 'health', 'places', 'animals', 'industry', 'food', 'computer', 'sports', 'transportation', 'travel', 'buildings', 'business', 'music'
        ];

        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required',
            ]);

            $checkDuplicate = Category::where('name', $request->name)->where('store_id', $this->store->id)->count();

            if($checkDuplicate > 0){
                Session::flash('error', 'Category ' . $request->name . ' already used');
                return redirect()->back()->withInput($request->input());
            }

            $image = $this->uploadImg($request);

            Category::create([
                'store_id' => $this->store->id,
                'name' => $request->name,
                'description' => $request->description,
                'image' => $image,
                'permalink' => isset($request->permalink) ? $request->permalink : $this->clean($request->name),
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'robots_meta_no_index' => isset($request->robots_meta_no_index) ? 1 : 0,
                'robots_meta_no_follow' => isset($request->robots_meta_no_follow) ? 1 : 0,
                'status' => isset($request->status) ? 1 : 0
            ]);
            
            Session::flash('success', 'Category ' . $request->name . ' created successfully');
            return redirect()->route('categories.index', $this->store->subdomain);
        }

        return view('products.category.create', compact('pixabayCategories'));
    }

    public function uploadImg(Request $request){
        if(isset($request->image)){
            $file = $request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'CATG_IMG_' . time() . '.' . $fileExtension;

            $file->move('img/uploads/'.$this->store->subdomain.'/categories', $fileName);

        }elseif(isset($request->pixabayImage)){
            $file = file_get_contents($request->pixabayImage);
            $fileExtension = explode('.', $request->pixabayImage);
            $fileName =  'FAV_ICN_' . time() . '.' . $fileExtension[count($fileExtension) - 1];
            $path = 'img/uploads/'.$this->store->subdomain.'/categories/';

            if(!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
            }

            file_put_contents($path . $fileName , $file); 
        }else{
            $fileName = '';
        }

        return $fileName;
    }

    public function edit(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $category = Category::find($decrypted);
        $pixabayCategories = [
            'all', 'fashion', 'nature', 'backgrounds', 'science', 'education', 'people', 'feelings', 'religion', 'health', 'places', 'animals', 'industry', 'food', 'computer', 'sports', 'transportation', 'travel', 'buildings', 'business', 'music'
        ];

        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required',
            ]);

            $checkDuplicate = Category::where('name', $request->name)->where('store_id', $this->store->id)->where('id', '<>', $category->id)->count();

            if($checkDuplicate > 0){
                Session::flash('error', 'Category ' . $request->name . ' already used');
                return redirect()->back()->withInput($request->input());
            }
            
            $image = $this->updateImage($request, $category);

            $category->update([
                'store_id' => $this->store->id,
                'name' => $request->name,
                'description' =>$request->description,
                'permalink' => isset($request->permalink) ? $request->permalink : $this->clean($request->name),
                'image' => $image,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'robots_meta_no_index' => isset($request->robots_meta_no_index) ? 1 : 0,
                'robots_meta_no_follow' => isset($request->robots_meta_no_follow) ? 1 : 0,
                'status' => isset($request->status) ? 1 : 0
            ]);
            
            Session::flash('success', 'Category ' . $request->name . ' updated successfully');
            return redirect()->route('categories.index', $this->store->subdomain);
        }

        return view('products.category.edit', compact('category', 'id', 'pixabayCategories'));
    }

    public function updateImage($request, $category){
        if(isset($request->image)){
            $file = $request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'CATG_IMG_' . time() . '.' . $fileExtension;

            $file->move('img/uploads/'.$this->store->subdomain.'/categories', $fileName);

        }elseif(isset($request->pixabayImage)){
            $file = file_get_contents($request->pixabayImage);
            $fileExtension = explode('.', $request->pixabayImage);
            $fileName =  'FAV_ICN_' . time() . '.' . $fileExtension[count($fileExtension) - 1];
            $path = 'img/uploads/' .$this->store->subdomain. '/categories/';

            if(!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
            }

            file_put_contents($path . $fileName , $file); 
        }else{
            $fileName = $category->image;
        }

        return $fileName;
    }

    public function delete(Request $request, $subdomain){
        $decrypted = Crypt::decrypt($request->category_id);
        $category = Category::find($decrypted);

        $productTags = ProductCategory::where('category_id', $category->id)->delete();
        $categoryMenu = StoreCategoryMenu::where('category_id', $category->id)->delete();

        $category->delete();
        
        Session::flash('success', 'Category ' . $category->name . ' deleted successfully');
        return redirect()->route('categories.index', $this->store->subdomain);
    }

    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = trim(preg_replace('/-+/', '-', $string), '-');
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase
 
		return $string;
    }
}
