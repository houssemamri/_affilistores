<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use Input;
use Hash;
use Session;
use Crypt;
use App\Poll;
use App\PollOption;
use App\PollVote;
use App\User;
use App\UserDetail;

class PollController extends GlobalController
{
    public function index(){
        $polls = Poll::all();

        return view('admin.polls.index', compact('polls'));
    }

    public function create(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'question' => 'required',
            ]);

            if(!isset($request->option)) {
                Session::flash('error', 'Options must have atleast two option');
                return redirect()->back()->withInput(Input::all());
            }else{
                if(count($request->option) < 2){
                    Session::flash('error', 'Options must have atleast two option');
                    return redirect()->back()->withInput(Input::all());
                }
            }
            $polls = Poll::where('question', '<>', '')->update(['status' => 0]);

            $poll = Poll::create([
                'question' => $request->question,
                'status' => $request->status
            ]);

            foreach ($request->option as $option) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'name' => $option
                ]);
            }

            Session::flash('success', 'Successfully added article');
            return redirect()->route('polls.index');
        }
        return view('admin.polls.create');
    }

    public function edit(Request $request, $id){
        $decrypted = Crypt::decrypt($id);
        $poll = Poll::find($decrypted);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'question' => 'required',
            ]);

            if(!isset($request->option)) {
                if(!isset($request->existing_option)){
                    Session::flash('error', 'Options must atleast two');
                    return redirect()->back()->withInput(Input::all());
                }

                if(isset($request->existing_option) && count($request->existing_option) < 2){
                    Session::flash('error', 'Options must atleast two');
                    return redirect()->back()->withInput(Input::all());
                }

            }else{
                if(!isset($request->existing_option)){
                    if(count($request->option) < 2){
                        Session::flash('error', 'Options must atleast two');
                        return redirect()->back()->withInput(Input::all());
                    }
                }

                if(isset($request->existing_option) && ((count($request->option) + count($request->existing_option)) < 2)){
                    Session::flash('error', 'Options must atleast two');
                    return redirect()->back()->withInput(Input::all());
                }
            }
            

            $poll->update([
                'question' => $request->question,
                'status' => $request->status
            ]);
            
            //update options
            $index = 0;
            foreach ($request->existing_option as $existingOption) {
                $pollOption = PollOption::find($request->option_id[$index]);
                
                $pollOption->update([
                    'name' => $existingOption
                ]);

                $index++;
            }

            //create options
            if(isset($request->option)){
                foreach ($request->option as $option) {
                    PollOption::create([
                        'poll_id' => $poll->id,
                        'name' => $option
                    ]);
                }
            }

            Session::flash('success', 'Successfully updated poll');
            return redirect()->route('polls.index');
        }

        return view('admin.polls.edit', compact('id', 'poll'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->poll_id);
        $poll = Poll::find($decrypted);

        $pollOptions = PollOption::where('poll_id', $poll->id)->delete();
        $pollVote = PollVote::where('poll_id', $poll->id)->delete();
        $poll->delete();

        Session::flash('success', 'Successfully deleted poll');
        return redirect()->route('polls.index');
    }
}
