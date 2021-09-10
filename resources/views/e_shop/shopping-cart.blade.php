@section('title', 'Корзина')
@section('content')
<h2>Корзина</h2>
@if (!count($shoppingCart))
  <br>Ваша корзина пуста :С
@else
  <div class="center-block" style="width: 700px; margin:auto;">
    <table class="table table-hover table-condensed table-bordered">
      <thead>
        <tr>
          <th>Название</th>
          <th>Описание</th>
          <th>Цена</th>
          <th>Количество на складе</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @if (Auth::check())
          @foreach ($shoppingCart as $item)
            <tr>
              <td class="align-middle"><a href='/product/{{$item->product["id"]}}'>{{$item->product['name']}}</a></td>
              <td>
                @if (strlen($item->product['description']) > 47)
                  {{mb_substr($item->product['description'], 0, 50).'...'}}
                @else
                  {{$item->product['description']}}
                @endif
              </td>
              <td class="align-middle">{{$item->product['price']}}</td>
              <td class="align-middle">{{$item->product['quantity']}}</td>
              <td class="align-middle"><a href='/ShoppingCart/delete/{{$item->product["id"]}}'>Удалить из корзины</a></td>
            </tr>
          @endforeach
          <tr><td colspan="5">{{$shoppingCart->links()}}</td></tr>
        @else
          @foreach ($shoppingCart as $item)
            <tr>
              <td class="align-middle"><a href='/product/{{$item["id"]}}'>{{$item['product']->name}}</a></td>
              <td>
                @if (strlen($item['product']->description) > 47)
                  {{mb_substr($item['product']->description, 0, 50).'...'}}
                @else
                  {{$item['product']->description}}
                @endif
              </td>
              <td class="align-middle">{{$item['product']->price}}</td>
              <td class="align-middle">{{$item['product']->quantity}}</td>
              <td class="align-middle"><a href='/ShoppingCart/delete/{{$item['product']->id}}'>Удалить из корзины</a></td>
            </tr>
          @endforeach
        @endif

      </tbody>
    </table>

  </div>
@endif
@endsection
@extends('e_shop.layouts.layout')