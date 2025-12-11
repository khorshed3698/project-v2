@extends('layouts.admin')

@section('page_heading',trans('messages.stakeholder_form'))

@section('style')
    <link href="{{ asset("assets/stakeholder-plugins/jsoneditor/jsoneditor.css") }}" rel="stylesheet" type="text/css"/>
    <style>
        input[type="radio"].error {
            outline: 1px solid red
        }

        #jsoneditor {
            height: 500px;
        }

        #jsoneditor p {
            font-family: "DejaVu Sans", sans-serif;
        }

        .jsoneditor-sort {
            display: none;
        }

        .jsoneditor-transform {
            display: none;
        }

    </style>
@endsection

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'E')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> Stakeholder info edit </b></h5>
            </div><!-- /.panel-heading -->

            {!! Form::open(array('url' => '/settings/get-external-service-list/update','method' => 'post', 'class' => 'form-horizontal', 'id' => 'stakeholder',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form', 'onsubmit' => 'return jsonCompact()')) !!}
            {!! Form::hidden('process_type_id', Encryption::encodeId($externalService->id),['class' => 'form-control input-md required', 'id'=>'process_type_id']) !!}
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-12">
                        <div id="jsoneditor"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="pull-left">
{{--                        <button type="button" class="btn btn-md btn-success" onclick="openJsonEditorModal()">Open Json--}}
{{--                        </button>--}}
                    </div>
                    <div class="pull-right">
                        <a href="{{ url('/settings/external-service-list') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-md btn-default')) !!}
                        </a>
                        @if(ACL::getAccsessRight('settings','E'))
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- /.box -->
        {!! Form::close() !!}<!-- /.form end -->
        </div>
    </div>
    <div class="modal fade" id="jsonEditorModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-success">Demo Json</h4>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <pre><code id="hello"></code></pre>
                    <div id="demoJson"></div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div><!--./modal-->
@endsection


@section('footer-script')
    <script src="{{ asset("assets/stakeholder-plugins/jsoneditor/jsoneditor.js") }}"></script>
    <script src="{{ asset("assets/stakeholder-plugins/jsoneditor/lodash.min.js") }}"></script>
    <script type="text/javascript">
        var _token = $('input[name="_token"]').val();
        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $("#stakeholder").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        function openJsonEditorModal()
        {
           //  let jsonData = {
           //      "agencyname": "Brack Bank",
           //      "agencylogo": "logo.png",
           //      "servicename": "Bank Account Open (Local Investor)",
           //      "service_key": "Bank Account",
           //      "declaration": "All the details and information provided in this form are true and complete.",
           //      "server_configuration": {
           //          "submission_type": "Token_based",
           //          "token_details": {
           //              "body": {
           //                  "grant_type": "password",
           //                  "username": "bida",
           //                  "password": "bida123",
           //                  "scope": "bida_scope"
           //              },
           //              "header": [
           //                  "Content-Type: application/json",
           //                  "Authorization: Basic Zzh4SnpmSGtucXFLMzhPWWhBZjgwVXBZd2ZjYTplcjB5dHRUNjA2ZEpUNWlhNzhJYkR6Y21sV2Nh"
           //              ]
           //          },
           //          "submission_url": "https://wso2-apim-uat01.thecitybank.com/miscellaneous/bidaService/accountOpening",
           //          "status_api_url": "https://wso2-apim-uat01.thecitybank.com/miscellaneous/bidaService/$.bida_oss_id",
           //          "submission_token_url": "https://wso2-apim-uat01.thecitybank.com/wso2/token",
           //          "submission_token_client": "",
           //          "submission_token_secret": ""
           //      },
           //      "introduction": "",
           //      "guideline": "",
           //      "videotuitorial": "",
           //      "formpreview": "http://localhost:8000/uploads/2023/07/DCCI_COS_OPN_64b390fc6efa90.21070595.pdf",
           //      "tracking_no_prefix": "CTBL",
           //      "default_data": {
           //          "channelName": "bida",
           //          "channelSecret": "string",
           //          "channelTransactionId": "12345678",
           //          "serviceId": 800,
           //          "applicationTitle": "420 Days of Reporting"
           //      },
           //      "data": {
           //          "User_Name": "@users.user_email",
           //          "User_Email": "@users.user_email",
           //          "User_Phone": "@users.user_phone",
           //          "User_Nid": "@ea_apps.ceo_nid",
           //          "User_Tin": "@users.user_tin",
           //          "User_Bin": "",
           //          "Client_Name": "@ea_apps.company_name",
           //          "User_Address": "@ea_apps.ceo_address",
           //          "Description": "",
           //          "Bank_Code": "<list>insidedbApi:oss-api-service-client/citybank_bida_sector",
           //          "Bank_Branch_Code": "<list>insidedbApi:oss-api-service-client/citybank_bida_sector",
           //          "Comment": "",
           //          "Date_Of_Birth": ""
           //      },
           //      "input_specification": {
           //          "Description": {
           //              "label": "Details",
           //              "type": "textarea",
           //              "additionalClasses": "texteditor abcd",
           //              "textEditor": true
           //          },
           //          "Comment": {
           //              "label": "Comment",
           //              "type": "textarea"
           //          },
           //          "Date_Of_Birth": {
           //              "type": "date"
           //          }
           //      },
           //      "files": {
           //          "other_attachment": [
           //              {
           //                  "Title": "Certificate of Incorporation (Pdf)",
           //                  "File_Key": "Certificate_of_Incorporation"
           //              },
           //              {
           //                  "Title": "Memorandum of Article and Association",
           //                  "mandatory": true
           //              },
           //              {
           //                  "Title": "TIN_Certificate"
           //              },
           //              {
           //                  "Title": "Trade_License"
           //              },
           //              {
           //                  "Title": "National_ID_Card_Account_Operating_Person"
           //              }
           //          ]
           //      }
           //  };
            // let jsonString = JSON.parse(jsonData);
            //  let formattedJson = '';
            //  for (let key in jsonData) {
            //      if (jsonData.hasOwnProperty(key)) {
            //          formattedJson += key + ': ' + JSON.stringify(jsonData[key]) + ',\n';
            //      }
            //  }
            // $('#jsonEditorModal .modal-body #hello').html(formattedJson);
            // $('#jsonEditorModal').modal('show');

            let jsonString = '{{ !empty($externalService->external_service_config) ? $externalService->external_service_config : '' }}';
            // let jsonStringify = JSON.stringify(jsonString);
            //console.log(jsonStringify);
            //  let jsonObject = JSON.parse(jsonString);
            //  let formattedJson = '';
            //  for (let key in jsonObject) {
            //      if (jsonObject.hasOwnProperty(key)) {
            //          formattedJson += key + ': ' + JSON.stringify(jsonObject[key]) + ',\n';
            //      }
            //  }
            //  formattedJson = formattedJson.slice(0, -2);
            // let formattedJson = '';
            //  for (let key in jsonStringify) {
            //      if (jsonStringify.hasOwnProperty(key)) {
            //          formattedJson += key + ': ' + JSON.stringify(jsonStringify[key]) + ',\n';
            //      }
            //  }
            let container = document.getElementById("demoJson");
            let options = {
                mode: "text",
                modes: ["text"],
                onEditable: function (node) {
                    switch (node.field) {
                        case "_id":
                            return false;
                        case "name":
                            return {
                                field: false,
                                value: true
                            };
                        default:
                            return true;
                    }
                },
                //   on event example
                onError: function (err) {
                    alert(err.toString());
                },
                onEvent: function (node, event) {
                    if (event.type === "click") {
                        let message = "field: " + node.field + " | path: " + node.path;
                        if (node.value) {
                            message += " | value: " + node.value;
                        }
                        console.log(message);
                        // update json field position
                        const my_clipboard = "{ x: -3755.9812, y: 140.43, z: -3287.19 }";
                        let my_room;
                        let my_item;
                        let x_pos = 0.0;
                        let y_pos = 0.0;
                        let z_pos = 0.0;
                        let i_count = 1;
                        if (node.field === "position") {
                            // get x coordinate
                            const regex = /(?<=x:\s{0,})(?<x_pos>\-{0,1}\d{0,}\.{0,1}\d{0,})|(?<=y:\s{0,})(?<y_pos>\-{0,1}\d{0,}\.{0,1}\d{0,})|(?<=z:\s{0,})(?<z_pos>\-{0,1}\d{0,}\.{0,1}\d{0,})/gm;
                            const str = my_clipboard;
                            let m;


                            while ((m = regex.exec(str)) !== null) {
                                // This is necessary to avoid infinite loops with zero-width matches
                                console.log('i_count:', i_count, '|group 0:', m[0]);
                                switch (i_count) {
                                    case 2:
                                        x_pos = Number(m[0]);
                                        console.log('x_pos:', x_pos);
                                        break;
                                    case 4:
                                        y_pos = Number(m[0]);
                                        break;
                                    case 6:
                                        z_pos = Number(m[0]);
                                        break;
                                }
                                if (m.index === regex.lastIndex) {
                                    regex.lastIndex++;
                                }
                                // The result can be accessed through the `m`-variable.
                                m.forEach((match, groupIndex) => {
                                    console.log(`Found match, group ${groupIndex}: ${match}`);
                                });
                                i_count++;
                            }

                            my_room = node.path[1];
                            my_item = node.path[3];
                            console.log("node.path[3]: ", node.path[3]);
                            json["rooms"][my_room]["items"][my_item]["position"]["x"] = x_pos;
                            json["rooms"][my_room]["items"][my_item]["position"]["y"] = y_pos;
                            json["rooms"][my_room]["items"][my_item]["position"]["z"] = z_pos;
                            editor.update(json);
                        }
                    }

                    function prettyPrintPath(path) {
                        let str = "";
                        for (let i = 0; i < path.length; i++) {
                            const element = path[i];
                            if (typeof element === "number") {
                                str += "[" + element + "]";
                            } else {
                                if (str.length > 0) str += ",";
                                str += element;
                            }
                        }
                        return str;
                    }
                }
            };
            //let demoJson = new JSONEditor(container,options,jsonString);
           // $('#jsonEditorModal .modal-body #hello').html(jsonString);
            $('#jsonEditorModal').modal('show');
        }// end -:- openJsonEditorModal()
    </script>
    <script type="text/javascript">
        const container = document.getElementById("jsoneditor");
        const options = {
            mode: "text",
            modes: ["text"],
            onEditable: function (node) {
                switch (node.field) {
                    case "_id":
                        return false;
                    case "name":
                        return {
                            field: false,
                            value: true
                        };
                    default:
                        return true;
                }
            },
            //   on event example
            onError: function (err) {
                alert(err.toString());
            },
            onEvent: function (node, event) {
                if (event.type === "click") {
                    let message = "field: " + node.field + " | path: " + node.path;
                    if (node.value) {
                        message += " | value: " + node.value;
                    }
                    console.log(message);
                    // update json field position
                    const my_clipboard = "{ x: -3755.9812, y: 140.43, z: -3287.19 }";
                    let my_room;
                    let my_item;
                    let x_pos = 0.0;
                    let y_pos = 0.0;
                    let z_pos = 0.0;
                    let i_count = 1;
                    if (node.field === "position") {
                        // get x coordinate
                        const regex = /(?<=x:\s{0,})(?<x_pos>\-{0,1}\d{0,}\.{0,1}\d{0,})|(?<=y:\s{0,})(?<y_pos>\-{0,1}\d{0,}\.{0,1}\d{0,})|(?<=z:\s{0,})(?<z_pos>\-{0,1}\d{0,}\.{0,1}\d{0,})/gm;
                        const str = my_clipboard;
                        let m;
                        // m = regex.exec(str);
                        // console.log('m:', m);
                        // todo? check if it is a string with coordinates x_pos is found

                        // // x_pos = parseFloat(m[2], 10);
                        // x_pos = Number(m[2]);
                        // console.log('regex:', m[0], '-', m[1], '-', m[2]);
                        // console.log('x_pos:', m.x_pos);

                        while ((m = regex.exec(str)) !== null) {
                            // This is necessary to avoid infinite loops with zero-width matches
                            console.log('i_count:', i_count, '|group 0:', m[0]);
                            switch (i_count) {
                                case 2:
                                    x_pos = Number(m[0]);
                                    console.log('x_pos:', x_pos);
                                    break;
                                case 4:
                                    y_pos = Number(m[0]);
                                    break;
                                case 6:
                                    z_pos = Number(m[0]);
                                    break;
                            }
                            if (m.index === regex.lastIndex) {
                                regex.lastIndex++;
                            }
                            // The result can be accessed through the `m`-variable.
                            m.forEach((match, groupIndex) => {
                                console.log(`Found match, group ${groupIndex}: ${match}`);
                            });
                            i_count++;
                        }

                        my_room = node.path[1];
                        my_item = node.path[3];
                        console.log("node.path[3]: ", node.path[3]);
                        json["rooms"][my_room]["items"][my_item]["position"]["x"] = x_pos;
                        json["rooms"][my_room]["items"][my_item]["position"]["y"] = y_pos;
                        json["rooms"][my_room]["items"][my_item]["position"]["z"] = z_pos;
                        editor.update(json);
                    }
                }

                function prettyPrintPath(path) {
                    let str = "";
                    for (let i = 0; i < path.length; i++) {
                        const element = path[i];
                        if (typeof element === "number") {
                            str += "[" + element + "]";
                        } else {
                            if (str.length > 0) str += ",";
                            str += element;
                        }
                    }
                    return str;
                }
            }
        };
        var myJson = "{{ !empty($externalService->external_service_config)?$externalService->external_service_config:'' }}";
        var replaceJson = myJson.replace(/&quot;/g, '"');
        replaceJson = replaceJson.replace(/&lt;/g, '<');
        replaceJson = replaceJson.replace(/&gt;/g, '>');

        @if(empty($externalService->external_service_config))
            replaceJson = JSON.stringify(replaceJson);
        @endif

        const jsonData = JSON.parse(replaceJson);
        const editor = new JSONEditor(container, options, jsonData);
        const textareaElement = container.querySelector('.jsoneditor-text');
        if (textareaElement) {
            textareaElement.setAttribute('name', 'external_service_config');
        }

        function jsonCompact() {
            $('.jsoneditor-compact').click();
        }//end -:- jsonCompact()
    </script>
@endsection <!--- footer script--->