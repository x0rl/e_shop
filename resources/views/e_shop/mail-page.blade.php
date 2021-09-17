@section('title', 'Почта')
@section('content')
    <h3>Почта</h3>
    @if (count($messages) == 0)
        У вас пока нет писем. Подружиться с другими пользователями можно на страницах товаров в комментариях!<br>
        <a href='/mail/{{App\Models\User::first()->id}}'>Отправить сообщение первому попавшемуся прохожему</a>
    @else
        Список диалогов: <br>
        @foreach ($messages as $item)
            @if ($item->user->id !== Auth::user()->id)
                <a href='{{route("mail.dialog", $item->user->id)}}'> {{ $item->user->name }}</a><br>
            @else
                <a href='{{route("mail.dialog", $item->user->id)}}'> {{ $item->user->name }}</a><br>
            @endif
        @endforeach
    @endif
@endsection
@extends('e_shop.layouts.layout')