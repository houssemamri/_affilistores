<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use Session;
use Auth;
use Crypt;
use Purifier;
use App\MarketPlaceInstruction;

class InstructionController extends GlobalController
{
    public function index(){
        $instructions = MarketPlaceInstruction::all();

        return view('admin.instructions.index', compact('instructions'));
    }

    public function create(Request $request){
        $marketPlaces = $this->marketPlaces();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'market_place' => 'required',
                'instructions' => 'required',
            ]);

            MarketPlaceInstruction::create([
                'market_place' => $request->market_place,
                'instructions' => Purifier::clean($request->instructions)
            ]);

            Session::flash('success', 'Instructions successfully saved.');
            return redirect()->route('instructions.index');
        }

        return view('admin.instructions.create', compact('marketPlaces'));
    }

    public function edit(Request $request, $id){
        $instruction = MarketPlaceInstruction::where('id', Crypt::decrypt($id))->first();

        if(isset($instruction)){
            $marketPlaces = $this->marketPlaces();

            if($request->isMethod('POST')){
                $this->validate($request, [
                    'market_place' => 'required',
                    'instructions' => 'required',
                ]);
    
                $instruction->update([
                    'market_place' => $request->market_place,
                    'instructions' => Purifier::clean(htmlspecialchars($request->instructions))
                ]);
    
                Session::flash('success', 'Instructions successfully updated.');
                return redirect()->route('instructions.index');
            }
    
            return view('admin.instructions.edit', compact('id', 'instruction', 'marketPlaces'));
        }else{
            return redirect()->route('instructions.index');
        }
    }

    public function delete(Request $request){
        $instruction = MarketPlaceInstruction::where('id', Crypt::decrypt($request->instruction_id))->first();

        if(isset($instruction)){
            $instruction->delete();

            Session::flash('success', 'Instructions successfully deleted.');
        }

        return redirect()->route('instructions.index');
    }

    public function marketPlaces(){
        return [
            'amazon' => 'Amazon', 
            'ebay' => 'Ebay', 
            'aliexpress' => 'AliExpress', 
            'walmart' => 'Walmart', 
            'shopcom' => 'Shop.com', 
            'cjcom' => 'Cj.com', 
            'jvzoo' => 'JVZoo', 
            'clickbank' => 'ClickBank', 
            'warriorplus' => 'Warrior Plus', 
            'paydotcom' => 'PayDotCom'
        ];
    }
}
