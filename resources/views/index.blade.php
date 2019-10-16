@extends('layout')

@section('content')
  <div class="flex items-center mb-3">
    <h1 class="w-full text-center mb-2 md:mb-0 md:text-left md:w-auto md:flex-1">Menus</h1>
    <a href="{{ route('menu.create') }}" class="btn btn-primary">Create Menu</a>
  </div>

  <menu-index :items="{{ json_encode($menus) }}"></menu-index>
@endsection
