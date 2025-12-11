<?php

?>




<style>
    
</style>




<div>

</div>




<script>
    let current_url  = "{{ request()->segment(1) }}";
    let training_course_url = "{{ session('training_course_url') }}";
    console.log(current_url, training_course_url);
    if (current_url == 'dashboard' && training_course_url) {
        // ask for confirmation to continue
        var userConfirmed = confirm("Do you want to continue with training course details?");
        // if ok then redirect
        if (userConfirmed) {
            window.location.href = training_course_url;
            // return;
            // window.location.href = 'users/profileinfo';
        }
    }
</script>
