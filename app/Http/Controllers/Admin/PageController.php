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
use App\Page;
use App\PageAvailability;
use App\Membership;
use App\MemberMenu;


class PageController extends GlobalController
{
    public function index(){
        $frontEndPages = Page::where('type', 0)->get();
        $memberPages = Page::where('type', 1)->orderBy('order', 'ASC')->get();

        return view('admin.pages.index', compact('frontEndPages', 'memberPages'));
    }

    public function create(Request $request, $type){
        $memberships = Membership::all();
        $menus = MemberMenu::all();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
            ]);

            if(isset($_POST['frontEnd'])){
                $page = Page::create([
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'body' => (htmlspecialchars($request->content)),
                    'type' => 0,
                    'page_part' => $request->page_part,
                    'icon' => $request->icon,
                ]);
    
                Session::flash('success', 'Front end page successfully created');
            }else{
                if(!isset($request->available_for)){
                    Session::flash('error', 'At least one membership must be selected');
                    return redirect()->back()->withInput(Input::all());
                }
    
                if(isset($request->available_for) && count($request->available_for) == 0){
                    Session::flash('error', 'At least one membership must be selected');
                    return redirect()->back()->withInput(Input::all());
                }
                $lastpage = Page::orderBy('order', 'DESC')->first();

                $page = Page::create([
                    'menu_id' => $request->parent_page,
                    'title' => $request->title,
                    'slug' => $request->slug,
                    'body' => (htmlspecialchars($request->content)),
                    'slug' => $request->slug,
                    'type' => 1,
                    'page_part' => $request->page_part,
                    'order' => $lastpage->order + 1
                ]);
    
                foreach ($request->available_for as $membership) {
                    PageAvailability::create([
                        'membership_id' => $membership,
                        'page_id' => $page->id,
                    ]);
                }

                Session::flash('success', 'Member page successfully created');
            }
            
            return redirect()->route('pages.index');
        }
        
        return view('admin.pages.create', compact('memberships', 'menus', 'type'));
    }

    public function edit(Request $request, $id){
        $memberships = Membership::all();
        $menus = MemberMenu::all();
        $decrypted = Crypt::decrypt($id);
        $page = Page::find($decrypted);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
            ]);

            if(isset($_POST['frontEnd'])){
                $page->update([
                    'title' => $request->title,
                    'body' => (htmlspecialchars($request->content)),
                    'page_part' => $request->page_part,
                ]);
    
                Session::flash('success', 'Front end page successfully updated');
            }else{
                if(!isset($request->available_for)){
                    Session::flash('error', 'At least one membership must be selected');
                    return redirect()->back()->withInput(Input::all());
                }
    
                if(isset($request->available_for) && count($request->available_for) == 0){
                    Session::flash('error', 'At least one membership must be selecteds');
                    return redirect()->back()->withInput(Input::all());
                }
    
                $page->update([
                    'menu_id' => $request->page_part == 'side_nav' ? $request->parent_page : null,
                    'title' => $request->title,
                    'body' => (htmlspecialchars($request->content)),
                    'slug' => $request->slug,
                    'type' => 1,
                    'page_part' => $request->page_part,
                    'icon' => $request->icon,
                ]);

                //remove old available_for
                $pageAvailability = PageAvailability::where('page_id', $page->id)->delete();
    
                foreach ($request->available_for as $membership) {
                    PageAvailability::create([
                        'membership_id' => $membership,
                        'page_id' => $page->id,
                    ]);
                }

                Session::flash('success', 'Member page successfully updated');
            }
            
            
            return redirect()->route('pages.index');
        }

        return view('admin.pages.edit', compact('memberships', 'menus', 'page', 'id'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->page_id);
        $page = Page::find($decrypted);
        $pageAvailability = PageAvailability::where('page_id', $page->id)->delete();
        $page->delete();

        Session::flash('success', 'Page ' .$page->title. ' successfully deleted');
        return redirect()->route('pages.index');
    }

    public function setOrdering(Request $request){
        $ctr = 1;

        foreach ($request->pageOrders as $pageOrder) {
            $page = Page::find($pageOrder)->update([
                'order' => $ctr
            ]);         

            $ctr++;
        }

        Session::flash('success', 'Page ordering successfully updated');
        return redirect()->route('pages.index');
    }
}
