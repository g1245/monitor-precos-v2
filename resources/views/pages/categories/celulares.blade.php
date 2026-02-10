@extends('layouts.app')
@section('title', 'Celulares - Monitor de Preços')
@section('description', 'Encontre os melhores preços em celulares. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('category-products', ['category' => $category])
    </div>
@endsection
