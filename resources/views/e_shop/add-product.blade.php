@section('title', 'Создание нового товара')
@section('content')
  Вы добавляете товар в категорию {{$subCategory->name}}<br><br>
  @if (count($errors))
    @foreach ($errors->all() as $item)
      {{$item}}<br>
    @endforeach
  @endif
  <div class="center-block" style="width: 60%; margin:auto;">
  <form method="POST" action="/newProduct/add">
    @csrf
    <input class='form-control' hidden name="sub_category_id" value="{{$subCategory->id}}">
    <table class="table table-bordered">
      <tbody>
      <tr>
        <td>Название: </td>
        <td><input class="form-control" value="{{old('name')}}" type="text" name="name"></td>
      </tr>
      <tr>
        <td>Описание: </td>
        <td><textarea class="form-control" cols="50" rows="10" name="description">{{old('description')}}</textarea></td>
      </tr>
      <tr>
        <td>Цена: </td>
        <td><input class="form-control" value="{{old('price')}}" type="number" name="price"></td>
      </tr>
      <tr>
        <td>Количество на складе: </td>
        <td><input class="form-control" value="{{old('quantity')}}" type="number" name="quantity"></td>
      </tr>
      <tr>
        <td colspan="2"><input class="btn btn-outline-secondary" type="submit" name="submit" value="Сохранить"></td>
      </tr>
      </tbody>
    </table>
  </form>
@endsection
@extends('e_shop.layouts.layout')