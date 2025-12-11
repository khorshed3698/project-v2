<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                NID Source Information
            </h4>
        </div>
        <div class="modal-body">
            <div id="nidInfoEdit">

                <form class="form-horizontal" id="edit_nid" method="POST" action="">
                    {{ csrf_field() }}
                    <table aria-label="Detailed NID Source Information" style='border:0px;width:100%;'>
                        <tr>
                            <th aria-hidden="true" scope="col"></th>
                        </tr>
                        <tr>
                            <td>Verification Flag:</td>
                            <td>
                                <input type="text" name="verification_flag" id="verification_flag"
                                       class="form-control" style="width: 50%;">
                            </td>
                        </tr>

                        <tr style="height:55px;">
                            <td>No of Try:</td>
                            <td><input type="text" name="no_of_try" id="no_of_try" class="form-control"
                                       style="width: 50%;"></td>
                        </tr>

                        <tr style="height: 55px;">
                            <td>NID :</td>
                            <td><input type="text" name="nid" id="nid" class="form-control"
                                       style="width: 50%;"/></td>
                        </tr>

                        <tr style="height: 55px;">
                            <td>Date of birth :</td>
                            <td>
                                <div class="input-group date datepicker" style="width: 50%;">
                                    <input type="text" name="dob" id="dob"
                                           class="form-control datepicker"/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                            </td>
                        </tr>

                        <tr style="height:55px;">
                            <td>&nbsp;</td>
                            <td>
                                <input type="hidden" name="nid_id" id="encoded_nid_id">
                                <input type="submit" value="Update" class="btn btn-info">
                            </td>
                        </tr>
                    </table>
                </form>

            </div>
            <div class="clearfix">&nbsp;</div>
        </div>
        <div class="modal-footer">&nbsp;
        </div>
    </div>
</div>