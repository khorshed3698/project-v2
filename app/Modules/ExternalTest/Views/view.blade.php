

        <section class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5>
                        <strong>{{ trans('Application View') }}</strong>
                    </h5>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    @include('partials.messages')
                    <div class="table-responsive" style="overflow:visible;">
                        <pre>
                            {{var_dump(json_decode($appData->appdata,true))}}
                        </pre>
                    </div> <!-- /.table-responsive -->
                </div> <!-- /.panel-body -->
            </div><!-- /.panel -->

        </section>

