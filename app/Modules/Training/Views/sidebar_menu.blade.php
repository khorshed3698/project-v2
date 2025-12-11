{{-- @if (checkUserPermissionTraining()) --}}
{{-- <ul> --}}
    {{-- <li> --}}
        {{-- <a class="@if (Request::is('training/*')) active @endif nav-link" href="{{ url('training/list') }}">
            <div class="nav-link-icon">
                <img alt="Work Permit" src="{{ url('assets/fonts_svg/work_permit.svg') }}">
            </div>
            Training
            <div class="nav-link-arrow">
                <span class="fa arrow"></span>
            </div>
        </a> --}}
        {{-- <ul class="nav nav-second-level"> --}}

            {{-- @if (checkTrainee())
                <li>
                    <a class="nav-link @if (Request::is('training/upcoming-course') ||
                            Request::is('training/upcoming-course/*') ||
                            Request::is('training/course-details/*')) active @endif"
                        href="{{ url('training/upcoming-course') }}">
                        <div class="nav-link-icon">
                            <i class="far fa-file-video"></i>
                        </div>
                        Upcoming Course
                    </a>
                </li>
                <li>
                    <a class="nav-link @if (Request::is('training/purchase-course') ||
                            Request::is('training/purchase-course/*') ||
                            Request::is('training/purchase-course/*')) active @endif"
                        href="{{ url('training/purchase-course') }}">
                        <div class="nav-link-icon">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        Purchsed Course
                    </a>
                </li>
            @endif --}}

            {{-- @if (checkUserTrainingDesk())
                <li>
                    <a class="nav-link @if (Request::is('training/schedule/list') || Request::is('training/schedule/list/*')) active @endif"
                        href="{{ url('training/schedule/list') }}">
                        <div class="nav-link-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        Training Schedule
                    </a>
                </li>
                @if (ACL::getAccsessRight('Training-Desk', 'A'))
                    <li>
                        <a class="nav-link @if (Request::is('training/attendance/create') || Request::is('training/attendance/create/*')) active @endif"
                            href="{{ url('training/attendance/create') }}">
                            <div class="nav-link-icon">
                                <i class="far fa-clipboard"></i>
                            </div>
                            Attendance
                        </a>
                    </li>
                    <li>
                        <a class="nav-link @if (Request::is('training/evaluation/create') || Request::is('training/evaluation/create/*')) active @endif"
                            href="{{ url('training/evaluation/create') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-marker"></i>
                            </div>
                            Marking
                        </a>
                    </li>
                    <li>
                        <a class="nav-link @if (Request::is('training/notification/add-notification') || Request::is('training/notification/add-notification/*')) active @endif"
                            href="{{ url('training/notification/add-notification') }}">
                            <div class="nav-link-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            Notifications
                        </a>
                    </li>
                @endif

            @endif --}}
            {{-- super admin --}}
            {{-- @if ($type[0] == '1')
                <li>
                    <a class="nav-link @if (Request::is('training/category-list') ||
                            Request::is('training/category-list/*') ||
                            Request::is('training/create-category')) active @endif"
                        href="{{ url('training/category-list') }}">
                        <div class="nav-link-icon">
                            <i class="far fa-folder-open"></i>
                        </div>
                        Category
                    </a>
                </li>
                <li>
                    <a class="nav-link @if (Request::is('training/course-list') || Request::is('training/course-list/*')) active @endif"
                        href="{{ url('training/course-list') }}">
                        <div class="nav-link-icon">
                            <i class="far fa-file-video"></i>
                        </div>
                        Course
                    </a>
                </li>
            @endif --}}
        {{-- </ul> --}}
    {{-- </li> --}}
{{-- </ul> --}}
{{-- @endif --}}
