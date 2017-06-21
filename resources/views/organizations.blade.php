@extends('layouts.app')

@section('content')
    <style>
        .table  tr td.green {
            color:green !important;
            font-weight:bold !important;
        }

    </style>
    <script>
        $(document).ready(function() {

        })
    </script>
<div class="container">
    <div class="row">

        <div class="col-md-12">
            <div class="col-md-2">
                <div>
                    <form role="form" method="GET" action="{{ route('organizations') }}">
                        {{ csrf_field() }}
                        <input type="checkbox" id="tg" class="toggle-cb" > <label for="tg"> Check all</label>
                        <hr style="margin:4px">
                        @foreach(filters() as $key=>$filter)
                            <div class="form-group"></label>
                                <label  for="filter-{{ $key }}" style="font-weight: normal; width: 100%">
                                    <input style="float:right" class="check-box filter-cb" value="1" {{ isset(session('filters')[$key])?"checked":"" }} type="checkbox" name="filters[{{ $key }}]"  id="filter-{{ $key }}"> {{ $filter }}
                                </label>
                            </div>
                            @endforeach
                        <button type="submit" class="btn btn-default">Apply Filter</button>
                    </form>
                </div>
            </div>
            <div class="col-md-10">
            <div class="panel panel-default">
                @include('error')
                <div class="panel-heading"><h3><b>{{ (int)@$count }}</b> organization(s) found</h3>
                    <form class="form-horizontal" action="/organizations">
                    <input value="{{ @$_GET['q'] }}" type="text" name="q" placeholder="organization name"><input type="submit" value="Search">
                    </form>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('send') }}">
                        {{ csrf_field() }}

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Response</th>
                                <th>Action date</th>
                                <th>Responder</th>

                                <th>Comment</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orgs as $org)
                            <tr >
                                <td ><a href="/response/{{ $org->code }}" target="_blank" >{{ str_limit($org->name, 30) }}</a>
                                <br><a class="delete" href="javascript://" data-id="{{ $org->id }}" style="color:red; font-size:10px">remove</a> </td>
                                <td>{{ @$org->response }}</td>
                                <td style="{{ (\Carbon\Carbon::parse($org->action_date)->gt(\Carbon\Carbon::now()) && \Carbon\Carbon::parse($org->action_date)->lte(\Carbon\Carbon::now()->addDays(7))?"color:green":"") }}">
@if($org->responder)
                                  {!!  (\Carbon\Carbon::parse($org->action_date)->gt(\Carbon\Carbon::now()) && \Carbon\Carbon::parse($org->action_date)->lte(\Carbon\Carbon::now()->addDays(7))?"<B>".\Carbon\Carbon::parse($org->action_date)->format('M d, Y')."</b>":\Carbon\Carbon::parse($org->action_date)->format('M d, Y'))  !!}
@endif
                                </td>
                                <td>{{ @$org->responder }}</td>
                                <td>{{ str_limit($org->comment, 20) }}</td>
                                <td>
                                    @if(@$org->status=='pending')
                                    <a href="{{ route('process',[$org->id]) }}" class="btn btn-primary">Processed</a>
                                @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $orgs->links() }}
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function() {
            $('.toggle-cb').click(function() {

                $('.filter-cb').each(function() {
                   // console.log('femi')
                  //  if($(this).checked)
                    $(this).prop('checked', $('.toggle-cb').prop('checked'))
                })
            })
        })
        $('.delete').click(function() {
            if(confirm("Are you sure you want to delete this organization?")) {
                window.location.href='/organizations/'+$(this).data('id')+'/delete'
            }
        })
    </script>
@endsection
