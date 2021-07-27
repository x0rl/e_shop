@section('title', 'Список покупок')
@section('content')
  <h2>Список покупок</h2>
  @if (count($shoppingList) == 0)
    <br>Пока вы не приобрели ни одного товара
  @else
    <div class="center-block" style="width: 800px; margin:auto;">
      <table class="table table-hover table-condensed table-bordered">
        <thead>
        <tr>
          <th>Название</th>
          <th>Описание</th>
          <th>Цена</th>
          <th>Количество</th>
          <th>Дата покупки</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($shoppingList as $item)
          <tr>
            <td class="align-middle"><a href='/product/{{$item->product["id"]}}'>{{$item->product['name']}}</a></td>
            <td>
              @if (strlen($item->product['description']) > 47)
                {{mb_substr($item->product['description'], 0, 50).'...'}}
              @else
                {{$item->product['description']}}
              @endif
            </td>
            <td class="align-middle">{{number_format($item->product['price'], 2, ',', '.')}}</td>
            <td class="align-middle">{{$item['quantity']}}</td>
            <td class="align-middle">{{$item['created_at']}}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
      {{$shoppingList->links()}}
    </div>
  @endif
@endsection
@extends('e_shop.layouts.layout')