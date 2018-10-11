<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        .pt-10 { padding-top: 10px; }
        .pt-20 { padding-top: 20px; }
        .p-5 { padding: 5px; }
        .p-10 { padding: 10px; }
        .p-20 { padding: 20px; }
        .small-text { font-size:0.9em; }
        .tiny-text { font-size:0.7em; }
        .supertiny-text { font-size:0.5em; }
        .horizontal-padding-20 { padding: 0px 20px; }
        .m-10 { margin: 10px }
        .center { text-align: center }
        .italic { font-style: italic; }
        .button { background: #f29737; color: #fff; padding: 5px 30px; font-size:1em; }
        .border-orangogo { border: 2px solid #f29737;}
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        .text-monospace {
            font-family: courier;
            font-size: 0.8em;
        }
    </style>

    @component('mail::header', ['url' => config('app.url')])
        <div class="center">
            <img style="max-height:50px; width:auto;margin:10px;" src="https://demo.orangogo.bitboss.it/images/orangogo.png">
            <!--img style="max-height:50px; width:auto" src="{{ $_SERVER['APP_URL'] }}/images/orangogo.png"-->
        </div>
    @endcomponent
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0">

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        {{ Illuminate\Mail\Markdown::parse($slot) }}

                                        {{ $subcopy ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}
    @endcomponent
</body>
</html>
