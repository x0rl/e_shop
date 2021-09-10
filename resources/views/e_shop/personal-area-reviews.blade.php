@section ('title', 'Мои отзывы')
@section ('content')
  <div style="width: 700px; margin: auto;">
  <table class="table">
    @if ($userReviews)
      <thead>
      <th>Название</th>
      <th>Рейтинг</th>
      <th>Текст</th>
      <th>Дата</th>
      </thead>
      @foreach ($userReviews as $review)
        <tr>
          <td><a href="/product/{{$review->product['id']}}">{{$review->product['name']}}</a></td>
          <td>{{$review['rating']}}</td>
          <td>{{$review['text']}}</td>
          <td>{{$review['created_at']}}</td>
        </tr>
      @endforeach
    @else
      <tr><td colspan="4">Вы пока не оставили ни одного отзыва</td></tr>
    @endif
  </table>
  {{$userReviews->links()}}
  </div>
@endsection
@extends('e_shop.layouts.layout')