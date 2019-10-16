@extends('layout')

@section('content')
  <div class="flex items-center mb-3">
    <h1 class="w-full text-center mb-2 md:mb-0 md:text-left md:w-auto md:flex-1">Menu items <small class="text-muted">({{ $menu['name'] }} - {{ $menu['locale'] }})</small></h1>
  </div>

  <menu-item-index :items="{{ json_encode($menu['items']) }}" menu="{{ $menu['slug'] }}"></menu-item-index>

  <menu-item-create locale="{{ $menu['locale'] }}" menu="{{ $menu['slug'] }}"></menu-item-create>
@endsection