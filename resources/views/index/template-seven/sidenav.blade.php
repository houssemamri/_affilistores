<ul class="nav">
    @foreach($store->categoryMenu as $submenu)
    <li class="nav-item ">
        <a class="nav-link" href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $submenu->category->permalink ]) }}" >
            <p>
                {{ $submenu->category->name }}
            </p>
        </a>
    </li>
    @endforeach
</ul>  