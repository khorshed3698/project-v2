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
            <div id="nidInfoView">
                <div>
                    <table aria-label="Detailed NID Source Information" width="100%">
                        <tr>
                            <th aria-hidden="true" scope="col"></th>
                        </tr>
                        <tr>
                            <td width="50%"></td>
                            <td width="50%">
                        <tr>
                            <td>
                                <strong>Photo </strong>
                            </td>
                            <td>
                                <span id="">
                                    <img id="photo" src="{{ asset('users/upload/2019_5de348c232ec84.53328812images.jpg') }}"
                                         class="rounded-circle" alt="Photo" width="138" height="184" style="border-radius: 50%">
                                </span>
                            </td>
                        </tr>
                        </td>
                        </tr>
                    </table>

                    <table aria-label="Detailed Report Data Table" width="100%">
                        <tr>
                            <th aria-hidden="true" scope="col"></th>
                        </tr>
                        <tr>
                            <td width="30%"><label>Date of birth </label></td>
                            <td width="60%"><span id="date_of_birth"></span></td>
                        </tr>
                        <tr>
                            <td width="30%"><label>Name Bn </label></td>
                            <td width="60%"><span id="name_bn"></span></td>
                        </tr>
                        <tr>
                            <td width="30%"><label>Name En </label></td>
                            <td width="60%"><span id="name_en"></span></td>
                        </tr>
                        <tr>
                            <td width="30%"><label>Father </label></td>
                            <td width="60%"><span id="father_name"></span></td>
                        </tr>
                        <tr>
                            <td width="30%"><label>Mother </label></td>
                            <td width="60%"><span id="mother_name"></span></td>
                        </tr>
                        <tr>
                            <td width="30%"><label>NID </label></td>
                            <td width="60%"><span id="user_nid"></span></td>
                        </tr>
{{--                        <tr>--}}
{{--                            <td width="40%"><label>Gender </label></td>--}}
{{--                            <td width="60%"><span id="gender"></span></td>--}}
{{--                        </tr>--}}
                        <tr>
                            <td width="30%"><label>Address </label></td>
                            <td width="60%"><span id="address"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div>
    </div>
    <div class="modal-footer">
        &nbsp;
    </div>
</div>