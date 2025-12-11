<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
</head>

<body>
    <div class="content">
        <div class="col-md-12">
            <div style="padding:20px;">
                <div>
                    <div class="text-center"
                        style="font-size:70px; text-align: center; margin-top: 60px; font-family: 'solaimanlipi', sans-serif;"><b>Certificate</b>
                    </div>
                    <div style="margin:40px 0px;">
                        <table aria-label="Detailed Certificate" width="100%" style="margin-bottom: 10px;">
                            <thead>
                                <tr  class="d-none">
                                    <th aria-hidden="true" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <thead>
                                    <tr  class="d-none">
                                        <th aria-hidden="true" scope="col"></th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td width="25%" style="padding: 0">
                                        <p>Date: {{ \Carbon\Carbon::parse($course->course_duration_end)->format('jS F Y') }} </p>
                                    </td>
                                    <td width="75%" style="padding: 0; text-align: right">
                                        <p>Certificate No.
                                            {{ $certificate_no }}</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <br /><br /><br />
                    <span style="font-size:16px; text-align: justify;">This is to certify that {{ $participants->full_name }} has
                        successfully
                        completed the training program titled “{{ $course->course_title }}” conducted by Bangladesh Investment Development Authority (BIDA) held on {{ \Carbon\Carbon::parse($course->course_duration_start)->format('jS F Y') }} .</span> <br /><br /><br /><br />
                </div>
                <div class="" style="margin-top: 50px;">
                    <div style="float: left;width: 25%;">
                        <div style="text-align:center">
                            <img src="{{ $md_signature }}" width="70" alt="MD Signature" /><br>
                            Jahidul Hasan<br>
                            Managing Director<br>
                            Business Automation LTD.<br><br>
                        </div>
                    </div>
                    <div style="text-align: center; ">
                        <div style="padding-left: 45%;">
                            <img src="{{ $director_signature }}" width="70" alt="Director Signature" /><br>
                            {{ $user_full_name }} <br>
                            {{ $designation }}<br>
                            Investment Evironment Services <br>
                            Bangladesh Invesment Development Authority <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
