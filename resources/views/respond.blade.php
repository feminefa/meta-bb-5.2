@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @include('error')
                @if(!Session::has('success'))
                <div class="panel-heading"><h3>{{ $org->name }} Organization</h3></div>
                @endif
                <div class="panel-body">
                    @if(!Session::has('success'))
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('respond.save', [$org->code]) }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('action') ? ' has-error' : '' }}">
                            <label for="actions" class="col-md-4 control-label">Action</label>

                            <div class="col-md-6">
                                <div class="radio">
                                <input id="action1" type="radio" {{ old('action', @$org->action)=="1"?"checked":"" }}  name="action" value="1" required autofocus>

                                <label for="action1">Migrate this site and its content to canvas</label>
                                </div>
                                <div class="radio">
                                    <input id="action2" type="radio" {{ old('action', @$org->action)=="2"?"checked":"" }}  name="action" value="2" required autofocus>

                                <label for="action2">Retrieve organization data and delete this site.<br> <span style="font-size: 10px; color:grey">Please provide sections to be retrieved in the comment field below.</span></label>
                                </div>
                                    <div class="radio">
                                        <input id="action3" type="radio" {{ old('action', @$org->action)=="3"?"checked":"" }}  name="action" value="3" required autofocus>

                                <label for="action3">Delete organization and all its data.</label>
                                    </div>
                                @if ($errors->has('action'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('action') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                            <label for="date" class="col-md-4 control-label">Date Action should be taken</label>

                            <div class="col-md-6">
                                <input placeholder="MM/DD/YYYY" id="date" type="date" class="form-control" name="date" value="{{ old('date', @$org->action_date!=null?\Carbon\Carbon::parse($org->action_date)->format('Y-m-d'):"") }}" required>

                                @if ($errors->has('date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('datel') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                            <label for="comment" class="col-md-4 control-label">Comment (optional)</label>

                            <div class="col-md-6">
                                <textarea style="width:100%; height:100px" id="comment" type="text" class="form-control" name="comment"  autofocus>{{ old('comment', @$org->comment) }}</textarea>

                                @if ($errors->has('comment'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('pawprint') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Pawprint</label>

                            <div class="col-md-6">

                                <select class="form-control" name="pawprint" required>
                                    <option value="">Select one...</option>
                                    @foreach(json_decode ($org->leaders) as $leader)
                                        <option value="{{ $leader->pawprint }}" {{ (old('pawprint', @$org->responder)==$leader->pawprint?"selected":"") }}>{{ $leader->pawprint }}</option>
                                    @endforeach
                                </select>
                               <!-- <input id="pawprint" type="pawprint" value="{{ old('pawprint', @$org->responder) }}" class="form-control" name="pawprint" required>
-->
                                @if ($errors->has('pawprint'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pawprint') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit Response
                                </button>
                            </div>
                        </div>
                    </form>

                   @else
                        <h3>Thank you.</h3><br>
                   <b> For further inquiries, please contact us at:</b>
                   <br><br> 130 Heinkel Building
                        <br>Phone:  (573) 882-3303
                        <br>  http://etatmo.missouri.edu
                        <br>    @MIZZOUelearning
                   <br>
                     Educational Technologies @ Missouri (ET@MO)

                   @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
