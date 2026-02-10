@extends('layouts.app')
@section('title', 'Eletrônicos - Monitor de Preços')
@section('description', 'Encontre os melhores preços em eletrônicos. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('category-products', ['category' => $category])
    </div>
@endsection
