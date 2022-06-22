<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class LaptopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Laptop::select(
        'id',
        'user_id',
        'image',
        'manufacturer',
        'model', 
        'procesor',
        'memmory',
        'drive',
        'grafic',
        'price')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image'=>'required|image',
            'manufacturer'=>'required|string',
            'model'=>'required|string', 
            'procesor'=>'required|string',
            'memmory'=>'required|string',
            'drive'=>'required|string',
            'grafic'=>'required|string',
            'price'=>'required|numeric'
        ]);

        try{
            $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('laptop/image', $request->image, $imageName);

            $laptop = Laptop::create($request->post()+['image'=>$imageName]);
            if(Auth::check())
            {
                $id = auth()->user()->id;
            }
            $laptop->user_id = $id;
            $laptop->save();

            return response()->json([
                'message'=>'Laptop Added Successfully!!'
            ]);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while adding a laptop!!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Laptop  $laptop
     * @return \Illuminate\Http\Response
     */
    public function show(Laptop $laptop)
    {
        return response()->json([
            'laptop'=>$laptop
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Laptop  $laptop
     * @return \Illuminate\Http\Response
     */
    public function edit(Laptop $laptop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Laptop  $laptop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Laptop $laptop)
    {
        $request->validate([
            'image'=>'nullable',
            'manufacturer'=>'required|string',
            'model'=>'required|string', 
            'procesor'=>'required|string',
            'memmory'=>'required|string',
            'drive'=>'required|string',
            'grafic'=>'required|string',
            'price'=>'required|numeric'
        ]);

        try{

            $laptop->fill($request->post())->update();

            if($request->hasFile('image')){

                // remove old image
                if($laptop->image){
                    $exists = Storage::disk('public')->exists("laptop/image/{$laptop->image}");
                    if($exists){
                        Storage::disk('public')->delete("laptop/image/{$laptop->image}");
                    }
                }

                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('laptop/image', $request->image, $imageName);
                $laptop->image = $imageName;
                $laptop->save();
            }

            return response()->json([
                'message'=>'Laptop Updated Successfully!!'
            ]);

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while updating a laptop!!'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Laptop  $laptop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Laptop $laptop)
    {
        try {

            if($laptop->image){
                $exists = Storage::disk('public')->exists("laptop/image/{$laptop->image}");
                if($exists){
                    Storage::disk('public')->delete("laptop/image/{$laptop->image}");
                }
            }

            $laptop->delete();

            return response()->json([
                'message'=>'Laptop Deleted Successfully!!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while deleting a laptop!!'
            ]);
        }
    }
}
