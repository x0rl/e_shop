@section('title', 'Профиль ' . $user->name)
@section('content')
    <h1>Профиль пользователя {{ $user->name }}</h1>
    Хотите отправить ему сообщение??
    <a href='{{route("mail.dialog", $user->id)}}'>Да, хочу</a>
@endsection
@extends('e_shop.layouts.layout')