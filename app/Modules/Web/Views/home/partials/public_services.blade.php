<section class="bida-pspd-sec bida-section">
    <div class="container">
        <div class="text-center py-3">
            <h2 class="mb-2">Public Service Performance Data</h2>
        </div>

        <div class="bida-pspd-content">
            <div class="oss-public-service-top">
                <div class="oss-psrv-tabmenu">
                    <div class="oss-srv-center-text">
                        <span>Services<br> Under OSS</span>
                    </div>
                    <div id="ossServiceTabmenu" class="oss-serv-menu nav-tabs nav">
                        <input type="hidden" name="active_tab" id="active_tab" value="2">
                        <div class="oss-serv-menu-item srv-menu-01">
                            <div class="ossServ-tabText-item nav-link public-service-item-click" data-active-tab="1" data-bs-target="#ossPSPD_servTab" data-bs-toggle="tab" aria-controls="ossPSPD_servTab" role="tab" aria-expanded="true">
                                <span class="srv-menu-text">Start Business</span>
                            </div>
                        </div>

                        <div class="oss-serv-menu-item srv-menu-02">
                            <div class="ossServ-tabText-item nav-link public-service-item-click" data-active-tab="2" data-bs-target="#ossPSPD_servTab" data-bs-toggle="tab" aria-controls="ossPSPD_servTab" role="tab" aria-expanded="true">
                                <span class="srv-menu-text">Registration, <br>License, <br>Certificate and <br>Permits</span>
                            </div>
                        </div>

                        <div class="oss-serv-menu-item srv-menu-03">
                            <div class="ossServ-tabText-item nav-link public-service-item-click" data-active-tab="3" data-bs-target="#ossPSPD_servTab" data-bs-toggle="tab" aria-controls="ossPSPD_servTab" role="tab" aria-expanded="true">
                                <span class="srv-menu-text">Utility<br> services</span>
                            </div>
                        </div>

                        <div class="oss-serv-menu-item srv-menu-04">
                            <div class="ossServ-tabText-item nav-link public-service-item-click" data-active-tab="4" data-bs-target="#ossPSPD_servTab" data-bs-toggle="tab" aria-controls="ossPSPD_servTab" role="tab" aria-expanded="true">
                                <span class="srv-menu-text">Financial <br>services</span>
                            </div>
                        </div>

                        <div class="oss-serv-menu-item srv-menu-05">
                            <div class="ossServ-tabText-item nav-link public-service-item-click" data-active-tab="5" data-bs-target="#ossPSPD_servTab" data-bs-toggle="tab" aria-controls="ossPSPD_servTab" role="tab" aria-expanded="true">
                                <span class="srv-menu-text">Chamber,<br> associations and <br>other services</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                use Carbon\Carbon;

                // Create a single instance of the current date
                $currentMonth = Carbon::now()->startOfMonth();
                $previousMonth1 = Carbon::instance($currentMonth)->subMonth();
                $previousMonth2 = Carbon::instance($currentMonth)->subMonths(2);

                // Retrieve month numbers and names
                $currentMonthNumber = $currentMonth->month;
                $previousMonth1Number = $previousMonth1->month;
                $previousMonth2Number = $previousMonth2->month;

                $currentMonthName = $currentMonth->format('F');
                $previousMonth1Name = $previousMonth1->format('F');
                $previousMonth2Name = $previousMonth2->format('F');

                // Years
                $year = $currentMonth->year;
                $previousYear1 = $year - 1;
                $previousYear2 = $year - 2;
                
                // $currentDate = Carbon::create(2025, 4, 01); // Testing Purpose
                $currentDate = Carbon::now();
                $ninetyDaysAgo = $currentDate->subDays(90);
                $active_year = $ninetyDaysAgo->year;

            ?>

            <div class="oss-serv-tab-content">
                <div class="tab-content">
                    <div class="tab-pane fade" id="ossPSPD_servTab">
                        <div class="serviceTabContent-data">
                            <div class="srv-data-filter-menu sdf-menu-style-2">
                                <ul>
                                    <input type="hidden" name="active_month" id="active_month" value="">
                                    <li><button class="src-filter-btn month-click" data-active-month="{{ $currentMonthNumber }}">{{ $currentMonthName }}</button></li>
                                    <li><button class="src-filter-btn month-click" data-active-month="{{ $previousMonth1Number }}">{{ $previousMonth1Name }}</button></li>
                                    <li><button class="src-filter-btn month-click" data-active-month="{{ $previousMonth2Number }}">{{ $previousMonth2Name }}</button></li>

                                    <input type="hidden" name="active_year" id="active_year" value="{{ $active_year }}">
                                    <li><button class="src-filter-btn year-click {{ $year == $active_year ? 'active' : '' }}" data-active-year="{{ $year }}">{{ $year }}</button></li>
                                    <li><button class="src-filter-btn year-click {{ $previousYear1 == $active_year ? 'active' : '' }}" data-active-year="{{ $previousYear1 }}">{{ $previousYear1 }}</button></li>
                                    <li><button class="src-filter-btn year-click" data-active-year="{{ $previousYear2 }}">{{ $previousYear2 }}</button></li>
                                </ul>
                            </div>

                            <div id="result" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ossPSPD_servTab_02">
                        {{-- <h2>2. OSS Public Service Registration, License, Certificate and Permits</h2> --}}
                    </div>
                    <div class="tab-pane fade" id="ossPSPD_servTab_03">
                        {{-- <h2>3. OSS Public Service Utility services</h2> --}}
                    </div>
                    <div class="tab-pane fade" id="ossPSPD_servTab_04">
                        {{-- <h2>4. OSS Public Service Financial services</h2> --}}
                    </div>
                    <div class="tab-pane fade" id="ossPSPD_servTab_05">
                        {{-- <h2>5. OSS Public Service Chamber, associations and other services</h2> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>