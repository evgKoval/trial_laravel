<b-nav class="mb-3">
    @foreach ($categories as $category)
            <b-nav-item href="/category/{{ $category['category'] }}">{{ ucfirst($category['category']) }}</b-nav-item>
    @endforeach
</b-nav>