<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $terms = Term::latest()->paginate(5);

        return view('terms.index',compact('terms'))
            ->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('terms.create');
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
            'name' => 'required',
            'detail' => 'required',
        ]);

        Term::create($request->all());

        return redirect()->route('terms.index')
                        ->with('success','Term created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Term $term)
    {
        return view('terms.show',compact('term'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $term)
    {
        return view('terms.edit',compact('term'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $term->update($request->all());

        return redirect()->route('terms.index')
                        ->with('success','Term updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        $term->delete();

        return redirect()->route('terms.index')
                        ->with('success','Term deleted successfully');
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function lastActiveTerm()
    {
        $term = Term::orderBy('date', 'desc')->first();

        return view('terms.last',compact('term'));


    }

      /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request, Term $term)
    {
        $term = Term::find($request->id);
        $term->date = now();
        $term->update();

        return redirect()->route('terms.index')
        ->with('success','Term updated successfully');


    }

      /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function unpublish(Request $request, Term $term)
    {
        $term = Term::find($request->id);
        $term->date = NULL;
        $term->update();

        return redirect()->route('terms.index')
        ->with('success','Term updated successfully');


    }

}
