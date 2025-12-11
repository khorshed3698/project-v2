<script>
    function formatDateRange(startDate, endDate) {
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        // Parse the dates
        const start = new Date(startDate);
        const end = new Date(endDate);

        // Format the start and end dates
        const startFormatted = `${monthNames[start.getMonth()]} ${start.getDate()}`;
        const endFormatted = `${monthNames[end.getMonth()]} ${end.getDate()}`;

        // Extract the year
        const year = start.getFullYear();

        return `The data covers the period from ${startFormatted} to ${endFormatted}, ${year}.`;
    }


    
    let pageLoaded = false;
    let initialLoad = true; // Flag to indicate initial load

    async function fetchData() {
        let serviceType = $('#active_tab').val();
        let month = $('#active_month').val();
        let year = $('#active_year').val();
        let startDate = '';
        let endDate = '';
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
        var currentDay = String(currentDate.getDate() - 1).padStart(2, '0');
        if (currentDate.getDate() == 1) {
            currentDay = String(currentDate.getDate()).padStart(2, '0');
        }
        var formattedDate = `${currentYear}-${currentMonth}-${currentDay} 23:59:59`;

        if (!month) {
            startDate = `${year}-01-01`;
            endDate = `${year}-12-31`;
            if (currentYear == year) {
                startDate = `${year}-01-01`;
                endDate = `${formattedDate}`;
            }

        } else {
            if ((month >= 11) && currentMonth <= 2) {
                currentYear -= 1;
            }
            startDate = `${currentYear}-${month}-01`;
            const daysInMonth = new Date(year, month, 0).getDate();
            endDate = `${currentYear}-${month}-${daysInMonth}`;
            month = String(month).padStart(2, '0');
            if (currentMonth == month) {
                endDate = `${formattedDate}`;
            }
        }

        // $('#result').html('<div class="text-center">Loading...</div>');
        const htmlDataContent = formatDateRange(startDate, endDate);
        try {
            // let token = getMetaToken();
            let token = "{{ Cache::get('insightdb_api_token') }}";
            const apiURL = "{{ config('app.insightdb_api_base_url') }}"+"{{ config('app.landing_page_public_data') }}";

            if (!token) {
                token = await TokenManager.fetchToken("/bida-oss-landing/insightdb-token");
            }
            $.ajax({
                url: apiURL,
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                data: JSON.stringify({
                    data_sets: [
                        "bida_oss_public_service_feedback",
                        "bida_oss_public_service_data"
                    ],
                    parameters: {
                        service_type: serviceType,
                        start_date: startDate,
                        end_date: endDate
                    }
                }),
                contentType: "application/json",
                success: function(response) {
                    $('#result').html(`
                    <div class="srv-data-info-box"></div>
                    <div class="srv-data-info-table">
                        <div class="srv-data-table-container">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped bidaSrvDataInfoTable">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th>Stipulated Delivery <br> Timeline</th>
                                            <th>Disposed <br> Applications</th>
                                            <th>% Within Stipulated <br> Timelines</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-collapse-sec mt-0"></div>
                `);

                    const feedbackContainer = $('.srv-data-info-box');
                    const tableBody = $('.bidaSrvDataInfoTable tbody');
                    const sourceOfDoc = $('.bottom-collapse-sec');

                    // Populate feedback section
                    if (response.data.bida_oss_public_service_feedback && response.data
                        .bida_oss_public_service_feedback.length) {
                        response.data.bida_oss_public_service_feedback.forEach(feedback => {
                            feedbackContainer.append(`
                            <div class="data-infobox-item">
                                <p>Organization</p>
                                <span class="info-num">${feedback['Organization'] || '0'}</span>
                            </div>
                            <div class="data-infobox-item">
                                <p>Service</p>
                                <span class="info-num">${feedback['Service'] || '0'}</span>
                            </div>
                            <div class="data-infobox-item">
                                <p>Number of Feedback</p>
                                <span class="info-num">${feedback['Number of Feedback'] || '0'}</span>
                            </div>
                            <div class="data-infobox-item">
                                <p>Feedback Ratings (out of 5)</p>
                                <span class="info-num">${feedback['Feedback Ratting (out of 5)'] || '0'}</span>
                            </div>
                        `);
                        });
                    } else {
                        feedbackContainer.append(`
                        <div class="data-infobox-item">
                            <p>Organization</p>
                            <span class="info-num">-</span>
                        </div>
                        <div class="data-infobox-item">
                            <p>Service</p>
                            <span class="info-num">-</span>
                        </div>
                        <div class="data-infobox-item">
                            <p>Number of Feedback</p>
                            <span class="info-num">-</span>
                        </div>
                        <div class="data-infobox-item">
                            <p>Feedback Ratings (out of 5)</p>
                            <span class="info-num">-</span>
                        </div>
                    `);
                    }

                    // Populate table data
                    if (response.data.bida_oss_public_service_data && response.data
                        .bida_oss_public_service_data.length) {
                        let prevEntity = null;
                        response.data.bida_oss_public_service_data.forEach((serviceData, index,
                            arr) => {
                            let rowspanCount = 0;
                            if (serviceData.Entity !== prevEntity) {
                                rowspanCount = arr.filter(data => data.Entity === serviceData
                                    .Entity).length;
                            }
                            const row = $('<tr></tr>');
                            const row2 = $('<tr></tr>');

                            if (serviceData.Entity !== prevEntity) {
                                row.append(`
                                <td colspan="4" class="text-start align-top">
                                    <div class="entry-title">
                                        <span class="entry-title-icon">
                                            <img src="${serviceData.logo || 'assets/default-icon.svg'}" alt="Icon" loading="lazy">
                                        </span>
                                        <span class="entry-title-text"><b>${serviceData.Entity}</b></span>
                                    </div>
                                </td>
                            `);
                                prevEntity = serviceData.Entity;
                            }

                            row2.append(`
                            <td class="text-start">${serviceData.Service} ${serviceData['Agency/Section'] ? `(${serviceData['Agency/Section']})` : ''}</td>
                            <td>${serviceData['Stipulated Delivery Timeline']}</td>
                            <td>${serviceData.Disposed}</td>
                            <td>${serviceData['% within Stipulated Timelines']}</td>
                        `);

                            tableBody.append(row);
                            tableBody.append(row2);
                        });
                    } else {
                        tableBody.append(`
                        <tr>
                            <td> 0 </td>
                            <td> 0 </td>
                            <td> 0 </td>
                            <td> 0 </td>
                        </tr>
                    `);
                    }

                    // Append the dynamic content
                    sourceOfDoc.append(`
                <p class="data-sec-covers"><em>${htmlDataContent}</em> <small class="data-sec-covers-small">Powered by: <a href="https://insightdb.ai" target="_blank" rel="noopener noreferrer" aria-label="Visit InsightDB website"><img style="width: 60px; height: auto;" src="https://insightdb.ai/wp-content/uploads/2023/09/Insightdb-Logo-white-f-1024x291.png" alt="InsightDB Unlock Better Decision" loading="lazy"></a></small></p>
                <div class="data-sec-title-parent">
                <button class="data-sec-title collapsed"
                        data-bs-toggle="collapse"
                        data-bs-target="#expandDataSec"
                        aria-expanded="false"
                        aria-controls="expandDataSec"
                        id="data-sec-title-div"
                        style="background: none; border: none; cursor: pointer;">
                    Explanation of the data calculation process
                </button>
                </div>


                                    <div id="expandDataSec" class="collapse" aria-expanded="false" aria-labelledby="expandDataSecHeading" role="region">
                    <div class="expand-data-content">
                <h6 style="font-size: 13px;">Explanation of the Data:</h6>
                        <p>
                            The BIDA OSS Rules were introduced on May 10, 2020, to ensure the timely delivery of services to investors.
                            These rules define the timeframes for delivering each service provided by relevant offices and agencies, referred
                            to as Service Level Agreements (SLA).
                        </p>

                        <ul class="mb-2">
                            <li>
                                <strong>Stipulated Delivery Timeline:</strong> The timeframe defined in the OSS Rules within which a service must be delivered. The timeline is considered working days for both application submission to processing.
                            </li>
                            <li>
                                <strong>Disposed Applications:</strong> The total number of applications fully processed through the OSS platform.
                            </li>
                        </ul>

                        <p>Once an application is submitted, the respective office or agency evaluates it and may take one of the following actions:</p>

                        <ol class="mb-2">
                            <li>
                                Approve the application.
                            </li>
                            <li>
                                Return the application to the applicant for rectification in case of any deficiencies (Shortfall).
                            </li>
                            <li>
                                Reject the application.
                            </li>
                        </ol>

                        <p>
                            Any of these actions—approval, return for rectification, or rejection—is considered a resolution of the application.
                        </p>
                <ul class="mb-2">
                <li>
                <strong>% within stipulated timelines: </strong> Indicates the percentage of applications resolved within the specified delivery timelines.
                            Time during the shortfall stage is not considered while calculating the delivery timeline.
                </li>
                </ul>

                    </div>
                </div>
                `);
                    // Reinitialize Bootstrap collapse component
                    new bootstrap.Collapse(document.getElementById('expandDataSec'), {
                        toggle: false
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    
                    console.log(errorMessage);
                    // $('#result').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                },
            });
        } 
        catch (error) {
            console.error("Token fetch failed:", error);
        }
    }

    // function getMetaToken() {
    //     const metaTag = document.querySelector('meta[name="insightDB-token"]');
    //     if (metaTag && metaTag.getAttribute('content')) {
    //         return metaTag.getAttribute('content');
    //     }
    //     return null;
    // }

    $(document).ready(async function() {
        $(document).on('click', '.public-service-item-click', function() {
            $('#ossPSPD_servTab').addClass('active');
            $('#ossPSPD_servTab').addClass('show');
            // value set for active tab
            let active_tab = $(this).attr('data-active-tab');
            // set the value of the hidden input field
            $('#active_tab').val(active_tab);

            let bgColor = '';
            // Set colors based on active_tab value
            switch (active_tab) {
                case '1':
                    bgColor = '#855297';
                    break;
                case '2':
                    bgColor = '#9B1A44';
                    break;
                case '3':
                    bgColor = '#B52624';
                    break;
                case '4':
                    bgColor = '#C9A036';
                    break;
                case '5':
                    bgColor = '#81AB35';
                    break;
                default:
                    bgColor = '#855297'; // Default color
                    break;
            }

            // Apply the background color dynamically
            $('#ossPSPD_servTab .srv-data-filter-menu, .srv-data-filter-menu.sdf-menu-style-3')
                .css('background-color', bgColor);

            // Need to check
            setTimeout(() => {
                fetchData();
            }, 50);
        });

        $(document).on('click', '.month-click', function() {
            let active_month = $(this).attr('data-active-month');
            // set the value of the hidden input field
            $('#active_month').val(active_month);
            $('#active_year').val('');
            $('[data-active-month]').each(function() {
                var target = $(this).data('active-month');
                if (target == active_month) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });

            $('.year-click').removeClass('active');

            fetchData();

        });

        $(document).on('click', '.year-click', function() {
            let active_year = $(this).attr('data-active-year');
            // set the value of the hidden input field
            $('#active_month').val('');
            $('#active_year').val(active_year);
            $('[data-active-year]').each(function() {
                var target = $(this).data('active-year');
                if (target == active_year) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });

            $('.month-click').removeClass('active');

            fetchData();
        });

        TokenManager.initializeTokenRefresh("/bida-oss-landing/insightdb-token", 270000);
        await TokenManager.fetchToken("/bida-oss-landing/insightdb-token");
        $('.public-service-item-click').eq(1).trigger('click');
        initialLoad = false;

    });

    window.addEventListener('load', () => {
        pageLoaded = true;
    });

    document.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('public-service-item-click') || e.target.classList.contains('srv-menu-text'))) {
            if (initialLoad) {
                return; // Prevent scroll on initial load
            }
            if (!pageLoaded) {
                return;
            }
            e.preventDefault();
            let panel = document.querySelector('.oss-serv-tab-content');
            if (panel) {
                panel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
</script>
