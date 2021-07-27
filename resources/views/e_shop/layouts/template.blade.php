<!DOCTYPE html>
<html lang="ru">
  <head class="body">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>@yield('title')</title>
  </head>
  <body>
  <table width='50%' style='margin:auto; border:1px double black'>
    <!-- <table class="layout_table">-->
    <tr>
      <td class="header" colspan="2">
        @include('e_shop.layouts.header')
      </td>
    </tr>
    <tr>
      <td class="sidebar">
        @include('e_shop.layouts.sidebar')
      </td>
      <td class="content">
        {!!session('message').'<br>'!!}
        @yield('content')
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center; border: 1px double black;">todo проверки в админ панели с юзерами<br>todo валидация со стороны клиента с уведомлением об ошибке на все инпуты (ща криво чет все)<br>todo сохранение в создании продукта<br>todo use bail<br>todo представления editProduct addProduct повторяются<br>todo СДЕЛАЙ ТЫ КАРТИНКИ ЕБАННЫЙ СВЕТ<br>todo функция для автомат. обрезки описания<br>todo сайдбар при каждом обновлении берет данные из моделей<br>todo обрезать описание товаров<br>todo переписать регу и авторизацию?<br>todo список категорий из бд))<br>todo автоперенос корзины после авторизации<br>Реализуйте интернет магазин. В нем должны быть товары, категории, подкатегории. Список категорий и подкатегорий должен размещаться в сайдбаре сайта. У каждого товара должна быть цена, картинка, кнопка 'в корзину'.

        Реализуйте регистрацию пользователей. Зарегистрированный пользователь имеет личный кабинет, в нем он видит свою корзину, а также список своих покупок.

        Реализуйте админку, в которой можно добавлять, удалять и редактировать товары. Также в админке виден список пользователей. Админ может забанить и разбанить пользователя, а также повысить его до админа. Также в админке должна быть статистика покупок - сумма продаж по месяцам.</td>
    </tr>
  </table>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
  </body>
</html>