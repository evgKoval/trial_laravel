<form action="{{ route('search') }}" method="GET">
    <div class="row mb-4 align-items-center">
        <b-col sm="12">
            <b-form-input id="input-large" value="{{ request()->input('query') }}" placeholder="Search..." size="lg" name="query"></b-form-input>
        </b-col>
    </div>
</form>
