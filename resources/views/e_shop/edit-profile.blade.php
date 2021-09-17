@section('title', 'Профиль')
@section('content')
  @include('e_shop.layouts.breadcrumbs-personal-area')
  @if($emailVerified)
    <h3 style='margin-bottom: 1em;'>Ваша почта была подтверждена {{$emailVerified}}</h3>
  @else
    <h3 style='display:inline'>Надо подтвердить почту для защиты аккаунта!</h3><br>
    <br><a href='{{ route("verification.notice") }}'>Перейти на страницу с подтверждением</a>
  @endif
  <div class="center-block" style="width: 700px; margin:auto;">
    <h3>Адрес доставки</h3>
    <div id='message' style='margin-bottom: 1em'>
    </div>
    <form action = '{{ route('editAddress') }}' method = 'POST'>
      @csrf
      <div class="input-group mb-3">
        <span class="input-group-text" id='city' name='city' id="basic-addon1">Город</span>
        <input type="text" value='{{optional($address)->city}}' id='cityInput' class="form-control" aria-describedby="basic-addon1">
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text" name='street' id="basic-addon1">Улица</span>
        <input type="text" value='{{optional($address)->street}}' id='streetInput' class="form-control" aria-describedby="basic-addon1">
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text" name='house' id="basic-addon1">Дом</span>
        <input type="text" value='{{optional($address)->house}}' id='houseInput' class="form-control" aria-describedby="basic-addon1">
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text" name='corp' id="basic-addon1">Корпус</span>
        <input type="text" id='corpInput' value='{{optional($address)->corp}}' class="form-control" placeholder="(необязательное поле)" aria-describedby="basic-addon1">
      </div>
      <button id='save' class="btn" type="submit" name="save" value="save">Сохранить</button>
    </form>
  </div>
  <script>
    $(function() {
      $('form').submit(function(e) {
        var $form = $(this);
        $.ajax({
          type: $form.attr('method'),
          url: $form.attr('action'),
          data: {
            '_token': $('input[name="_token"]').attr('value'),
            city: $('#cityInput').val(),
            street: $('#streetInput').val(),
            house: $('#houseInput').val(),
            corp: $('#corpInput').val()
          },
          error: function (err) {
            if (err.status == 422) { // when status code is 422, it's a validation issue
                $('#success_message').fadeIn().html(err.responseJSON.message);
                $("#message").fadeIn("slow").html('');
                let message = '';
                $.each(err.responseJSON.errors, function (i, error) {
                    message += error[0] + '<br>';
                });
                $("#message").fadeIn("slow").html(message);
            }
        }
        }).done(function(data) {
          $("#message").fadeIn('slow').html(data);
        });
        //отмена действия по умолчанию для кнопки submit
        e.preventDefault(); 
      });
    });
  </script>
@endsection
@extends('e_shop.layouts.layout')