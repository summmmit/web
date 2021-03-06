<?php

use App\Models\UserGroup;
use App\Models\UserDetails;
use App\Models\Groups;

$user_id = Auth::user()->id;
$user_group = UserGroup::where('user_id', '=', $user_id)->get()->first();
$userDetails = UserDetails::where('user_id', '=', $user_id)->get()->first();
$admin_group_id = Groups::Administrator_Group_ID;
$teacher_group_id = Groups::Teacher_Group_Id;
$student_group_id = Groups::Student_Group_Id;
?>
@section('upper-dropdown')
    <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
        @if($userDetails->pic == null)
            <img src="{{ URL::asset('assets/images/anonymous.jpg') }}" class="img-circle" alt="" width="30px"
                 height="30px" id="upper_dropdown_menu_profile_image">
        @else
            <img src="{{ URL::asset('school/images/user/profile_images/'.$userDetails->pic) }}" class="img-circle"
                 alt="" width="30px" height="30px" id="upper_dropdown_menu_profile_image">
        @endif
        <span class="username hidden-xs">{{ $userDetails->first_name }} {{ $userDetails->last_name }}</span> <i
                class="fa fa-caret-down "></i>
    </a>
@stop
@section('left-user-profile')
    <div class="inline-block">
        @if($userDetails->pic == null)
            <img src="{{ URL::asset('assets/images/anonymous.jpg') }}" class="img-circle" alt="" width="50px"
                 height="50px" id="left_menu_profile_image">
        @else
            <img src="{{ URL::asset('school/images/user/profile_images/'.$userDetails->pic) }}" alt="" height="50px"
                 width="50px" id="left_menu_profile_image">
        @endif
    </div>
    <div class="inline-block">
        <h5 class="no-margin"> Welcome </h5>
        <h4 class="no-margin"> {{ $userDetails->first_name }} {{ $userDetails->last_name }} </h4>
    </div>
@stop

@section('left-menu')
    @if($user_group->group_id == $admin_group_id)
        @include('layouts.menus.admin.leftMenu')
    @elseif($user_group->group_id == 2)
        @include('layouts.menus.user.leftMenu')
    @elseif($user_group->group_id == 3)
        @include('layouts.menus.teacher.leftMenu')
    @endif
@stop

@section('upper-menu')
    @if($user_group->group_id == 1)
        @include('layouts.menus.admin.upperMenu')
    @elseif($user_group->group_id == 2)
        @include('layouts.menus.user.upperMenu')
    @elseif($user_group->group_id == 3)
        @include('layouts.menus.teacher.upperMenu')
    @endif
@stop