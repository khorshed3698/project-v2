function countDownTimer(connect_msg, msg, time) {

    $('#loading').html(
        '<Span class="alert alert-success" id="loding-msg" style="text-align: center"><i class="fa fa-spinner fa-spin"></i>' +
        '<span id="loding-msg-text">' + connect_msg + '</span></Span>' +
        '<div id="loding-time">' +
        '<div class="countdown"></div>' +
        '</div>'
    );

    $("#loding-time").css({
        "position": "absolute",
        "color": "#f0ad4e",
        "margin-top": "16%",
        "left": "340px",
        "font-size": "24px",
        "font-weight": "bold",
        "width": "45%",
        "z-index": "600",
        "padding": "20px 10px",
        "text-align": "center",
        "background-color": " #dff0d8",
        "border": " 1px solid transparent",
        "border-radius": "4px",
    });
    $(".countdown").css({
        "border": "2px dashed #f0ad4e",
        "border-radius": "4px",
    });
    $("#loding-msg").css({
        "position": "absolute",
        "margin-top": "10%",
        "left": "340px",
        "font-size": "24px",
        "font-weight": "bold",
        "width": "45%",
        "z-index": "600",
        "padding": "20px 10px 20px 10px",
    });

    var timer2 = time;
    var t = timer2.split(':');
    //convert to micro second
    var sec = (parseInt(t[0]) * 60) + parseInt(t[1]) + '000';

    var interval = setInterval(function () {
        var timer = timer2.split(':');
        //by parsing integer, I avoid all extra string processing
        var minutes = parseInt(timer[0], 10);
        var seconds = parseInt(timer[1], 10);
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        if (minutes < 0) clearInterval(interval);
        seconds = (seconds < 0) ? 59 : seconds;
        seconds = (seconds < 10) ? '0' + seconds : seconds;
        //minutes = (minutes < 10) ?  minutes : minutes;
        if (minutes == 0) {
            $('.countdown').html(msg + '<br/>' + seconds + ' Seconds');
        } else {
            $('.countdown').html(msg + '<br/>' + minutes + ' Minutes' + ' ' + seconds + ' Seconds');
        }
        timer2 = minutes + ':' + seconds;
        console.log(timer2);
    }, 1000);
    setTimeout(function () {
        location.reload();
    }, sec);

}