@section('styles')
    <style>
        .error{
            border:1px solid red !important;
        }

        .required-text {
            position: absolute;
            margin-top: 10px;
            left: 12px;
            width: fit-content;
        }

    </style>
@endsection

<section id="ossPContactSection" class="ossp-contact-sec" style="background-image: url({{asset('assets/landingV2/assets/frontend/images/home/bida-contact-sec-bg.webp);')}}">
    <div class="container">
        <div class="ossp-contact-box">
            <div class="contact-title">
                <h2>Contact Us</h2>
            </div>

            <div class="contact-content">
                <div class="contact-desc">
                    <div class="env-gif">
                        <img src="{{asset('assets/landingV2/assets/frontend/images/contact/envalop.webp')}}" width="240" height="240" alt="Icon BIDA OSS Contact" loading="lazy">
                    </div>

                    <div class="ossp-contact-info">
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                    <circle cx="13.1535" cy="13.3024" r="12.5602" stroke="#0F6849" stroke-width="0.866221"/>
                                    <path d="M8.15908 12.3436L8.15908 12.3435C8.16076 11.0508 8.72781 9.80683 9.74348 8.88602C10.7598 7.96467 12.1415 7.44339 13.5865 7.44179C15.0315 7.44339 16.4132 7.96467 17.4295 8.88602C18.4452 9.80683 19.0122 11.0508 19.0139 12.3435V12.3436C19.0156 13.3993 18.6354 14.4289 17.9275 15.2743L17.9048 15.3014L17.9044 15.3024L17.9031 15.304L17.8479 15.3692L17.7888 15.4387L17.7666 15.4645L17.7593 15.473L17.757 15.4755L17.7564 15.4762L17.7563 15.4763L13.5865 19.9349L9.41725 15.4771L9.41638 15.4761C9.41637 15.4761 9.41637 15.4761 9.41637 15.4761C9.41619 15.4759 9.41548 15.4752 9.4141 15.4736L9.4069 15.4655L9.38461 15.4397C9.36714 15.4193 9.34595 15.3943 9.32531 15.3699L9.27015 15.3044L9.25252 15.2834L9.24765 15.2776L9.24638 15.2761L9.24606 15.2757L9.24599 15.2757C9.24598 15.2756 9.24598 15.2756 9.24597 15.2756C8.53776 14.4298 8.15737 13.3998 8.15908 12.3436ZM15.8369 12.3436V12.3433C15.8369 11.9313 15.7021 11.5305 15.4523 11.1916C15.2027 10.853 14.8502 10.5918 14.4414 10.4383C14.0326 10.2848 13.5838 10.2449 13.151 10.323C12.7182 10.401 12.3182 10.5941 12.0024 10.8804C11.6864 11.1668 11.4686 11.5343 11.3801 11.9377C11.2916 12.3412 11.3372 12.7593 11.5103 13.138C11.6831 13.5163 11.9743 13.8365 12.3438 14.0603C12.7132 14.284 13.1456 14.4025 13.5865 14.4025H13.5867C14.1772 14.4018 14.7468 14.189 15.1699 13.8055C15.5935 13.4214 15.8362 12.8962 15.8369 12.3436Z" fill="#0F6849" stroke="#0F6849" stroke-width="0.406041"/>
                                </svg>
                            </div>
                            <span class="label-title">Support</span>
                            <p>Support from home will be ensure Sunday to Thursday: 9:00am-5:00pm Friday & Saturday: Closed All Govt. Holiday: Closed</p>
                        </div>

                        <div class="contact-info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="26" viewBox="0 0 27 26" fill="none">
                                    <path d="M19.2686 19.9312C12.3408 19.9409 7.08254 14.6202 7.08985 7.75238C7.08985 7.3033 7.45366 6.93787 7.90193 6.93787H10.0458C10.4478 6.93787 10.7897 7.23346 10.849 7.63138C10.9904 8.58562 11.2679 9.51464 11.6732 10.39L11.7569 10.5703C11.8131 10.6916 11.8252 10.8287 11.7911 10.958C11.757 11.0873 11.6788 11.2006 11.5701 11.2784C10.9058 11.7527 10.6525 12.7069 11.1697 13.4508C11.821 14.3874 12.6339 15.2005 13.5703 15.8521C14.3149 16.3686 15.2691 16.1152 15.7426 15.4517C15.8203 15.3427 15.9337 15.2642 16.0632 15.23C16.1927 15.1957 16.33 15.2078 16.4515 15.2641L16.631 15.347C17.5064 15.753 18.4354 16.0307 19.3896 16.172C19.7876 16.2313 20.0832 16.5732 20.0832 16.976V19.1191C20.0832 19.2258 20.0621 19.3315 20.0213 19.43C19.9804 19.5286 19.9205 19.6182 19.845 19.6936C19.7695 19.769 19.6799 19.8288 19.5813 19.8696C19.4827 19.9103 19.377 19.9313 19.2703 19.9312H19.2686Z" fill="#0F6849"/>
                                    <circle cx="13.1535" cy="13.0014" r="12.5602" stroke="#0F6849" stroke-width="0.866221"/>
                                </svg>
                            </div>
                            <span class="label-title">Call Center</span>
                            <p><a href="tel:{{ config('app.support_contact_mobile') }}" aria-label="Call Us">{{ config('app.support_contact_mobile') }}</a></p>
                        </div>

                        <div class="contact-info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                    <path d="M18.7838 10.6503L13.5865 13.8986L8.38917 10.6503V9.35097L13.5865 12.5993L18.7838 9.35097M18.7838 8.05164H8.38917C7.66805 8.05164 7.08984 8.62984 7.08984 9.35097V17.147C7.08984 17.4916 7.22674 17.822 7.47041 18.0657C7.71408 18.3094 8.04457 18.4463 8.38917 18.4463H18.7838C19.1284 18.4463 19.4589 18.3094 19.7026 18.0657C19.9463 17.822 20.0832 17.4916 20.0832 17.147V9.35097C20.0832 9.00636 19.9463 8.67587 19.7026 8.4322C19.4589 8.18853 19.1284 8.05164 18.7838 8.05164Z" fill="#0F6849"/>
                                    <circle cx="13.1535" cy="13.2489" r="12.5602" stroke="#0F6849" stroke-width="0.866221"/>
                                </svg>
                            </div>
                            <span class="label-title">Email</span>
                            <p><a href="mailto:{{ config('app.support_contact_email') }}" aria-label="Email Us">{{ config('app.support_contact_email') }}</a></p>
                        </div>

                        {{-- <div class="contact-info-item">
                            <div class="info-icon">
                                <picture>
                                    <source srcset="{{ asset('assets/landingV2/assets/frontend/images/contact/icon-help-desk.webp') }}" type="image/webp">
                                    <source srcset="{{asset('assets/landingV2/assets/frontend/images/contact/icon-help-desk.svg')}}" type="image/jpeg">
                                    <img src="{{asset('assets/landingV2/assets/frontend/images/contact/icon-help-desk.svg')}}" width="26" height="auto" alt="Icon Help Desk BIDA OSS">
                                </picture>
                            </div>
                            <span class="label-title">Oss Help Desk</span>
                            <p><a href="mailto:{{ $getContactInfo->value3 ?: null }}">{{ $getContactInfo->value3 ?: null }}</a></p>
                        </div> --}}
                    </div>
                </div>
                <div class="contact-form">
                    {!! Form::open([
                        'method' => 'post',
                        'id' => 'form_id',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="form-group">
                        {!! Form::text('name', null, ['class' => 'form-control required', 'placeholder' => 'Name*']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::text('phone', null, ['placeholder' => 'Phone Number*', 'class' => 'form-control phone_or_mobile required', 'id' => 'contactPhoneNumber']) !!}
                        {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        {!! Form::email('email', null, ['class' => 'form-control required',  'placeholder' => 'E-mail*']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                    <button class="btn" type="button" id="nextStep">Next</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


    <div id="step2Div"></div>
    

</section>