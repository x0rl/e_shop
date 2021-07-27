@section('title', 'Личный кабинет')
@section('content')
  <div class="center-block" style="width: 500px; margin:auto;">
    <table class="table">
      <tr>
        <td><a href='/ShoppingCart'>Корзина</a></td>
        <td><a href='/personal_area/shoppingList'>Список покупок</a></td>
        <td><a href="/personal_area/reviews">Мои отзывы</a></td>
      </tr>
    </table>
  </div>
  <br><br>

@endsection
@extends('e_shop.layouts.layout')