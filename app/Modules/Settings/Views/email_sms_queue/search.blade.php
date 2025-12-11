{!! Form::open(['url' => '#tracking/search','method' => 'GET','id' => ''])!!}
<div class="row">
    <div class="col-md-12">

        <div class="col-md-3"></div>
        <div class="col-md-5">
            <label for="">Search text: </label>
            {!! Form::text('search_text', '', ['class' => 'form-control search_text', 'placeholder'=>'Tracking Number']) !!}
        </div>
        <div class="col-md-1">
            <label for="">&nbsp;</label> <br>
            <input type="button" id="search_process" class="btn btn-primary" value="Search">
        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>
{!! Form::close()!!}


<table aria-label="Detailed Report Data Table" id="table_search" class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>Tracking No.</th>
        <th>Caption</th>
        <th>Email Status</th>
        <th>SMS Status</th>
        <th>Sent On</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>