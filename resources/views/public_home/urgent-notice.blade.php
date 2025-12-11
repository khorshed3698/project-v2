<style>
    .modal-dialog {
        width: max-content;
        margin: 0px !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
    }

    .modal-content {
        background-color: white;
        padding: 12px;
        border-radius: 5px;
        width: max-content;
        height: max-content;
    }

    .modal-inner-content {
        margin: auto;
        border-radius: 5px;
        text-align: center;
        position: relative;
        width: 800px;
        height: 450px;
        background: url("{{ url('/assets/images/modal-image.jpeg') }}") no-repeat;
        background-size: cover;
    }


    .modal-body {
        color: white;
        position: absolute;
        top: 52%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
        width: 100%;
        font-size: 16px;
        font-weight: 200;
        line-height: 25px;
        text-align: justify;
        padding: 0px 30px;
    }

    .modal-inner-content::after {
        content: "";
        background: linear-gradient(180deg, rgba(15, 104, 73, 0.95) 14%, rgba(216, 45, 39, 0.95) 100%);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 99;
        width: 100%;
        height: 100%;
        overflow: hidden;
        border-radius: 5px;
    }

    .btn-close {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 15px;
        height: 15px;
        background-color: red;
        border: none;
        cursor: pointer;
        border-radius: 50%;
        z-index: 999;
        color: white;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-content {
        z-index: 999;
        color: white;
        position: absolute;
        top: 16%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;

    }

    .header-content h1 {
        font-size: 30px;
        font-weight: 500;
        line-height: 23.78px;
        text-align: center;
        margin-top: 10px;
    }

    .header-content img {
        width: 45px;
        gap: 0px;
        opacity: 0px;
    }

    img {
        max-width: 100%;
    }

    .modal-last {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 999;
        width: 100%;
        text-align: center;
    }

    .modal-last img {
        width: 140px;
    }

    @media only screen and (max-width: 840px) {
        .modal-inner-content {
            width: 90vw;
            height: 60vh;
        }

        .header-content {
            width: max-content;
        }
    }

    @media only screen and (max-width: 635px) {
        .modal-inner-content {
            height: 70vh;
        }
    }

    @media only screen and (max-width: 500px) {
        .modal-inner-content {
            height: 80vh;
        }
        .header-content {
            top: 12%;
        }
    }

    @media only screen and (max-width: 400px) {
        .modal-inner-content {
            height: 90vh;
        }

        .header-content {
            top: 10%;
        }
    }

    @media (max-width: 376px) and (max-height: 844px) {
        .modal-body{
            font-size: 12px !important;
        }
    }
</style>

<div class="row">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-inner-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa fa-times"></i></button>
                    <div class="header-content">
                        <img src="{{ asset('/assets/images/announcement.png') }}" alt="">
                        <h1> জরুরী বিজ্ঞপ্তি</h1>
                    </div>
                    <div class="modal-body">
                        <p>
                            বাংলাদেশ বিনিয়োগ উন্নয়ন কর্তৃপক্ষের ওএসএস পোর্টালে দাখিলকৃত ভিসা সুপারিশ, নতুন কর্মানুমতি,
                            কর্মানুমতির মেয়াদ বৃদ্ধি ও কর্মানুমতিপত্র সংশোধনের আবেদনসমূহ যথাযথ যাচাই-বাছাইয়ের লক্ষ্যে
                            শিল্প
                            প্রতিষ্ঠানের তথ্যসমূহ অধিকতর সঠিকভাবে যাচাই এবং বিডা কর্তৃক নিবন্ধিত সকল শিল্প প্রতিষ্ঠানের
                            একটি
                            সমৃদ্ধ হালনাগাদ ডাটাবেজ তৈরির লক্ষ্যে বিডা ওএসএস চালু হওয়ার পূর্বে বিডা কর্তৃক নিবন্ধিত শিল্প
                            প্রতিষ্ঠানের নিবন্ধন সংক্রান্ত তথ্য আবশ্যিকভাবে ওএসএস পোর্টালে হালনাগাদ করতে হবে (অর্থাৎ বিডা
                            কর্তৃক সেবাসমূহ গ্রহণকালে ওএসএস পোর্টালের মাধ্যমে হালনাগাদকৃত শিল্প নিবন্ধনপত্রের সংশোধনী
                            কপি
                            আগামী ১৭.১০.২০২৪ খ্রি. তারিখের মধ্যে দাখিল করতে হবে)
                        </p>
                        <p>
                            এমতাবস্থায়, বিডা হতে ভিসা সুপারিশ, নতুন কর্মানুমতি, কর্মানুমতির মেয়াদ বৃদ্ধি ও
                            কর্মানুমতিপত্র
                            সংশোধনী-সংক্রান্ত সেবাসমূহ গ্রহণের ক্ষেত্রে ওএসএস সিস্টেম থেকে প্রাপ্ত অনলাইন নিবন্ধন
                            সংশোধনের
                            কপি বাধ্যতামূলকভাবে দাখিল করতে হবে
                        </p>
                    </div>
                    <div class="modal-last">
                        <img src="{{ asset('/assets/images/bida-trans.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#myModal').modal('show');
        }, 1000);

        $('.btn-close').click(function() {
            $('#myModal').modal('hide');
        });
    });
</script>
