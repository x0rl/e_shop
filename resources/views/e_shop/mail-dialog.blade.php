@section('title', $targetUser->name)
@section('content')
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route("mail")}}">Входящие</a></li>
      <li class="breadcrumb-item active">{{$targetUser->name}}</li>
    </ol>
</nav>
Здесь будет выводиться переписка с пользователем <span id='targetUser'>{{ $targetUser->name }}</span><br><br>
<form class='form-inline' method='POST' action='{{route("mail.dialog.sendMessage", $targetUser->id)}}'>
    @csrf
    <div class="input-group" style='margin: auto'>
        <textarea id='message' placeholder='Введите сообщение' oninput="sendTypingWhisper()" name='message' maxlength="2000" class="form-control" rows="3" style="resize:none"></textarea>     
        <button type='submit' class='btn'>Отправить</button>
    </div>
</form>
<div id = 'typing'></div>
<br>
<div id='messages' style='word-break:break-all;'>
    @foreach ($messages as $message)
        <div id='message-item'>
        @if ($message->created_at->format('d:j:Y') == date('d:j:Y', time()))
            Сегодня.
        @else
            {{$message->created_at->format('d:j:Y')}}.
        @endif
        {{$message->created_at->format('G:i')}}. {{$message->fromUser->name}}: 
        {{ $message->message }}
        </div>
        
    @endforeach
    <br>
    {!! $messages->links() !!}
</div>
<script src="https://unpkg.com/@webcreate/infinite-ajax-scroll@^3.0.0-beta.6/dist/infinite-ajax-scroll.min.js"></script>
<script>
    let ias = new InfiniteAjaxScroll('#messages', {
        item: '#message-item',
        next: 'a[rel="next"]',
        pagination: '.pagination'
    });

    $(function() {
        $('form').submit(function(e) {
            var $form = $(this);
            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: {
                    '_token': $('input[name="_token"]').attr('value'),
                    message: $('#message').val()
                }
            });
            //отмена действия по умолчанию для кнопки submit
            e.preventDefault();
            addMessageToDialog('{{Auth::user()->name}}', $('#message').val());
            $('#message').val('');
        });
    });

    Echo.private('new-message.{{Auth::user()->id}}')
        .listen('NewMessage', (e) => {
            if (e.from.name == $('#targetUser').html()) {
                addMessageToDialog(e.from.name, e.message.message, e.message.created_at);
                $.ajax({
                    type: 'GET',
                    url: '{{route('markMessagesAsRead')}}',
                    data: {
                        from: e.from.id
                    }
                });
            }
        });

    let timer;
    Echo.private(`chat.{{Auth::user()->id}}.{{$targetUser->id}}`)
        .listenForWhisper('typing', (e) => {
            $("#typing").html('{{$targetUser->name}} ' + '{{__('Typing')}}' + '...');
            clearTimeout(timer);
            timer = setTimeout(function typing() {
                $("#typing").html('');
            }, 2000);
        });

    let mark = Date.now();
    function sendTypingWhisper() {
        if (Date.now() - mark <= 1500) {
            return;
        }
        Echo.private(`chat.{{$targetUser->id}}.{{Auth::user()->id}}`)
            .whisper('typing', {});
        mark = Date.now();
    }

    function addMessageToDialog(from, message, timestamp = undefined)
    {
        let time;
        if (! timestamp) {
            time = new Date(Date.now());
        } else {
            time = new Date(Date.parse(timestamp));
        }
        time = time.toLocaleString("ru", { hour: 'numeric', minute: 'numeric' });
        $('#messages').prepend('Сегодня. ' + time + '. ' + from + '. ' + message + '<br>');
    }
</script>
@endsection
@extends('e_shop.layouts.layout')