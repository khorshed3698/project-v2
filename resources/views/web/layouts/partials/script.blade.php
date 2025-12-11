<!-- Plugins -->
<script src="{{ asset('assets/scripts/token-manager-v1.js') }}"></script>
<script src="{{asset('assets/landingV2/assets/plugins/jquery/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/landingV2/assets/plugins/popper/popper.min.js')}}"></script>
<script src="{{asset('assets/landingV2/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset("assets/plugins/toastr.min.js") }}" type="text/javascript" defer></script>
{{-- <script src="https://www.google.com/recaptcha/api.js" defer></script> --}}

@stack('pluginScripts')

<script src="{{asset('assets/landingV2/assets/frontend/js/bida-custom.js')}}"></script>

<script>
    //this function for is helpful article
    function isHelpFulArticle(is_helful_status, value, slug) {
        if (slug == 1) { // 1 = available service
            var service_detail_id = value;
        }
        if (slug == 2) { // 2 = agency info
            var sub_agency_id = value;
        }

        $.ajax({
            url: "/web/is-helpful-article",
            type: 'GET',
            data: {
                is_helpful: is_helful_status,
                service_detail_id: service_detail_id,
                sub_agency_id: sub_agency_id,
                slug: slug
            },
            async: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                console.log(response);
                toastr.success("Thanks for your feedback");
                return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }

    function handleLogin(e) {

        e.preventDefault();

        const link = e.target;

        // Prevent new tab open
        if (e.ctrlKey || e.metaKey || e.button === 1) {
            alert("Opening in a new tab is disabled.");
            return;
        }

        if (link.dataset.clicked === "true") {
            return;
        }
        link.dataset.clicked = "true";

        link.style.pointerEvents = "none";
        link.style.opacity = "0.6";
        link.innerText = "Processing...";

        const redirectUrl = link.dataset.redirect;

        setTimeout(function() {
            window.location.href = redirectUrl;
        }, 1000);
    }

</script>
@if(Session::has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            swal({
                    type: 'info',
                    title: 'Oops...',
                    text: "{!! addslashes(Session::get('error')) !!}",
                    confirmButtonText: 'OK'
                });
        });
    </script>
@endif

@stack('customScripts')