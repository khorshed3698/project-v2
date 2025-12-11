{!! Form::open(array('url' => '/settings/sector/store-product/'.$subSectorId,'method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'productForm',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> <i class="fa fa-sitemap"></i> Add Products</h4>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>

    <div class="row">
        <div class="col-lg-12" style="max-height:355px; overflow-y: scroll">
            <table aria-label="Detailed Product Report" class="table table-bordered productTable text-center">
                <tr class="alert alert-info">
                    <th>Name</th>
                    <th>ISIC Code</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                @forelse($product as $key => $product)
                    <tr class="productRow">
                        <td>
                            {!! Form::hidden("product_id[$key]",$product->id) !!}
                            {!! Form::text("name[$key]",$product->name,['class'=>'form-control required input-sm','placeholder'=>'Product name']) !!}
                        </td>
                        <td>{!! Form::number("isic_code[$key]",$product->isic_code,['class'=>'form-control required input-sm','placeholder'=>'ISIC Code']) !!}</td>
                        <td>{!! Form::select("status[$key]",[1=>'Active',0=>'Inactive'],$product->status,['class'=>'form-control required input-sm','placeholder'=>'Select one']) !!}</td>
                        <td>
                            @if($key == 0)
                                <button type="button" class="btn btn-sm btn-primary product-row-add"><i class="fa fa-plus"></i></button>
                            @else
                                <button type="button" class="btn btn-sm btn-danger product-row-remove disabled"><i class="fa fa-minus"></i></button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="productRow">
                        <td>{!! Form::text('name[0]','',['class'=>'form-control required input-sm','placeholder'=>'Product Name']) !!}</td>
                        <td>{!! Form::number('isic_code[0]','',['class'=>'form-control required input-sm','placeholder'=>'ISIC Code']) !!}</td>
                        <td>{!! Form::select('status[0]',[1=>'Active',0=>'Inactive'],'',['class'=>'form-control required input-sm','placeholder'=>'Select one']) !!}</td>
                        <td><button type="button" class="btn btn-sm btn-primary product-row-add"><i class="fa fa-plus"></i></button></td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>
</div>

<div class="modal-footer" style="text-align:left;">
    <div class="pull-left">
        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger', 'data-dismiss' => 'modal')) !!}
    </div>
    <div class="pull-right">
        @if(ACL::getAccsessRight('settings','A'))
            <button type="submit" class="btn btn-primary" id="product_create_btn" name="actionBtn" value="draft">
                <i class="fa fa-chevron-circle-right"></i> Save
            </button>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}


<script>
    $(document).ready(function () {
        $('.product-row-add').click(function(e){ //click event on add more fields button having class add_more_button
            e.preventDefault();
            var index = $(".productRow").length;
            var after_first_row = $('.productTable').find('.productRow').eq(0);

            $('<tr class="productRow">'+
                '<td><input class="form-control required input-sm" placeholder="Product Name" name="name['+index+']" type="text"></td>'+
                '<td><input class="form-control required input-sm" placeholder="ISIC Code" name="isic_code['+index+']" type="number"></td>'+
                '<td><select class="form-control required input-sm" name="status['+index+']"><option value="">Select one</option><option value="1">Active</option><option value="0">Inactive</option></select></td>'+
                '<td><button type="button" class="btn btn-sm btn-primary product-row-remove btn-danger"><i class="fa fa-minus"></i></button></td>'+
                '</tr>').insertAfter(after_first_row);
        });

        $(document.body).on('click', '.product-row-remove', function () {
            $(this).parent().parent().remove();
        });


        $("#productForm").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        var form = $("#productForm"); //Get Form ID
        var url = form.attr("action"); //Get Form action
        var type = form.attr("method"); //get form's data send method
        var info_err = $('.errorMsg'); //get error message div
        var info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit() {
            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
                beforeSend: function (msg) {
                    console.log("before send");
                    $("#product_create_btn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#product_create_btn").prop('disabled', true); // disable button
                },
                success: function (data) {
                    //==========validation error===========//
                    if (data.success == false) {
                        info_err.hide().empty();
                        $.each(data.error, function (index, error) {
                            info_err.removeClass('hidden').append('<li>' + error + '</li>');
                        });
                        info_err.slideDown('slow');
                        info_err.delay(2000).slideUp(1000, function () {
                            $("#product_create_btn").html('Submit');
                            $("#product_create_btn").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            window.location.href = data.link;
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#product_create_btn").html('Submit');
                            $("#product_create_btn").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    var errors = data.responseJSON;
                    $("#product_create_btn").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });
</script>
