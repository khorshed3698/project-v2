<!DOCTYPE html>
<html lang="en">
<head>
    <title>Company Registration - Single Form (New Registration)</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

</head>
<body>
<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-10   col-md-offset-1">
                        <div id="agreementheader" class="row text-center">
                            <h4 class="text-center"><b>ARTICLES OF ASSOCIATION</b></h4>
                        </div>

                        <div class="row">
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>A. General information</strong></div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" cellspacing="0"
                                                   width="100%">
                                                <tbody id="">
                                                <tr>
                                                    <td>
                                                        1. Name of the Entity
                                                    </td>
                                                    <td>
                                                        : {{$appInfo->verified_company_name}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        2. Entity Type
                                                    </td>
                                                    <td>

                                                        : {{$entityType}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        3. Registration No
                                                    </td>
                                                    <td>
                                                        :
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        4. RJSC Office
                                                    </td>
                                                    <td>

                                                        : {{$appInfo->rjsc_office_name}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Articles of Association</strong></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <tbody id="">
                                            <?php $i = 1;

                                            $totalRec = count($nrAoaClause);

                                            ?>


                                            @if($totalRec > 0)
                                                @foreach($nrAoaClause as $key => $v_aoa)
                                                    <?php $previousClauseId =  isset($nrAoaClause[$key-1]) ? $nrAoaClause[$key-1]->clause_id : null ?>
                                                    @if($previousClauseId != $v_aoa->clause_id)
                                                        <tr>
                                                            <td style="text-align: center" colspan="2">
                                                                {{ $v_aoa->name }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>

                                                        <td style="width: 10px">
                                                            <p>{{$v_aoa->sequence}}</p>
                                                        </td>
                                                        <td>
                                                            {!! $v_aoa->clause  !!}
                                                        </td>
                                                    </tr>
                                                    <?php $i++ ?>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>