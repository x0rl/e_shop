@section ('title', 'Продажи')
@section('content')
  <div class="dropdown" >
    <button class="btn dropdown-toggle" data-toggle="dropdown">
      Выберите год
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="?year=2020">2020</a>
      <a class="dropdown-item" href="?year=2021">2021</a>
    </div>
  </div><br><br>
  <h3>Продажи за {{$year}} год</h3>
  У всех купленных продуктов цена 1 установлена для тестов
  @if ($sales)
    <div style="width: 700px; margin: auto">
    <table class="table">
      <tr>
        <th>Месяц</th>
        <th>Сумма</th>
      </tr>
      @foreach ($sales as $item)
        <tr>
          <td>[{{$item['month']}}] {{date('F', mktime(0, 0, 0, $item['month'], 0, $year))}}</td>
          <td>{{$item['sum']}}</td>
        </tr>
      @endforeach
    </table>
  @else
    Не найдено купленных продуктов за указанный год
  @endif
@endsection
@extends('e_shop.layouts.layout')