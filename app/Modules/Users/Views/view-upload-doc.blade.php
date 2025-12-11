@extends('layouts.admin')

@section('content')

<?php
use App\Libraries\ACL;

$accessMode = ACL::getAccsessRight('user');
if (!ACL::isAllowed($accessMode, '-V-')) {
    die('no access right!');
};
?>

{{--Include Modal form partials--}}
@include('partials.modal')

<section class="col-md-12" id="printDiv">
    <br/>
    @include('Users::doc-tab')
</section>

@endsection <!--content section-->

@section('footer-script')
@include('partials.datatable-scripts')
<script>
    // {{--get Modal Size Onclick Button--}}
    function createOffice(userId) {
        var id = userId;
        $("#body-content").load("{{URL::to('users/approve-user-modal/')}}" + "/" + id);
    }
</script>
@endsection