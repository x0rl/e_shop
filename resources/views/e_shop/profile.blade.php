@section('title', 'Профиль')
@section('content')
  @if($emailVerified)
    <h3>Ваша почта была подтверждена {{$emailVerified}}</h3>
  @else
    <h3 style='display:inline'>Надо подтвердить почту для защиты аккаунта!</h3><br>
    <br><a href='{{ route("verification.notice") }}'>Перейти на страницу с подтверждением</a>
  @endif
@endsection
@extends('e_shop.layouts.layout')