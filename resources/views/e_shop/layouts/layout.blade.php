<!DOCTYPE html>
<html lang="ru">
  <head class="body">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/css/main.css">
    <style scoped> .pagination { justify-content: center!important; } </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>@yield('title')</title>
  </head>
  <body>
    <div class="center-block" style="width: 80%; margin:auto;">
    <!-- <table width='80%' style='margin:auto; border:1px double black'>-->
    <table class="table table-bordered">
    <!-- <table class="layout_table">-->
      <tbody>
      <tr>
        <td colspan="2">
          @include('e_shop.layouts.header')
        </td>
      </tr>
      <tr>
        <td class="sidebar">
          @include('e_shop.layouts.sidebar')
        </td>
        <td class="content">
          {{session('message')}}
          @if (isset($message))
            <div style="width: 100%; margin: auto" class="alert fade show alert-dismissible alert-{{$message['type']}}" role="alert">
              {{$message['text']}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
          @yield('content')
        </td>
      </tr>
      <tr>
        <td colspan="2" align="middle">
          <a href="/about">О проекте</a>
        </td>
      </tr>
      </tbody>
    </table>
    </div>
  </body>
</html>