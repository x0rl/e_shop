<!DOCTYPE html>
    <head>
        <meta charset=”utf-8">
    </head>
    <body>
    <h2>Отчет за прошедшую неделю</h2>
    @foreach ($sales as $item)
        День: {{ date('l', strtotime($item->created_at))}}<br>
        Продукт: {{ $item->product->name }}<br>
    @endforeach
    <br>
    ______________________________
    <h2>Итоговая выручка</h2>
    {{ $total }}
    </body>
</html>
