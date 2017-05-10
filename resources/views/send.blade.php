@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    @include('error')
                    <div class="panel-heading">Send Email</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('send') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('data') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-2 control-label">Organizations</label>

                                <div class="col-md-10">
                                    <textarea placeholder="org_id     org_name      date_created     user1_email     user1_id    user1_firstname    user1_lastname
org_id     org_name      date_created     user2_email     user2_id    user2_firstname    user2_lastname" id="name" class="form-control" name="data" style="height: 200px; font-size: 10px " required autofocus>{{ old('data') }}</textarea>

                                    @if ($errors->has('data'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('data') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-2 control-label">Subject</label>

                                <div class="col-md-10">
                                 <select name="subject" class="form-control" required>
    <option value="">Choose one</option>
    @foreach(getMessage() as $key=>$message)
      @if(isset($message['label']))
        <option value="{{ $key }}" {{ ($key==old('subject') || $key==session('oldSubject')?"selected":"") }}>{!! $message['label'] !!} </option>
        @endif
    @endforeach

</select>
                                    @if ($errors->has('subject'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

<!--
                            <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-2 control-label">Message</label>

                                <div class="col-md-10">
                                    <textarea id="password" style="height: 200px; font-size: 10px " class="form-control" name="message" required>{{ old('message', @$message[1]['body']) }}</textarea>

                                    @if ($errors->has('message'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
-->

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Send
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
