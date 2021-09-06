@section('title', 'Личный кабинет')
@section('content')
  {{-- <div class="center-block" style="width: 600px; margin:auto;">
    <table class="table">
      <tr>
        <td><a href='/ShoppingCart'>Корзина</a></td>
        <td><a href='/personal_area/shoppingList'>Список покупок</a></td>
        <td><a href="/personal_area/reviews">Мои отзывы</a></td>
        <td><a href='/pernsoal_area'>Профиль</a></td>
      </tr>
    </table>
  </div> --}}
  <ul class='list-group' style='display: inline-block'>
    <li class='list-group-item'><a href='/ShoppingCart'>Корзина</a></li>
    <li class='list-group-item'><a href='/personal_area/shoppingList'>Список покупок</a></li>
    <li class='list-group-item'><a href='personal_area/reviews'>Мои отзывы</a></li>
    <li class='list-group-item'><a href='/personal_area/profile'>Профиль</a></li>
  </ul>

@endsection
@extends('e_shop.layouts.layout')