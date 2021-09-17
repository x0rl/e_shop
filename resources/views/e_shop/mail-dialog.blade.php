@section('title', $targetUser->name)
@section('content')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route("mail")}}">Входящие</a></li>
      <li class="breadcrumb-item active">{{$targetUser->name}}</li>
    </ol>
  </nav>
    Здесь будет выводиться переписка с пользователем {{ $targetUser->name }}<br><br>
    <div style='word-break:break-all;'>
    @foreach ($messages as $message)
        @if ($message->created_at->format('d:j:Y') == date('d:j:Y', time()))
            Сегодня.
        @else
            {{$message->created_at->format('d:j:Y')}}.
        @endif
        {{$message->created_at->format('G:i')}}. {{$message->fromUser->name}}: 
        
            {{ $message->message }}<br>
    @endforeach
    </div>
    <br>
    <form class='form-inline' method='POST' action='{{route("mail.dialog.sendMessage", $targetUser->id)}}'>
        @csrf
        <div class="input-group" style='margin: auto'>
            <textarea name='message' maxlength="2000" class="form-control" rows="3" style="resize:none"></textarea>     
            <button type='submit' class='btn'>Отправить</button>
        </div>
    </form>
@endsection
@extends('e_shop.layouts.layout')