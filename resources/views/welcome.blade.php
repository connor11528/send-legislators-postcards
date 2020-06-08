@extends('layouts.main')

@section('content')
    <div class="row mt-5">
        <div class="col-sm-6">

            <form method="POST" action="/message">
                @csrf
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control" name="first_name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control" name="last_name">
                </div>
                <div class="form-group">
                    <label for="address">Address Line 1</label>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="form-group">
                    <label for="zipcode">Zip Code</label>
                    <input type="text" class="form-control" name="zipcode">
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" name="message"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Send my message as a postcard">
                </div>
            </form>
        </div>
    </div>
@endsection
