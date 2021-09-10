@section('title', $product['product_name'])
@section('content')
  <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Каталог</a></li>
      <li class="breadcrumb-item"><a href="/subCategory/{{$product->subCategory['id']}}">{{$product->subCategory['name']}}</a></li>
      <li class="breadcrumb-item" aria-current="page"><a href="/product/{{$product['id']}}">{{$product['name']}}</a></li>
      <li class="breadcrumb-item">Редактирование</li>
    </ol>
  </nav>
  <br><br><h2>{{$product['name']}}</h2><br>

  <form action='{{ route('editProduct', $product->id) }}'>
  <div class="center-block" style="width: 700px; margin:auto;">
    <table class="table table-bordered">
      <tbody>

      <tr>
        <td>Название: </td>
        <td>
          <div class="form-group has-warning">
          <input class="form-control" id="name" type="text" maxlength="45" name="name" value="{{$product['name']}}">
          </div>
        </td>
      </tr>
      <tr>
        <td>Описание: </td>
        <td><textarea class="form-control" id="desc" cols="50" maxlength="1000" rows="12" name="description">{{$product['description']}}</textarea></td>
      </tr>
      <tr>
        <td>Цена: </td>
        <td><input class="form-control" type="number" max="999999" name="price" value="{{$product['price']}}"></td>
      </tr>
      <tr>
        <td>Количество на складе: </td>
        <td><input class="form-control" type="number" name="quantity" max="9999"  value="{{$product['quantity']}}"></td>
      </tr>
      <tr>
        <td colspan="2"><input class="btn btn-outline-secondary" type="submit" name="submit" value="Сохранить"></td>
      </tr>
      </tbody>
    </table>
  </div>
  </form>
@endsection
@extends('e_shop.layouts.layout')