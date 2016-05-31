@extends('layout')

@section('content')
    <header>
        <div class="brand-ww">
            <div class="title text-xs-center"><img src="images/logo.png" alt="" width="187"/></div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="content">
            <div class="row">
                <div class="col-md-4 col-md-offset-4 text-xs-center m-t-3 m-b-3">
                    <img src="images/404.png" alt="404" width="120" class="m-b-3 m-t-3"/>
                    <h1 style="
                    font-family: 'Josefin Slab', serif;
                    font-size: 6rem;">Oh noes, 404!</h1>
                    <div class="title">Sesat? <a href="/"> Kembalilah ke pangkal jalan. </a></div>
                </div>
            </div>
        </div>
    </div>

@stop
