
<i class="fa fa-lg fa-volume-up" id="speakButton" style="cursor: pointer; display: none; width: 17px;"></i>
<div id="recordButton">
    <i class="fa fa-microphone fa-lg" style="width: 17px;"></i>
</div>
<script type="text/javascript" src="{{ asset('assets/plugins/jsrsasign.min.js') }}"></script>


{{-- VOICE TO TEXT CONVERTER --}}
<script>
    var recordButton = document.getElementById("recordButton");
    var socket;
    var mediaRecorder;
    var isRecording = false;
    var uniqueKey =
        "{{ \App\Libraries\Encryption::encodeId(Auth::user()->id) . '_' . \App\Libraries\Encryption::encodeId(rand(10000, 99999)) }}";
    var CLIENT_SECRET_KEY = "{{ config('app.speech_client_secret') }}";
    var SERVER_SECRET_KEY = "{{ config('app.speech_server_secret') }}";

    function clearRemarks() {
        $("#refresh_icon").addClass('faster-spin');
        setTimeout(function() {
            $("textarea#mainInput").val("");
            $("textarea#remarks").val("");
            $("#speakButton").css('display', 'none');
            $("#reset").css('display', 'none');
            if (isRecording) {
                closeRecording();
            }
            $("#refresh_icon").removeClass('faster-spin');
        }, 200);
    }

    function encodeKeyHS256(uniqueKey) {
        var header = {
            alg: "HS256",
            typ: "JWT"
        };
        var payload = {
            key: uniqueKey,
            timestamp: Date.now()
        };

        return KJUR.jws.JWS.sign(
            "HS256",
            JSON.stringify(header),
            JSON.stringify(payload), {
                utf8: CLIENT_SECRET_KEY
            }
        );
    }

    function initializeWebSocket() {
        if (socket && socket.readyState !== WebSocket.CLOSED) {
            socket.close();
        }
        socket = new WebSocket("{{ config('app.speech_endpoint') }}");
        let table_id = "{{ $transcription_id }}";
        var textarea = $("textarea#"+table_id);
        let second_table_id = "{{ $second_transcription_id }}";
        var second_textarea = $("textarea#"+second_table_id);

        var previous_transcription = second_textarea.val() || "";

        socket.onmessage = function(event) {
            try {
                var data = JSON.parse(event.data);
                if (data.status === "success" && data.transcription && data.response_key) {
                    var decodedKey = decodeResponseKeyHS256(data.response_key);

                    if (decodedKey === uniqueKey) {
                        $("#speakButton").css('display', 'block');
                        $("#reset").css('display', 'block');
                        textarea.val(previous_transcription + data.transcription);
                        second_textarea.val(previous_transcription + data.transcription);
                    } else {
                        console.error("Key mismatch! Transcription not shown.");
                    }
                }
            } catch (error) {}
        };

        socket.onerror = function (error) {
            console.error("WebSocket error:", error);
        };

        socket.onclose = function () {
            recordButton.innerHTML = '<i class="fa fa-microphone fa-lg"></i>';
        };

        // socket.onerror = function(error) {};
        // socket.onclose = function() {
        //     recordButton.innerHTML = '<i class="fa fa-microphone fa-lg"></i>';
        // };
    }

    recordButton.addEventListener("click", function() {
        if (!isRecording) {
            isRecording = true;
            recordButton.innerHTML = '<i class="fa fa-spinner fa-spin fa-lg"></i>';

            var encodedKey = encodeKeyHS256(uniqueKey);

            if (!socket || socket.readyState === WebSocket.CLOSED) {
                initializeWebSocket();
            }

            navigator.mediaDevices.getUserMedia({
                audio: true
            }).then(function(stream) {
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: "audio/webm"
                });

                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0 && isRecording && socket.readyState === WebSocket
                        .OPEN) {
                        var reader = new FileReader();
                        reader.onloadend = function() {
                            var audioBase64 = reader.result.split(",")[1];
                            var message = JSON.stringify({
                                client_id: "{{ config('app.speech_client_id') }}",
                                key: encodedKey,
                                audio: audioBase64
                            });
                            socket.send(message);
                        };
                        reader.readAsDataURL(event.data);
                        recordButton.innerHTML = '<i class="fa fa-microphone-slash fa-lg blinking-red"></i>';
                    }
                };
                mediaRecorder.start(500);
            });
        } else {
            closeRecording();
        }
    });

    function decodeResponseKeyHS256(token) {
        try {
            var isValid = KJUR.jws.JWS.verify(token, {
                utf8: SERVER_SECRET_KEY
            }, ["HS256"]);
            if (isValid) {
                var payloadObj = KJUR.jws.JWS.readSafeJSONString(b64utoutf8(token.split(".")[1]));
                return payloadObj.key;
            } else {
                throw new Error("Invalid response key.");
            }
        } catch (error) {
            console.error("Error decoding response key:", error);
            return null;
        }
    }

    function closeRecording() {
        isRecording = false;
        recordButton.innerHTML = '<i class="fa fa-spinner fa-spin fa-lg"></i>';

        if (mediaRecorder && mediaRecorder.state !== "inactive") {
            mediaRecorder.stop();
        }

        if (socket && socket.readyState === WebSocket.OPEN) {
            socket.send(
                JSON.stringify({ key: encodeKeyHS256(uniqueKey, CLIENT_SECRET_KEY), stop: true })
            );
            socket.close();
        }
    }
</script>

{{-- TEXT TO VOICE CONVERTER --}}
<script>
    $(document).ready(function() {
        var isSpeaking = false;
        var iconInterval;
        let table_id = "{{ $second_transcription_id }}";

        $("#speakButton").click(function() {
            var text = $("#" + table_id).val().trim();
            if (!text) return;

            if (isSpeaking) {
                resetIcon();
                return;
            }

            var speech = new SpeechSynthesisUtterance(text);
            speech.lang = 'en-US';
            speech.rate = 1;
            isSpeaking = true;

            $("#speakButton").addClass("text-primary").css("color", "#0069D9");

            iconInterval = setInterval(function() {
                $("#speakButton").toggleClass("fa-volume-down fa-volume-up");
            }, 500);

            speech.onend = resetIcon;
            window.speechSynthesis.speak(speech);
        });

        function resetIcon() {
            clearInterval(iconInterval);
            $("#speakButton")
                .removeClass("text-primary fa-volume-down")
                .addClass("fa-volume-up")
                .css("color", "#0069D9");
            isSpeaking = false;
            window.speechSynthesis.cancel();
        }

        $("#" + table_id).on("input", function() {
            if ($(this).val().trim() === "") {
                $("#speakButton").css('display', 'none');
                $("#reset").css('display', 'none');
            } else {
                $("#speakButton").css('display', 'block');
                $("#reset").css('display', 'block');
            }
        });

        $('#remarks').on('input', function() {
            if ($(this).val().trim() === '') {
                $("#speakButton").css('display', 'none');
                $("#reset").css('display', 'none');
            } else {
                $("#speakButton").css('display', 'block');
                $("#reset").css('display', 'block');
            }
        });
    });
</script>
