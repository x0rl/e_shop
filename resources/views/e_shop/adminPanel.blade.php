@section('title', 'Админ панель')
@section('content')
  <h2>Список пользователей</h2>
  <div class="center-block" style="width: 700px; margin:auto;">
  <table class="table table-hover table-condensed table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Login</th>
        <th>Status</th>
        <th>E-mail</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{$user['id']}}</td>
            <td>{{$user['name']}}</td>
            <td>{{$user['status']}}</td>
            <td>{{$user['email']}}</td>
            <td>
              {!! $user['ban_status'] //todo
                ? "<a href='?page=".$users->currentPage()."&id=".$user['id']."&action=unban'>Разблокировать</a>"
                : "<a href=\"?page=".$users->currentPage()."&id=".$user['id']."&action=ban\">Заблокировать</a>"!!}
              <br>
              {!! $user['status'] == 'admin' //todo
                ? "<a href=\"?page=".$users->currentPage()."&id=".$user['id']."&action=downToUser\">Понизить до пользователя</a>"
                : "<a href=\"?page=".$users->currentPage()."&id=".$user['id']."&action=upToAdmin\">Повысить до админа</a>"!!}
            </td>
          </tr>
          @endforeach
    </tbody>
  </table>
  </div>
  {{$users->links()}}
@endsection
@extends('e_shop.layouts.layout')