<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function index() 
    {
        return Inertia::render('Products/Index', [
            'filters' => Request::all('search', 'trashed'),
            'products' => Auth::user()->account->products()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'deleted_at' => $product->deleted_at
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store()
    {
        Auth::user()->account->products()->create(
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['required', 'max:100'],
            ])
        );

        return Redirect::route('products')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'deleted_at' => $product->deleted_at
            ]
        ]);
    }
    
    public function update(Product $product)
    {
        $product->update(
            Request::validate([
                'name' => ['required', 'max:50'],
                'description' => ['nullable', 'max:100']
            ])
        );

        return Redirect::back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return Redirect::back()->with('success', 'Product deleted.');
    }

    public function restore(Product $product)
    {
        $product->restore();
        
        return Redirect::back()->with('success', 'Product restored.');
    }
}
