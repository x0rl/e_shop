@extends ('e_shop.layouts.layout')
@section('title', 'Главная страница')
@section('content')
<h2>Список товаров категории {{$subCategory['name']}}</h2>
@if (Auth::check())
  @if(Auth::user()['status'] == 'admin')
    <a href="/newProduct/{{$subCategory['id']}}">Добавить продукт</a><br><br>
  @endif
@endif

@if (count($products) == 0)
  Не найдено продуктов в данной категории
@else

  <div class="center-block" style="margin:auto; width: 900px;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <td colspan="6">
            <div align="right">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Сортировка</a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="?orderBy=price&sort=asc">По цене (возрастание)</a>
                  <a class="dropdown-item" href="?orderBy=price&sort=desc">По цене (убывание)</a>
                  <a class="dropdown-item" href="?orderBy=rating&sort=desc">По рейтингу (убывание)</a>
                  <a class="dropdown-item" href="?orderBy=reviews_count&sort=desc">По отзывам (убывание)</a>
                  <a class="dropdown-item" href="?orderBy=id&sort=desc">По новизне (убывание)</a>
                </div>
              </li>
            </div>
          </td>
        </tr>
        <tr>
          <th>Название</th>
          <th>Rating</th>
          <th>Описание</th>
          <th>Цена</th>
          <th>Количество</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td><a href="/product/{{$product['id']}}">{{$product['name']}}</a></td>
            <td>
              @if ($product['reviews_count'])
                {{$product['rating']}}
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
                <br>{{$product['reviews_count']}}
              @else
                Нет отзывов
              @endif
            </td>

            <td>
              @if (strlen($product['description']) > 100)
                {{mb_substr($product['description'], 0, 100).'...'}}
              @else
                {{$product['description']}}
              @endif
            </td>
            <td>{{$product['price']}}</td>
            <td>{{$product['quantity'] == 0 ? 'Нет в наличии' : $product['quantity']}}</td>
            <td>
              @if (!Auth::check())
                @if (in_array($product['id'], array_column($userShoppingCart, 'id')))
                  В корзине<br>
                  <a href='?page={{$products->currentPage()}}&id={{$product["id"]}}&action=delFromCart'>Удалить из корзины</a>
                @else
                  <a href='?page={{$products->currentPage()}}&id={{$product["id"]}}&action=addToCart'>В&nbsp;корзину</a>
                @endif
              @else
                @if (in_array($product['id'], $userShoppingCart, 'id'))
                  В корзине<br>
                  <a href='?page={{$products->currentPage()}}&id={{$product["id"]}}&action=delFromCart'>Удалить из корзины</a>
                @else
                  <a href='?page={{$products->currentPage()}}&id={{$product["id"]}}&action=addToCart'>В&nbsp;корзину</a>
                @endif
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
    {!! $products->links() !!}
@endif
@endsection
