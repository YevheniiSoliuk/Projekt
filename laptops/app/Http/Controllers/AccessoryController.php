<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Laptop;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;


class AccessoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Accessory::select(
        'id',
        'laptop_id',
        'image',
        'type',
        'name', 
        'color_text',
        'color',
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
            'type'=>'required|string',
            'name'=>'required|string', 
            'color_text'=>'required|string',
            'color'=>'required',
            'price'=>'required|numeric',
            'laptop_id'=>'required'
        ]);

        try{
            $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('accessory/image', $request->image, $imageName);

            $accessory = Accessory::create($request->post()+['image'=>$imageName]);

            $laptopId = $request->laptop_id;
            $accessory->laptop_id = $laptopId;
            $accessory->save();

            return response()->json([
                'message'=>'Accessory Created Successfully!!'
            ]);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while creating a accessory!!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function show(Accessory $accessory)
    {
        return response()->json([
            'accessory'=>$accessory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function edit(Accessory $accessory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Accessory $accessory)
    {
        $request->validate([
            'image'=>'nullable',
            'type'=>'required|string',
            'name'=>'required|string', 
            'color_text'=>'required|string',
            'color'=>'required',
            'price'=>'required|numeric'
        ]);

        try{

            $accessory->fill($request->post())->update();

            if($request->hasFile('image')){

                // remove old image
                if($accessory->image){
                    $exists = Storage::disk('public')->exists("accessory/image/{$accessory->image}");
                    if($exists){
                        Storage::disk('public')->delete("accessory/image/{$accessory->image}");
                    }
                }

                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('accessory/image', $request->image, $imageName);
                $accessory->image = $imageName;
                $accessory->save();
            }

            return response()->json([
                'message'=>'Accessory Updated Successfully!!'
            ]);

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while updating a accessory!!'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accessory $accessory)
    {
        try {

            if($accessory->image){
                $exists = Storage::disk('public')->exists("accessory/image/{$accessory->image}");
                if($exists){
                    Storage::disk('public')->delete("accessory/image/{$accessory->image}");
                }
            }

            $accessory->delete();

            return response()->json([
                'message'=>'Accessory Deleted Successfully!!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while deleting a accessory!!'
            ]);
        }
    }
}
