@section('title', 'Поиск')
@section('content')
  <!-- todo повторяется из ProductsListPage -->
  <table class="table">
    <tr>
      <th>Название</th>
      <th>Rating</th>
      <th>Описание</th>
      <th>Цена</th>
      <th>Количество</th>
    </tr>
    @forelse($result as $item)
      <tr>
        <td><a target="_blank" href="/product/{{$item['id']}}">{{$item['name']}}</a></td>
        <td>
          @if ($item['reviews_count'])
            {{$item['rating']}}
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
            <br>{{$item['reviews_count']}}
          @else
            Нет отзывов
          @endif
        </td>
        <td>{{$item['description']}}</td>
        <td>{{$item['price']}}</td>
        <td>{{$item['quantity']}}</td>
      </tr>
    @empty
      <tr>
        <td colspan="5">Не найдено ниче</td>
      </tr>
    @endforelse
  </table>
  {{$result->links()}}
@endsection
@extends('e_shop.layouts.layout')