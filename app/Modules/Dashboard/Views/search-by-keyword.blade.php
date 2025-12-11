{!! Form::open(array('url' => '/process/list','method' => 'POST', 'class' => 'hidden', 'id' => 'global-search', 'role' => 'form')) !!}

<div class="row">
    <div class="col-lg-6" style="margin-bottom: 15px;">
        <div class="input-group input-group-lg">
            <input type="text" name="search_by_keyword" required class="form-control" placeholder="Search by keywords">
            <span class="input-group-btn">
                <button class="btn btn-success" type="submit">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                </button>
            </span>
        </div>
    </div>
</div>

{!! Form::close() !!}