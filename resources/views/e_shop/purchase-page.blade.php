@extends('e_shop.layouts.layout')
@section('title','Покупка '.$product['name'])
@section('content')
  <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Каталог</a></li>
      <li class="breadcrumb-item"><a href="/subCategory/{{$product->subCategory['id']}}">{{$product->subCategory['name']}}</a></li>
      <li class="breadcrumb-item"><a href="/product/{{$product['id']}}">{{$product['name']}}</a></li>
      <li class="breadcrumb-item active">Покупка</li>
    </ol>
  </nav>
  <div style="margin:auto; width: 700px">
    <table class="table">
      <tr>
        <th colspan="2">Покупка</th>
      </tr>
      <tr>
        <td>Название товара:</td>
        <td>{{$product['name']}}</td>
      </tr>
      <tr>
        <td>Описание товара:</td>
        <td>{{$product['description']}}</td>
      </tr>
      <tr>
        <td>Цена</td>
        <td>{{number_format($product['price'], 2, ',', '.')}}</td>
      </tr>
      <tr>
        <td>Количество на складе</td>
        <td>{{$product['quantity']}}</td>
      </tr>
      <tr>
        <td>Выбранное количество</td>
        <td>{{$quantity}}</td>
      </tr>
      <tr>
        <td>Сумма к покупке</td>
        <td>{{number_format($quantity * $product['price'], 2, ',', '.')}}</td>
      </tr>
      <tr>
        <td colspan="2">
          <form method="POST" action='{{ route('SubmitPurchase') }}'>
            @csrf
            <input hidden name="quantity" value="{{$quantity}}">
            <input hidden name="product_id" value="{{$product['id']}}">
            <input type="submit" class="btn" name="submit" value="Оплатить">
          </form>
        </td>
      </tr>
    </table>
  </div>
  <!-- receive notifications -->
  <script src="{{ asset('js/app.js') }}"></script>
 
  <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
  <script>
    let userId = 1;
    Echo.private(`users.${userId}`)
    .listen('SendedToAmoCRM', (e) => {
        alert('wordakkdsjk');
    });
  </script>
  <!-- receive notifications -->
@endsection