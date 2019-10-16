@extends('layout')

@section('content')
  <div class="flex items-center mb-3">
    <h1 class="w-full text-center mb-2 md:mb-0 md:text-left md:w-auto md:flex-1">{{ $title }}</h1>
  </div>

  <menu-create :locales="{{ json_encode($locales) }}"></menu-create>
@endsection