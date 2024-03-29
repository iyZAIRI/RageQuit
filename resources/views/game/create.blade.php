@extends('layouts.template')
@section('content')
<div class="hero common-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1> Add Game</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-single">
	<div class="container">
		<div class="row ipad-width">
            <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="search-form">
                        @if(!$errors->isEmpty())
                            <div class="row">
                                <div class="col-md-6 alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if(session()->has('success'))
                            <div class="row">
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            </div>
                        @endif
                        <h4 class="sb-title">Add a Game</h4>
                        <form method="post" class="form-style-1" action="{{ route('game.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div id="container" class="row">
                                <div class="col-md-6 form-it">
                                    <label>Game name</label>
                                     <input name="game_name" type="text" placeholder="Enter game name"required>
                                </div>
                                <div class="col-md-3 form-it">
                                    <label>Game artwork</label>
                                    <input name="photo" type="file">
                                </div>
                                <div class="col-md-3 form-it">
                                    <label>Game background</label>
                                    <input name="background_image" type="file">
                                </div>
                                <div class="col-md-6 form-it">
                                    <label>Official Website</label>
                                    <input name="website_link" type="text" placeholder="Enter official website link">
                                </div>
                                <div class="col-md-6 form-it">
                                    <label>Steam link</label>
                                    <input name="steam_link" type="text" placeholder="Enter steam link">
                                </div>
                                <div class="col-md-6 form-it">
                                    <label>Trailer link</label>
                                    <input name="trailer_link" type="text" placeholder="Enter steam link">
                                </div>
                                <div class="col-md-6 form-it">
                                    <label>Genre</label>
                                    <select name="genre_id">
                                        <option>Select genre</option>
                                        @foreach($genres as $genre)
                                            <option value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 form-it">
                                    <label>Description</label>
                                    <textarea name="description"></textarea>
                                </div>
                                <div id="entry 0" class="col-md-12">
                                    <div class="col-md-3 form-it">
                                        <label>Publisher</label>
                                        <select name="publisher_id[]">
                                            <option>Select publisher</option>
                                            @foreach($publishers as $publisher)
                                                <option value="{{ $publisher->id }}">{{ $publisher->publisher_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-it">
                                        <label>Platform</label>
                                        <select name="platform_id[]">
                                            <option>Select platform</option>
                                            @foreach($platforms as $platform)
                                                <option value="{{ $platform->id }}">{{ $platform->platform_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-it">
                                        <label>Release Year</label>
                                        <input name="release_year[]" type="text" placeholder="Enter release year"required>
                                    </div>
                                    <div class="entry-buttons">
                                        <div id="add-div" class="col-md-2 form-it">
                                            <label>Add</label>
                                            <button type="button" id="new-entry">+</button>
                                        </div>
                                        <div id="remove-div" class="col-md-1 form-it">
                                            <label>Remove</label>
                                            <button type="button" id="remove-entry">-</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input class="submit" type="submit" value="submit">
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//get element by class
var i=0;
document.getElementById("new-entry").onclick = function() {
    var container = document.getElementById("container");
    var entry = document.getElementById("entry "+i);
    var new_entry=container.insertBefore(entry.cloneNode(true), entry.nextSibling);
    new_entry.id="entry "+(i+1);
    i++;
    var buttons = document.getElementsByClassName("entry-buttons")[i];
    buttons.parentNode.removeChild(buttons);
}
document.getElementById("remove-entry").onclick = function() {
    var container = document.getElementById("container");
    var entry = document.getElementById("entry "+i);
    container.removeChild(entry);
    i--;
}
</script>
@endsection