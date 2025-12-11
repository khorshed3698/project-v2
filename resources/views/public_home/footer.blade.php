<footer>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="footer_top">
                    <div id="row">
                        <div class="col-md-4">
                            <div class="footer_menu text-left">
                                <p class="footer_title">About</p>
                                <ul>
                                    <li><a href="{{ url('articles/bida') }}">About BIDA</a></li>
                                    <li><a href="{{ url('articles/one-stop-service') }}">About One Stop Service</a></li>
                                    <li><a href="{{ url('articles/about-osspid') }}">About OSSPID</a></li>
                                    <li><a href="{{ url('articles/about-bida-quick-service-portal') }}">About Quick Service Portal</a></li>
                                    <li><a href="{{ url('articles/support') }}">Contact Us</a></li>
                                    <li><a href="{{ url('bida/training-list') }}">Training</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="footer_menu text-left">
                                <p class="footer_title footer_title_nd">Resources</p>
                                <ul>
                                    <li>
                                        <a href="{{ url('articles/available-services') }}">
                                            Available Online Services
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('articles/business-sector') }}">
                                            Business Sector/  National Industrial Classification
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('articles/certificate-issuing-agency-bd') }}">
                                            Certificate/ License/ Permit Issuing Agency (CLPIA) in Bangladesh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('articles/investment-promotion-agency-bd') }}">
                                            Investment Promotion Agency (IPA) in Bangladesh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('articles/document-and-downloads') }}">
                                            Necessary Resources
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('articles/utility-service-provider') }}">
                                            List of Utility Service Provider
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="footer_menu footer_top_3rd">
                                <p class="footer_title footer_title_nd">Others</p>
                                <ul>
                                    <li><a href="{{ url('articles/privacy-statement') }}">Privacy Statement</a></li>
                                    <li><a href="{{ url('articles/terms-of-services') }}">Terms of Services</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="footer_bottom">
                    <p class="company-info">
                        Managed by
                        <a href="http://bida.gov.bd" target="_blank" rel="noopener">Bangladesh Investment Development Authority (BIDA)</a>
                    </p>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <div id="footer_bottom">
                    <p class="company-info">
                        Managed by
                        <a href="{{ config('app.managed_by_url') }}" target="_blank" rel="noopener">{{ config('app.managed_by') }}</a>
                        On behalf of
                        <a href="http://bida.gov.bd" target="_blank" rel="noopener">Bangladesh Investment Development Authority (BIDA)</a>
                    </p>
                </div>
            </div>
        </div> --}}
    </div>
</footer>