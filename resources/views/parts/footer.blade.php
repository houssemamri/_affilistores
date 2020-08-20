<footer class="footer ">
    <div class="container-fluid">
        <nav class="pull-left">
            <ul>
                @foreach($footerPages as $page)
                    <li>
                        <a href="{{ route('default.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $page->slug]) }}">
                            {{ $page->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
        <div class="copyright pull-right">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script> <a href="#">{{ $site['site_name'] }}</a>
        </div>
    </div>
</footer>