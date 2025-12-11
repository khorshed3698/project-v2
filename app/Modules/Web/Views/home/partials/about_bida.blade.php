<section id="bidaAboutSec" class="bg-white bida-section">
    <div class="container">
        <article class="row">
            <div class="col-lg-4">
                <div class="bida-flex-center">
                    <div class="bida-sec-img res-center">
                        <picture>
                            <source
                                srcset="{{ asset('assets/landingV2/assets/frontend/images/home/bida-about-img.webp') }}"
                                type="image/webp">
                            <source
                                srcset="{{ asset('assets/landingV2/assets/frontend/images/home/bida-about-img.jpg') }}"
                                type="image/jpeg">
                            <img class="img-fluid img-radious"
                                src="{{ asset('assets/landingV2/assets/frontend/images/home/bida-about-img.webp') }}"
                                width="420" height="auto" alt="BIDA OSS About" loading="lazy">
                        </picture>
                    </div>
                </div>
            </div>
            <article class="col-lg-8">
                <div class="bida-flex-center sec-pad-left">
                    <h2 class="pt-lg-0 pt-3">About BIDA</h2>
                    <div class="bida-sec-desc" style="padding: 20px 0px 0px 0px;">
                        <p>The Bangladesh Investment Development Authority (BIDA) is the apex government
                            agency responsible for promoting and facilitating both domestic and foreign
                            investment in Bangladesh. Established in 2016 through the merger of the Board of
                            Investment (BOI) and the Privatization Commission, BIDA operates under the Chief
                            Adviser's Office. Its strategic role is pivotal in driving the nation's economic growth
                            and achieving long-term investment and development goals.
                        </p>

                        <p style=" margin-top: 17px;"><b>Key Objectives and Mandates</b></p>
                        <ol style="margin-bottom: 0px;">
                            <li>
                                Investment Promotion
                                <p>BIDA works to position Bangladesh as a leading investment destination by
                                    showcasing the country's advantages, including its strategic location, competitive
                                    workforce, and rapidly expanding domestic market</p>
                            </li>
                            
                            <li>
                                Facilitation of Investment
                                <p>Through a one-stop service (OSS) platform, BIDA streamlines business processes
                                    by offering simplified procedures for business setup, licensing, and regulatory
                                    approvals.</p>
                            </li>
                            
                            <li>
                                Policy Advocacy
                                <p>BIDA shapes and advocates for investment-friendly policies, aiming to eliminate
                                    bureaucratic hurdles and improve the ease of doing business in Bangladesh.</p>
                            </li>
                            
                            <li>
                                Private Sector Development
                                <p>By promoting public-private partnerships (PPP)
                                    and fostering entrepreneurship,
                                    BIDA contributes significantly to enhancing private-sector-led growth.</p>
                            </li>
                            
                        </ol>

                        <a class="text-primary mb-3" data-bs-toggle="collapse" href="#additionalInfo" role="button"
                            aria-expanded="false" aria-controls="additionalInfo" style="cursor: pointer; font-weight: 300;"
                            onclick="document.getElementById('additionalInfo').classList.add('show'); this.style.display='none';">
                            Read More
                        </a>
                    </div>
                </div>
            </article>
            <article class="col-lg-12">
                <div class="collapse" id="additionalInfo">
                    <div >
                        <div class="bida-sec-desc" style="padding: 5px 0px 0px 0px;">
                            <p style=" margin-top: 17px;"><b>Core Services and Functions</b></p>
                            <p>BIDA provides a wide range of services to investors at different stages of their
                                investment journey:</p>
                            <ul>
                                <li>Pre-Investment Counseling: Guidance for potential investors, offering detailed
                                    information on investment opportunities and regulatory frameworks.</li>
                                <li>Project Registration and Approval: Facilitates the registration and approval of
                                    domestic, foreign, and joint-venture industrial projects.</li>
                                <li>Establishment Support: Assists in setting up branch, liaison, or representative
                                    offices.</li>
                                <li>Visa and Work Permit Facilitation: Issues recommendations for visas and work
                                    permits for foreign nationals.</li>
                                <li>Utility Connections and Industrial Plots: Helps investors secure utility connections
                                    (electricity, gas, water, telecom) and industrial plots</li>
                                <li>Royalty and Technical Assistance Approvals: Approves remittances related to
                                    royalties, technical know-how, and assistance fees.</li>
                                <li>Capital Machinery and Raw Material Facilitation: Eases the import of capital
                                    machinery and raw materials.</li>
                                <li>Foreign Loans and Supplier Credit: Approves foreign loans, supplier credits, and
                                    PAYE schemes</li>
                                <li>Aftercare Services: Offers ongoing support to ensure the smooth operation of
                                    investments.</li>
                            </ul>
                            <p>Please visit BIDA website <a href="http://bida.gov.bd"
                                    target="_blank">http://bida.gov.bd</a> to learn further information</p>
                        </div>
                    </div>
                </div>
            </article>
        </article>
    </div>
</section>
