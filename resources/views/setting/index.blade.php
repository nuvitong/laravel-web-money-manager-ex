@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>@lang('Settings')</h2>

            <div class="col-md-8 ">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('User Settings')</div>

                    <div class="panel-body">
                        <form action="{{url('transactions/store')}}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}


                            <div class="form-group label-static is-empty">
                                <button type="button" class="btn btn-primary btn-raised">@lang('Save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>@lang('Common')</h4>
                <div class="settings-common">

                    <dl class="dl-horizontal">
                        <dt>App Version</dt>
                        <dd>{{$version}}</dd>
                        <dt>API Version</dt>
                        <dd>{{\App\Services\Mmex\MmexConstants::$api_version}}</dd>
                    </dl>
                </div>

                <h4>@lang('Used Packages')</h4>

                <ul class=list-unstyled>
                    @foreach($packages as $package)
                        <li><a href="https://packagist.org/packages/{{$package["name"]}}"
                               target="_blank">{{$package["name"]}}{{'@'}}{{ $package["version"] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@stop