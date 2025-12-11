<?php
$user_type = Auth::user()->user_type;
$office_id = Auth::user()->office_ids;
$type = explode('x', $user_type);
$icon = ['code-fork', 'ticket', 'database', 'object-group', 'asterisk', 'building-o', 'comments', 'shield', 'folder-open']
?>

<div class="container-fluid">
    <style>
        .radio_label {
            cursor: pointer;
        }

        .small-box {
            margin-bottom: 0;
            cursor: pointer;
        }

        @media (min-width: 481px) {
            .g_name {
                font-size: 29px;
                height: 98px;
                overflow: auto;
            }
        }

        @media (max-width: 480px) {
            .g_name {
                font-size: 18px;
            }

            span {
                font-size: 14px;
            }

            label {
                font-size: 14px;
            }
        }

        @media (min-width: 767px) {
            .has_border {
                border-left: 1px solid lightgrey;
            }

            .has_border_right {
                border-right: 1px solid lightgrey;
            }
        }

        .card-counter {
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            background-color: #fff;
            height: 100px;
            border-radius: 5px;
            transition: .3s linear all;
        }

        .card-counter:hover {
            box-shadow: 4px 4px 20px #DADADA;
            transition: .3s linear all;
        }

        .card-counter.primary {
            background-color: #007bff;
            color: #FFF;
        }

        .card-counter.danger {
            background-color: #ef5350;
            color: #FFF;
        }

        .card-counter.success {
            background-color: #66bb6a;
            color: #FFF;
        }

        .card-counter.info {
            background-color: #26c6da;
            color: #FFF;
        }

        .card-counter i {
            font-size: 5em;
            opacity: 0.2;
        }

        .card-counter .count-numbers {
            position: absolute;
            right: 35px;
            top: 20px;
            font-size: 32px;
            display: block;
        }

        .card-counter .count-name {
            position: absolute;
            right: 35px;
            top: 56px;
            /*font-style: italic;*/
            text-transform: capitalize;
            opacity: 0.7;
            display: block;
            font-size: 18px;
            max-width: 180px;
        }
    </style>
    <br>
    @include('Training::partials.dashboard')
</div>

<script type="text/javascript" src="{{ asset("assets/plugins/jquery/jquery.min.js") }}"></script>
<script type="text/javascript">
</script>
