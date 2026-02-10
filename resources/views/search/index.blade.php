@extends('layouts.app')
@section('title', 'Busca - Monitor de Preços')
@section('description', 'Encontre os melhores preços em diversos departamentos. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('search-products', ['query' => $query])
    </div>
@endsection
@push('scripts')
@endpush