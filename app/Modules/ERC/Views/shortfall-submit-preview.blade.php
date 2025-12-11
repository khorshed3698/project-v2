
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <strong>CCI&E Resubmitted Data: </strong>
                        </div>
                        <?php

                        function searchForId($id,$alldocuments) {
                            if(isset($alldocuments) && count($alldocuments)>0){
                                foreach ($alldocuments as $document) {
                                    if ($document->document_id == $id) {
                                        return $document->document_name_en;
                                    }
                                }
                            }else{
                                return null;
                            }
                            return null;
                        }
                        ?>
                        <div class="panel-body">
                            @foreach($resubmittedData->resubmit_data as $key=>$value)
                                @if ($key != 'doc_info')
                                    <div class="row">
                                        @foreach($value as $keys=>$data)
                                            <div class="col-md-12">
                                                {!! Form::label($data->name,ucfirst(str_replace('_',' ',$data->name)),['class'=>'text-left col-md-3']) !!}<span> {{$data->value}}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <br>
                                @else
<br>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover ">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th colspan="6">Attachments</th>
                                                <th colspan="2">Attached  file
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1;
                                            $attachment_list = $value;
                                            $clrDocuments = [];
                                            ?>
                                            {{--                {{dd($clrDocuments)}}--}}
                                            @foreach($attachment_list as $row)
                                                <tr>
                                                    <td>
                                                        <div align="center">{!! $i !!}<span
                                                                    class="required-star"></span></div>
                                                    </td>
                                                    <?php   $alldocuments = $shortfallData->data->req_doc_detail;

                                                    $doc_id=explode('_',$row->name);
                                                    ?>
                                                    <td colspan="6">{!!    searchForId($doc_id[1],$alldocuments) !!}</td>
                                                    <td colspan="2">
                                                        <?php
                                                            $osspath = explode('=',$row->value)[1];
                                                        ?>
                                                        <a target="_blank" href="{{'/uploads/'.$osspath}}" class="btn btn-info">
                                                            open
                                                        </a>

                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

