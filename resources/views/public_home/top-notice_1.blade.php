<?php
    $currentPath = Request::path();
    $hideNotice = strpos($currentPath, 'signup/') !== false;
?>
@if (!$hideNotice)
    <section id="bidaNoticeContent" class="site-popup-sec alert-warning collapse fade">
        <span class="bidaNoticeCloseBtn" data-bs-toggle="collapse" data-bs-target="#bidaNoticeContent"
            style="z-index: 9999;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 20 20" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M5.6698 -0.000976562H14.3398C17.7298 -0.000976562 19.9998 2.37902 19.9998 5.91902V14.09C19.9998 17.62 17.7298 19.999 14.3398 19.999H5.6698C2.2798 19.999 -0.000198364 17.62 -0.000198364 14.09V5.91902C-0.000198364 2.37902 2.2798 -0.000976562 5.6698 -0.000976562ZM13.0098 12.999C13.3498 12.66 13.3498 12.11 13.0098 11.77L11.2298 9.99002L13.0098 8.20902C13.3498 7.87002 13.3498 7.31002 13.0098 6.97002C12.6698 6.62902 12.1198 6.62902 11.7698 6.97002L9.9998 8.74902L8.2198 6.97002C7.8698 6.62902 7.3198 6.62902 6.9798 6.97002C6.6398 7.31002 6.6398 7.87002 6.9798 8.20902L8.7598 9.99002L6.9798 11.76C6.6398 12.11 6.6398 12.66 6.9798 12.999C7.1498 13.169 7.3798 13.26 7.5998 13.26C7.8298 13.26 8.0498 13.169 8.2198 12.999L9.9998 11.23L11.7798 12.999C11.9498 13.18 12.1698 13.26 12.3898 13.26C12.6198 13.26 12.8398 13.169 13.0098 12.999Z"
                    fill="#FF013E"></path>
            </svg>
        </span>
        <div class="container">
            <div class="animated-container animated fadeInDown">
                <div class="notice-content">
                </div>
            </div>
        </div>
    </section>

    <script>
        async function fetchInsightDbNotice() {
            try {
                return makeInsightDbNoticeApiCall();
            } catch (error) {
                console.error("Token fetch failed:", error);
            }
        }

        function getMetaToken() {
            const metaTag = document.querySelector('meta[name="insightDB-token"]');
            if (metaTag && metaTag.getAttribute('content')) {
                return metaTag.getAttribute('content');
            }
            return null;
        }

        async function makeInsightDbNoticeApiCall() {
            try {
                $.ajax({
                    url: '/bida_oss_public_notice',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            updateNoticeUI(response);
                        } else {
                            console.log('Notice not found');
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            } catch (error) {
                console.error('Insight DB Notice API Error:', error);
            }
        }

        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }

        function updateNoticeUI(noticeData) {
            if (noticeData && noticeData.data && noticeData.data.length > 0) {
                const notice = noticeData.data[0];
                $('.notice-content').html(decodeHtml(notice.details));
                $('#bidaNoticeContent').removeClass('collapse').addClass('show');
            } else {
                $('#bidaNoticeContent').hide();
            }
        }

        window.onload = async function() {
            fetchInsightDbNotice();
        };
    </script>
@endif