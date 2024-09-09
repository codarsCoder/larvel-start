@extends('master')
{{-- @section('title', 'Kullanıcı Ekle') --}}
@section('main')
   <!-- Alpine.js Counter -->
   <div x-data="{ count: 0 }">
    <button @click="count--" :disabled="count === 0" class="decrement">Azalt</button>
    <span x-text="count" class="count-display"></span>
    <button @click="count++" class="increment">Arttır</button>
</div>



@endsection
