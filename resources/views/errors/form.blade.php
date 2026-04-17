<p>
    @yield('message').
    Meanwhile, you may <a href="{{ route('home') }}">return to home</a> or try using the search form.
</p>
<form class="search-form" action="http://google.com/search">
    <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search">
        <div class="input-group-append">
            <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
            </button>
        </div>
    </div>

</form>