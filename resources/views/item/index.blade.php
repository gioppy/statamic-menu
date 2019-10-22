@extends('layout')

@section('content')
  <menu-item-index
    :items="{{ json_encode($menu['items']) }}"
    menu="{{ $menu['slug'] }}"
    title="{{ $menu['name'] }}"
    locale="{{ $menu['locale'] }}"></menu-item-index>
@endsection