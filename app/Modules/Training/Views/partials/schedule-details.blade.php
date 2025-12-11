<div class="panel panel-primary">
  <div class="panel-heading">
      <div class="pull-left" style="padding-top: 7px">
          <b> <i class="fa fa-list"></i> Training Schedule </b>
      </div>
      <div class="clearfix"></div>
  </div>
  <!-- /.panel-heading -->
  <div class="panel-body">
      <div class="col-lg-12">
          <fieldset class="scheduler-border">
              <legend class="scheduler-border">Schedule Info</legend>
              <div class="form-group">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                      <label for="reg_type_id" class="col-md-5 col-xs-5">Course Name</label>
                                      <div class="col-md-7 col-xs-7">
                                          <span>: {{ $course->course->course_title }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                      <label for="reg_type_id" class="col-md-5 col-xs-5">Batch Name</label>
                                      <div class="col-md-7 col-xs-7">
                                          <span>: {{ $course->batch->batch_name }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                      <label for="speaker_email" class="col-md-5 col-xs-5">Course Category</label>
                                      <div class="col-md-7 col-xs-7">
                                          :<span class="input_ban"> {{ $course->category->category_name }}
                                          </span>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                      <label for="speaker_mobile" class="col-md-5 col-xs-5">Total Hour</label>
                                      <div class="col-md-7 col-xs-7">
                                          :<span class="input_ban"> {{ $course->total_hours }} hr </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                      <label for="speaker_email" class="col-md-5 col-xs-5">Course Duration</label>
                                      <div class="col-md-7 col-xs-7">
                                          :<span class="input_ban"> {{ $course->duration }}
                                          </span><span>{{ $course->duration_unit }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                      <label for="speaker_mobile" class="col-md-5 col-xs-5">Total class</label>
                                      <div class="col-md-7 col-xs-7">
                                          :<span class="input_ban"> {{ $course->no_of_class }} </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                      <label for="enrolment_deadline" class="col-md-5 col-xs-5">Enrollment Deadline</label>
                                      <div class="col-md-7 col-xs-7">
                                          :<span class="input_ban"> {{ $course->enroll_deadline }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                      <label for="expected_starting_date" class="col-md-5 col-xs-5">Course Start and End
                                          time</label>
                                      <div class="col-md-7 col-xs-7">
                                          :<span class="input_ban"> {{ $course->course_duration_start }} -
                                              {{ $course->course_duration_end }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                      <label for="course_location" class="col-md-5 col-xs-5">Course Venue</label>
                                      <div class="col-md-7 col-xs-7">
                                          <span>: {{ $course->venue }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                      <label for="course_fee" class="col-md-5 col-xs-5">Course Fee</label>
                                      <div class="col-md-7 col-xs-7">
                                          : <span
                                              class="input_ban text-success">{{ $course->fees_type == 'paid' ? $course->amount : 'Free' }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6 col-xs-12">
                                      <label for="course_fee" class="col-md-5 col-xs-5">Course Marking</label>
                                      <div class="col-md-7 col-xs-7">
                                          : <span
                                              class="input_ban text-success">{{ $course->course_evaluation == 'yes' ? $course->pass_marks : 'No Marking' }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6 col-xs-12">
                                      <label for="course_fee" class="col-md-5 col-xs-5">Course Status</label>
                                      <div class="col-md-7 col-xs-7">
                                          : <span
                                              class="input_ban text-success">{{  ucfirst($course->status) }}</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </fieldset>

          <fieldset class="scheduler-border">
              <legend class="scheduler-border">Schedule Session info</legend>
              <div class="form-group">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group" style="margin-bottom: 0px;">
                              <div class="row">
                                  <div class="col-md-12 col-xs-12">
                                      <table aria-label="Detailed Schedule Session info" id="courseDetailTable" class="table table-bordered dt-responsive" cellspacing="0"
                                          width="100%" style="margin-bottom: 0px;">
                                          <thead style="background-color: #3379b77e">
                                              <tr>
                                                  <th class="text-center">Session Name</th>
                                                  <th class="text-center">Session Time</th>
                                                  <th class="text-center">Day</th>
                                                  <th class="text-center" width="15%">Application</th>
                                                  <th class="text-center" width="15%">Total Applicant </th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($course->scheduleSessions as $session)
                                              <tr>
                                                  <td>
                                                      <p class="text-center">{{ $session->session_name }}</p>
                                                  </td>
                                                  <td>
                                                      <p class="text-center">{{ $session->session_start_time }} to {{ $session->session_end_time }}</p>
                                                  </td>
                                                  <td style="width: 20%">
                                                      <p class="text-center">{{ $session->session_days }}</p>
                                                  </td>
                                                  <td>
                                                      <p class="text-center">{{ $session->applicant_limit }}</p>
                                                  </td>
                                                  <td>
                                                      <p class="text-center">{{ $session->seat_capacity == 0 ? '-' : $session->seat_capacity  }}</p>
                                                  </td>
                                                  
                                              </tr> 
                                              @endforeach
                                              
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </fieldset>

          <fieldset class="scheduler-border">
              <legend class="scheduler-border">General Info</legend>
              <div class="form-group">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-12 col-xs-12">
                                      <label for="qualifications" class="col-md-3 col-xs-3">Necessary Qualification:
                                      </label>
                                  </div>
                                  <div class="col-md-12 col-xs-12">
                                      <div class="col-md-12">
                                          <span > {!! $course->necessary_qualification_experience !!}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-12 col-xs-12">
                                      <label for="qualifications" class="col-md-3 col-xs-3">Course Goal:
                                      </label>
                                  </div>
                                  <div class="col-md-12 col-xs-12">
                                      <div class="col-md-12">
                                          <span> {!! $course->objectives !!}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-12 col-xs-12">
                                      <label for="qualifications" class="col-md-3 col-xs-3">Course Outline: </label>
                                  </div>
                                  <div class="col-md-12 col-xs-12">
                                      <div class="col-md-12">
                                          <span> {!! $course->course_contents !!}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-12 col-xs-12">
                                      <label for="qualifications" class="col-md-3 col-xs-3">Course Description: </label>
                                  </div>
                                  <div class="col-md-12 col-xs-12">
                                      <div class="col-md-12">
                                          <span> {!! $course->course->course_description !!}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-12 col-xs-12">
                                      <label for="reg_type_id" class="col-md-5 col-xs-5">Course Thumbnail: </label>
  
                                  </div>
                                  <div class="col-md-12 col-xs-12">
                                      <img src="{{ asset('uploads/training/course/' . $course->course_thumbnail_path) }}" alt="photo_default.png"
                                          class="img-responsive img-thumbnail course_image_thumbnail"
                                          id="course_thumbnail_preview"
                                          onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </fieldset>
      </div>
  </div><!-- /.box -->
  <div class="panel-footer">
      <div class="row">
          <div class="col-md-12">
              <div class="col-md-6">
                  <a class="pull-left" href="{{ url('/training/schedule/list') }}">

                      <button type="button" class="btn btn-sm btn-default"><i
                              class="fa fa-times" style="margin-right: 5px;"></i>Close</button>
                  </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </div>
              @if ($course->is_publish == 0 && Auth::user()->desk_training_id == 2 && ACL::getAccsessRight('Training-Desk', '-DE-'))
                  <div class="col-md-6">
                      <a class="pull-right"
                          href="{{ url('/training/schedule-update/' . \App\Libraries\Encryption::encodeId($course->id)) }}">
                          <button type="button" class="btn btn-sm btn-success"><i
                                  class="fas fa-check"></i>Approve</button>
                      </a>
                  </div>
              @endif

          </div>
      </div>
      <div class="clearfix"></div>
  </div>
</div>