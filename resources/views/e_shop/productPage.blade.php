@extends('e_shop.layouts.layout')
@section('title', $product['name'])
@section('content')
  <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Каталог</a></li>
      <li class="breadcrumb-item"><a href="/subCategory/{{$product->subCategory['id']}}">{{$product->subCategory['name']}}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{$product['name']}}</li>
    </ol>
  </nav>
  <h2>{{$product['name']}}</h2>
  <div class="center-block" style="width: 700px; margin:auto;">
    <table class="table table-bordered">
      <tbody>
      <tr>
        <td><b>{{$product['name']}}</b></td>
        <td>{{$product['description']}}</td>
      </tr>
      <tr>
        <td>Цена: {{number_format($product['price'], 2, ',', '.')}}</td>
        <td>Количество на складе: {{$product['quantity']}}</td>
      </tr>
      <tr>
        <td colspan="2">
          @if ($inShoppingCart)
            <form action="/ShoppingCart/delete/{{$product['id']}}">
              <button class="btn" type="submit" name="action" value="delFromCart">Удалить из корзины</button>
            </form>
          @else
            <form action="/ShoppingCart/add/{{$product['id']}}">
              <button class="btn" type="submit" name="action" value="addToCart">Добавить в корзину</button>
            </form>
          @endif
        </td>
      </tr>
      @if (Auth::check())
        <tr>
          <td colspan="2">
            <form method="POST" class="form-inline justify-content-center" action="/buyProduct">
              @csrf
              <input hidden name="id" value="{{$product['id']}}">
              <div class="form-group mx-sm-2 mb-2">
                <label for="inputQuantity">Количество</label>
                <input max="{{$product['quantity']}}" min="1" name="quantity" type="number" value="1" class="form-control mx-sm-2" id="inputQuantity" placeholder="Количество">
              </div>
              <button type="submit" class="btn mb-2">Купить</button>
            </form>
          </td>
        </tr>
        @if (Auth::user()['status'] == 'admin')
          <tr>
            <td colspan="2"><a href="/editProduct/{{$product['id']}}">Редактировать</a></td>
          </tr>
          <tr>
            <td colspan="2"><a href="/editProduct/{{$product['id']}}?delete">Удалить</a></td>
          </tr>
        @endif
      @endif
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
      <tr>
      <td colspan="3">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            @if (isset($comments))
              <a class="nav-link active" href="?show=comments">Комментарии</a>
            @else
              <a class="nav-link" href="?show=comments">Комментарии</a>
            @endif
          </li>
          <li class="nav-item">
            @if (isset($reviews))
              <a class="nav-link active" href="?show=reviews">Отзывы</a>
            @else
              <a class="nav-link" href="?show=reviews">Отзывы</a>
            @endif
          </li>
        </ul>
        <table class="table">
        @if (isset($comments))
          @forelse ($comments as $comment)
            <tr>
              <td width="20%">{{$comment->user['name']}}</td>
              <td>{{$comment['text']}}</td>
              <td width="28%">{{$comment['created_at']}}</td>
            </tr>
          @empty
            <tr><td colspan="2">Комментариев нет. Будьте первыми!</td></tr>
          @endforelse
          <tr>
            <td colspan="3">he
              <form class="form-inline" method="POST" action="/product/{{$product['id']}}/addComment">
                @csrf
                <div class="form-group mx-sm-3 mb-2">
                  <textarea class="form-control" placeholder="Комментарий" style="width: 500px" name="comment"></textarea>
                </div>
                <input type="submit"{{!Auth::check() ? 'disabled' : ''}} class="btn btn-primary mb-2">
              </form>
              {{$comments->links()}}
            </td>
          </tr>
        @elseif (isset($reviews))
          @forelse($reviews as $review)
            <tr>
              <td width="20%">{{$review->user['name']}}</td>
              <td>{{$review['text']}}</td>
              <td>
                {{$review['rating']}}
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                </svg>
              </td>
              <td width="28%">{{$review['created_at']}}</td>
            </tr>
          @empty
            <tr><td colspan="2">Отзывов нет.</td></tr>
          @endforelse
            <tr>
              <td colspan="4">
                @if (!$isInShoppingList)
                  Чтобы оставить отзыв, приобретите товар<br><br>
                @elseif ($product->reviews()->where('user_id', Auth::user()['id'])->where('product_id', $product['id'])->first())
                  Для управления оставленными отзывами, перейдите в личный кабинет<br>todo<br><br>
                @else
                  <form class="form-inline" action="/product/{{$product['id']}}/addReview" method="POST">
                    @csrf
                    <div class="form-group mx-sm-3 mb-2">
                      <textarea class="form-control" placeholder="Отзыв" style="width: 300px" name="text"></textarea>
                    </div>
                    <div class="input-group mx-sm-3 mb-2">
                      <div class="input-group-prepend mb-2">
                        <span class="input-group-text">
                          <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                          </svg>
                        </span>
                      </div>
                      <input name="show" value="reviews" hidden>
                      <input type="number" min="1" max="5" class="form-control mb-2" name="rating">
                      <input type="submit" class="btn mx-sm-3 btn-primary mb-2">
                    </div>

                  </form>
                @endif
                {{$reviews->links()}}
              </td>
            </tr>
        @endif
        </table>

      </td>
      </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
  </div>
@endsection