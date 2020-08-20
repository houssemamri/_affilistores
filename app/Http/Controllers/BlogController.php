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
use App\Blog;
use App\BlogCategory;
use App\BlogFeed;
use App\BlogFeedAutomation;
use App\Category;

class BlogController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function blogs(){
        $blogs = Blog::with(['productCategory', 'category'])->where('store_id', $this->store->id)->get();

        return view('blogs.index', compact('blogs'));
    }

    public function blogCreate(Request $request){
        $categories = BlogCategory::where('store_id', $this->store->id)->get();
        $productCategories = Category::where('store_id', $this->store->id)->get();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'post' => 'required',
                'category' => 'required',
                'product_category' => 'required'
            ]);

            Blog::create([
                'store_id' => $this->store->id,
                'title' => $request->title,
                'post' => $request->post,
                'slug' => $this->clean($request->title),
                'blog_category_id' => $request->category,
                'category_id' => $request->product_category,
                'published' => isset($_POST['save_publish']) ? 1 : 0
            ]);

            Session::flash('success', 'Blog successfully added');
            return redirect()->route('blogs.index', $this->store->subdomain);
        }

        return view('blogs.create', compact('categories', 'productCategories'));
    }

    public function blogUpdate(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $blog = Blog::find($decrypted);
        $categories = BlogCategory::where('store_id', $this->store->id)->get();
        $productCategories = Category::where('store_id', $this->store->id)->get();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'post' => 'required',
                'category' => 'required',
                'product_category' => 'required'
            ]);

            $blog->update([
                'title' => $request->title,
                'slug' => $this->clean($request->title),
                'post' => $request->post,
                'blog_category_id' => $request->category,
                'category_id' => $request->product_category,
                'published' => isset($_POST['save_publish']) ? 1 : $blog->published
            ]);

            Session::flash('success', 'Blog successfully updated');
            return redirect()->route('blogs.index', $this->store->subdomain);
        }

        return view('blogs.edit', compact('categories', 'id', 'blog', 'productCategories'));
    }

    public function blogDelete(Request $request){
        $decrypted = Crypt::decrypt($request->blogId);
        $blog = Blog::find($decrypted)->delete();

        Session::flash('success', 'Blog successfully deleted');
        return redirect()->back();
    }

    public function publish(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $publish = Blog::find($decrypted);

        $publish->update([
            'published' => 1 
        ]);

        Session::flash('success', 'Blog successfully published');
        return redirect()->back();
    }

    public function unpublish(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $publish = Blog::find($decrypted);

        $publish->update([
            'published' => 0
        ]);

        Session::flash('success', 'Blog successfully unpublished');
        return redirect()->back();
    }

    public function categories(){
        $categories = BlogCategory::where('store_id', $this->store->id)->get();

        return view('blogs.categories.index', compact('categories'));
    }

    public function categoryCreate(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required'
            ]);

            BlogCategory::create([
                'store_id' => $this->store->id,
                'title' => $request->title,
                'description' => $request->description,
            ]);

            Session::flash('success', 'Category successfully added');
            return redirect()->route('blogs.categories.index', $this->store->subdomain);
        }

        return view('blogs.categories.create');
    }

    public function categoryUpdate(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $category = BlogCategory::find($decrypted);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required'
            ]);

            $category->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            Session::flash('success', 'Blog category successfully updated');
            return redirect()->route('blogs.categories.index', $this->store->subdomain);
        }

        return view('blogs.categories.edit', compact('category', 'id'));
    }

    public function categoryDelete(Request $request){
        $decrypted = Crypt::decrypt($request->categoryId);
        $category = BlogCategory::find($decrypted);

        $blogs = Blog::where('store_id', $this->store->id)->where('blog_category_id', $category->id)->delete();
        $feeds = BlogFeed::where('store_id', $this->store->id)->where('blog_category_id', $category->id)->delete();
        
        $category->delete();

        Session::flash('success', 'Blog category successfully deleted');
        return redirect()->back();
    }

    public function feeds(){
        $feeds = BlogFeed::where('store_id', $this->store->id)->get();
        
        return view('blogs.feeds.index', compact('feeds'));
    }

    public function feedCreate(Request $request){
        $categories = BlogCategory::where('store_id', $this->store->id)->get();
        $productCategories = Category::where('store_id', $this->store->id)->get();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'url' => 'required',
                'category' => 'required',
                'product_category' => 'required'
            ]);

            if((isset($request->from) && isset($request->to)) && $request->from >= $request->to){
                Session::flash('error', 'Invalid automation date from and date to.');
                return redirect()->back();
            }

            $feed = BlogFeed::where('store_id', $this->store->id)->where('url', $request->url)->count();

            if($feed > 0){
                Session::flash('error', 'Feed already existing');
                return redirect()->back()->withInput($request->all());   
            }
            
            $rss = $this->getRssFeed($request->url);
            if(isset($rss)){
                $blogFeed = BlogFeed::create([
                    'store_id' => $this->store->id,
                    'url' => $request->url,
                    'blog_category_id' => $request->category,
                    'category_id' => $request->product_category,
                ]);

                $blogFeedAutomation = BlogFeedAutomation::create([
                    'blog_feed_id' => $blogFeed->id,
                    'from' => $request->from,
                    'to' => $request->to,
                    'frequency' => isset($request->from) && isset($request->to) ? $request->frequency : null,
                    'auto_publish' => isset($request->auto_publish) ? 1 : 0
                ]);

                if(isset($_POST['btn_save_feed']))
                    $this->postFeed($rss, $request->category, $blogFeed);   
            

                Session::flash('success', 'Feed successfully added');
                return redirect()->route('blogs.feeds.index', $this->store->subdomain);
            }else{
                Session::flash('error', 'Invalid Rss Feed URL');
                return redirect()->back();
            }
        }

        return view('blogs.feeds.create', compact('categories', 'productCategories'));
    }

    public function updateRss($subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $feed = BlogFeed::find($decrypted);

        if(isset($feed)){
            $rss = $this->getRssFeed($feed->url);
            $this->postFeed($rss, $feed->blog_category_id, $feed);
    
            Session::flash('success', 'Feed successfully updated');
        }else{
            Session::flash('error', 'Feed URL invalid');
        }
       
        return redirect()->back();
    }

    public function getRssFeed($url){
        try {
            $rss = [];

            $simpleXml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
            
            foreach ($simpleXml->children()->channel->item as $item) {
                $search = in_array((string) $item->link, array_column($rss, 'link'));

                if(!$search){
                    array_push($rss, [
                        'title' => (string) $item->title,
                        'description' => (string)$item->description,
                        'link' => (string)$item->link
                    ]);
                }
            }
        } catch(\Exception $e) {
            $rss = null;
        }

        return $rss;
    }

    public function postFeed($blogs, $category, $feed){
        foreach ($blogs as $key => $blog) {

            $blogExist = Blog::where('store_id', $this->store->id)->where(function($query) use ($blog){
                return $query->orWhere('title', $blog['title'])->orWhere('post', $blog['description']);
            })->count();

            if($blogExist == 0){
                $blog = Blog::create([
                    'store_id' => $this->store->id,
                    'title' => $blog['title'],
                    'slug' => $this->clean($blog['title']),
                    'post' => $blog['description'],
                    'url' => $blog['link'],
                    'type' => 1,
                    'blog_category_id' => $category,
                    'category_id' => $feed->category_id,
                    'publish' => $feed->auto_publish
                ]);
            }
        }

        return true;
    }

    public function feedUpdate(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $categories = BlogCategory::where('store_id', $this->store->id)->get();
        $productCategories = Category::where('store_id', $this->store->id)->get();
        $feed = BlogFeed::find($decrypted);
        
        if($request->isMethod('POST')){
            $this->validate($request, [
                'url' => 'required',
                'category' => 'required',
                'product_category' => 'required'
            ]);

            if((isset($request->from) && isset($request->to)) && $request->from >= $request->to){
                Session::flash('error', 'Invalid automation date from and date to.');
                return redirect()->back();
            }
            
            $check = BlogFeed::where('store_id', $this->store->id)->where('id', '<>', $decrypted)->where('url', $request->url)->count();

            if($check > 0){
                Session::flash('error', 'Feed URL already existing');
                return redirect()->back()->withInput($request->all());   
            }
            
            $rss = $this->getRssFeed($request->url);

            if(isset($rss)){
                $feed->update([
                    'store_id' => $this->store->id,
                    'url' => $request->url,
                    'blog_category_id' => $request->category,
                    'category_id' => $request->product_category,
                ]);

                $feed->automation->update([
                    'from' => $request->from,
                    'to' => $request->to,
                    'frequency' => isset($request->from) && isset($request->to) ? $request->frequency : null,
                    'auto_publish' => isset($request->auto_publish) ? 1 : 0
                ]);

                if(isset($_POST['btn_save_feed']))
                    $this->postFeed($rss, $request->category, $feed);   

                Session::flash('success', 'Feed successfully updated');
                return redirect()->route('blogs.feeds.index', $this->store->subdomain);
            }else{
                Session::flash('error', 'Invalid Rss Feed URL');
                return redirect()->back();
            }
        }

        return view('blogs.feeds.edit', compact('categories', 'id', 'feed', 'productCategories'));
    }

    public function feedDelete(Request $request){
        $decrypted = Crypt::decrypt($request->feedId);
        $blog = BlogFeed::find($decrypted)->delete();

        Session::flash('success', 'Feed successfully deleted');
        return redirect()->back();
    }

    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = trim(preg_replace('/-+/', '-', $string), '-');
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase
 
		return $string;
    }
}
