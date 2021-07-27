<nav class="navbar navbar-expand-lg navbar-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      @if (!Auth::check())
      <li class="nav-item active">
        <a class="nav-link" href="/ShoppingCart">Корзина<span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/register">Регистрация</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/login">Авторизация</a>
      </li>
      @else
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{Auth::user()['name']}}
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/ShoppingCart">Корзина</a>
          <a class="dropdown-item" href="/personal_area/shoppingList">История покупок</a>
          <a class="dropdown-item" href="/personal_area">Личный кабинет</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/logout">Выйти</a>
        </div>
      </li>
      @if (Auth::user()['status'] === 'admin')
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Админ панель
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/admin_panel">Список пользователей</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Продажи</a>
          </div>
        </li>
      @endif
      <span class="navbar-text">
        Баланс: {{number_format(Auth::user()['money'], 2, ',', '.')}}
      </span>
      @endif
    </ul>
    <!-- todo сортировка, размеры или общее представление для списка товаров и поиска -->
    <form class="form-inline my-2 my-lg-0" action="/search">
      <input class="form-control mr-sm-2" name="name" type="search" placeholder="Имя товара" aria-label="Search">
      <button class="btn my-2 my-sm-0" type="submit">Поиск</button>
    </form>
  </div>

</nav>