<?php
    $invalidEmail = \App\Libraries\UtilFunction::invalidEmailRegex(Auth::user()->user_email);
?>
@if($invalidEmail && Auth::user()->user_status == 'active')
    <div class="alert btn-danger" role="alert" id="invalid-email-message">
        <strong>আপনার ইমেইলটি সঠিক নয়। আপনি ইমেইল নোটিফিকেশন পাবেন না। Your email address is invalid. You won't receive email notifications.</strong>
        <a href="/articles/about-osspid" target="_blank">Learn more</a>
    </div>
@endif
