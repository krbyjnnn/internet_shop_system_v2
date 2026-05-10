@extends('layouts.admin')
@section('title', 'Products')
@section('content')

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="two-col">

    {{-- Add / Edit Product Form --}}
    <div class="card">

        @if(isset($product))
            {{-- Edit mode --}}
            <h2>Edit Product</h2>
            <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="{{ $product->name }}">
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" name="price" value="{{ $product->price }}" min="1" step="0.01">
                    @error('price')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" min="0">
                    @error('stock')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-primary">Update Product</button>
                    <a href="{{ route('admin.products') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>

        @else
            {{-- Add mode --}}
            <h2>Add Product</h2>
            <form method="POST" action="{{ route('admin.products.store') }}">
                @csrf
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Chips, Water, Coffee">
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" name="price" value="{{ old('price') }}" placeholder="e.g. 25" min="1" step="0.01">
                    @error('price')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" placeholder="e.g. 50" min="0">
                    @error('stock')<span class="error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn-primary">Add Product</button>
            </form>
        @endif

    </div>

    {{-- Products Table --}}
    <div class="card">
        <h2>All Products</h2>
        @if($products->isEmpty())
            <p style="color: #94a3b8; font-size: 14px;">No products yet.</p>
        @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr class="{{ $p->stock == 0 ? 'out-of-stock-row' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $p->name }}
                        @if($p->stock == 0)
                            <span class="badge badge-gray">Out of stock</span>
                        @endif
                    </td>
                    <td>₱{{ $p->price }}</td>
                    <td>{{ $p->stock }}</td>
                    <td style="display:flex; gap:6px;">
                        <a href="{{ route('admin.products.edit', $p->id) }}" class="btn-edit">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger"
                                onclick="return confirm('Delete this product?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>

@endsection