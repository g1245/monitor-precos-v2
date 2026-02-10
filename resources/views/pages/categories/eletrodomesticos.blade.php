@extends('layouts.app')
@section('title', 'Eletrodomésticos - Monitor de Preços')
@section('description', 'Encontre os melhores preços em eletrodomésticos. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('category-products', ['category' => $category])
    </div>
@endsection
