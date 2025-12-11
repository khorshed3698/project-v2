<footer class="footer-section">
    <div class="footer-main">
        <div class="container">
            {{--Footer Top Section--}}
            <div class="footer-top-sec">
                <div class="row">
                    {{--Column 1: BIDA Logo and Technology Partner Logo--}}
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            {{--BIDA Logo--}}
                            <div class="footer-mtn-item">
                                <a href="https://bida.gov.bd" target="_blank" rel="noopener noreferrer" aria-label="Visit Bangladesh Investment Development Authority (BIDA)">
                                    <picture>
                                        <source srcset="{{ asset('assets/landingV2/assets/frontend/images/bida-footer-logo.webp') }}" type="image/webp">
                                        <source srcset="{{ asset('assets/landingV2/assets/frontend/images/bida-footer-logo.png') }}" type="image/jpeg">
                                        <img class="img-block" src="{{ asset('assets/landingV2/assets/frontend/images/bida-footer-logo.png') }}" width="174" height="66" alt="Bangladesh Investment Development Authority (BIDA)" loading="lazy">
                                    </picture>
                                </a>
                            </div>
                            {{--Technology Partner Logo--}}
                            <div class="footer-mtn-item">
                                <p>Technology Partner</p>
                                <a href="https://ba-systems.com" target="_blank" rel="noopener noreferrer" aria-label="Visit Business Automation Ltd.">
                                    <picture>
                                        <source srcset="{{ asset('assets/landingV2/assets/frontend/images/logo-ba.webp') }}" type="image/webp">
                                        <source srcset="{{ asset('assets/landingV2/assets/frontend/images/logo-ba.png') }}" type="image/jpeg">
                                        <img class="img-block" src="{{ asset('assets/landingV2/assets/frontend/images/logo-ba.png') }}" width="132" height="51" alt="Business Automation Ltd." loading="lazy">
                                    </picture>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{--Column 2: About--}}
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h3 class="footer-title">About</h3>
                            <ul class="footer-menu">
                                <li><a href="{{ route('web.aboutBida') }}">About BIDA</a></li>
                                <li><a href="{{ route('web.aboutOneStopService') }}">About One Stop Service</a></li>
                                <li><a href="{{ route('web.aboutOsspid') }}">About OSSPID</a></li>
                                <li><a href="{{ route('web.aboutQuickServicePortal') }}">About Quick Service Portal</a></li>
                            </ul>
                        </div>
                    </div>
                    {{--Column 3: Resources--}}
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h3 class="footer-title">Resources</h3>
                            <ul class="footer-menu">
                                <li><a href="{{ route('web.availableOnlineServices') }}">Available Online Services</a></li>
                                <li><a href="{{ url('articles/business-sector') }}">Business Sector/ National Industrial Classification</a></li>
                                <li><a href="{{ url('articles/certificate-issuing-agency-bd') }}">Certificate/ License/ Permit Issuing Agency (CLPIA) in Bangladesh</a></li>
                                <li><a href="{{ url('articles/investment-promotion-agency-bd') }}">Investment Promotion Agency (IPA) in Bangladesh</a></li>
                                <li><a href="{{ url('articles/document-and-downloads') }}">Necessary Resources</a></li>
                                <li><a href="{{ url('articles/utility-service-provider') }}">List of Utility Service Provider</a></li>
                            </ul>
                        </div>
                    </div>
                    {{--Column 4: Others--}}
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h3 class="footer-title">Others</h3>
                            <ul class="footer-menu">
                                <li><a href="{{ url('articles/privacy-statement') }}">Privacy Statement</a></li>
                                <li><a href="{{ url('articles/terms-of-services') }}">Terms of Services</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{--Footer Bottom Section--}}
            <div class="footer-bottom-sec">
                <div class="footer-subscribe-sec">
                    <i>
                        Developed by
                        <a href="https://ba-systems.com" target="_blank" rel="noopener noreferrer" aria-label="Visit Business Automation Ltd.">Business Automation Ltd.</a>
                    </i>
                </div>
            </div>

        </div>
    </div>
</footer>