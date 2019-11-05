<form action="{{ route('search') }}" method="GET">
    <div class="row mb-4 align-items-center">
        <b-col sm="10">
            <b-form-input id="input-large" value="{{ request()->input('query') }}" placeholder="Example: Acer" size="lg" name="query"></b-form-input>
        </b-col>
        <b-col sm="2">
            <b-button type="submit" variant="primary" size="lg" block>Search</b-button>
        </b-col>
    </div>
</form>
