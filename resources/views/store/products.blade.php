@extends('layouts.app')
@section('title', 'Todos os produtos de ' . $store->name . ' - Monitor de Preços')
@section('description', 'Veja todos os produtos disponíveis na ' . $store->name . '. Compare preços e encontre as melhores ofertas.')
@section('content')
    <div class="py-6">
        @livewire('store-products', ['store' => $store])
    </div>
@endsection
@push('scripts')
@endpush
