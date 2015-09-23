@extends('layouts.first-settings-layout')

@section('stylesheets')
@stop
@section('page_header')
    <h1><i class="fa fa-pencil-square"></i>Home</h1>
@stop
@section('page_breadcrumb')
    <ol class="breadcrumb">
        <li>
            <a href="#">
                Dashboard
            </a>
        </li>
        <li class="active">
            Home
        </li>
    </ol>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12"><!-- Some Message to be Displayed start-->
            @if(Session::has('global'))
                <div class="alert alert-info">
                    <i class="fa fa-remove-sign"></i>{{ Session::get('global') }}
                </div>
            @endif
            @if ($errors->has())
                <div class="errorHandler alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h4 class="panel-title">Set <span class="text-bold">Current Session</span></h4>
                </div>
                <div class="panel-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <div class="form-group">
                                    <label for="">
                                        Choose Your Session To register For
                                    </label>
                                    <select id="form-field-select-session" class="form-control" name="session_id">
                                        <option value="">Select Current Session....</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-offset-6 col-md-2 no-display" id="form-button-submit">
                                <button class="btn btn-green btn-block" type="submit">
                                    Register <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- end: PAGE CONTENT-->
    @stop

    @section('scripts')
            <!-- Scripts for This page only -->
    <script src="{{ URL::asset('school/teacher/initial-settings.js') }}"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            SVExamples.init();
            TeacherIntialSettings.init();
        });
    </script>
@stop
