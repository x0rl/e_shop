<a href="/">Главная страница</a><br><br>
@foreach (App\Models\Category::get() as $categoryItem)
  {{$categoryItem['name']}}<br>
  <ul>
    @foreach(App\Models\SubCategory::where('category_id', $categoryItem['id'])->get() as $item)
      <li><a href="/subCategory/{{$item['id']}}">{{$item['name']}}</a></li>
    @endforeach
  </ul>
@endforeach