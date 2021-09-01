<DOCTYPE html>
  <head>
  <meta charset=”utf-8">
  </head>
  <body>
  <h2>Отсутствует ответственный администратор на категории только что созданного товара!</h2>
  Товар: {{ $product->name }} [{{$product->id}}]<br>
  Категория: {{ $product->sub_category_id }}<br>
  Кто добавил: {{ $user->name }} [{{$user->id}}]<br>

  Ссылка на страницу с товаром: {{ $link }}
 </body>
 </html>