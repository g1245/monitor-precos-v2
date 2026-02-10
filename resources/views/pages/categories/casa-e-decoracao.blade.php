@extends('layouts.app')
@section('title', 'Casa e Decoração - Monitor de Preços')
@section('description', 'Encontre os melhores preços em casa e decoração. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('category-products', ['category' => $category])
    </div>
@endsection
