@extends('layouts.app')

@section('content')
<h2>Here are some Jobs related to your job interest</h2>
</div>
        <div class="results">

            <div id="hits-container"></div>
            <div id="pagination-container"></div>

        </div>
    </div>
@include('js_plugins.algolia')
@stop