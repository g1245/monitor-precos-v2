@extends('layouts.app')
@section('title', $department->name . ' - Monitor de Preços')
@section('description', 'Encontre os melhores preços em ' . $department->name . '. Compare ofertas e economize nas suas compras.')
@section('content')
    <div class="py-6">
        @livewire('department-products', ['department' => $department])
    </div>
@endsection
@push('scripts')
@endpush