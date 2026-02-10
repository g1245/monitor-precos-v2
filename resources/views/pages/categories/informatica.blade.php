@extends('layouts.app')
@section('title', 'Informática - Monitor de Preços')
@section('description', 'Encontre os melhores preços em informática. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('category-products', ['category' => $category])
    </div>
@endsection
