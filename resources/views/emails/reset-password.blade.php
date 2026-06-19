<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="light only">
<title>Reset Password</title>
</head>
<body style="margin:0; padding:0; background-color:#EEF1F4; font-family:'Segoe UI', Helvetica, Arial, sans-serif; color:#343434; -webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EEF1F4;">
        <tr>
            <td align="center" style="padding:32px 16px;">

                <table role="presentation" width="560" cellpadding="0" cellspacing="0"
                       style="width:560px; max-width:100%; background-color:#ffffff; border:1px solid #E6E9ED; border-radius:14px; overflow:hidden;">

                    {{-- Aksen merah tipis di atas --}}
                    <tr><td style="height:5px; background-color:#E03A3E; font-size:0; line-height:0;">&nbsp;</td></tr>

                    {{-- Header: logo Direktorat --}}
                    <tr>
                        <td align="center" style="padding:30px 36px 22px 36px; border-bottom:1px solid #EEF0F3;">
                            <img src="{{ $message->embed(public_path('img/logo-direktorat.png')) }}"
                                 alt="Direktorat Kemahasiswaan, Karier dan Alumni"
                                 height="44" style="height:44px; width:auto; display:block;">
                        </td>
                    </tr>

                    {{-- Isi --}}
                    <tr>
                        <td style="padding:32px 36px 8px 36px;">
                            <p style="margin:0 0 6px 0; font-size:18px; color:#1A1A1A;">
                                Halo, <strong>{{ $name }}</strong>!
                            </p>
                            <p style="margin:0 0 22px 0; font-size:14px; line-height:1.7; color:#555;">
                                Kami menerima permintaan untuk mereset kata sandi akun DITMAWA Telkom University kamu.
                                Klik tombol di bawah ini untuk membuat kata sandi baru.
                            </p>

                            {{-- Tombol CTA --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:4px 0 26px 0;">
                                        <a href="{{ $url }}" target="_blank"
                                           style="display:inline-block; background-color:#E03A3E; color:#ffffff; font-size:15px; font-weight:600; text-decoration:none; padding:14px 34px; border-radius:10px;">
                                            Reset Kata Sandi
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 18px 0; font-size:13px; line-height:1.7; color:#777;">
                                Tautan ini akan kedaluwarsa dalam {{ $count }} menit.
                                Jika kamu tidak meminta reset kata sandi, abaikan saja email ini.
                                Akun kamu tetap aman.
                            </p>

                            {{-- Fallback URL --}}
                            <p style="margin:0 0 6px 0; font-size:12px; color:#999;">
                                Tombol tidak berfungsi? Salin dan tempel tautan berikut di browser kamu:
                            </p>
                            <p style="margin:0 0 26px 0; font-size:12px; line-height:1.6; word-break:break-all;">
                                <a href="{{ $url }}" style="color:#E03A3E; text-decoration:none;">{{ $url }}</a>
                            </p>

                            <div style="border-top:1px solid #EEF0F3; padding-top:18px;">
                                <p style="margin:0; font-size:14px; color:#555;">Salam hangat,</p>
                                <p style="margin:2px 0 0 0; font-size:14px; color:#343434;">
                                    Tim Direktorat Kemahasiswaan, Karier dan Alumni
                                </p>
                                <p style="margin:1px 0 0 0; font-size:13px; color:#888;">Telkom University</p>
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#F7F8FA; padding:18px 36px; border-top:1px solid #EEF0F3;">
                            <p style="margin:0; font-size:11px; line-height:1.6; color:#9AA0A6;">
                                Email ini dikirim otomatis oleh sistem dokumentasi kegiatan mahasiswa. Mohon tidak membalas email ini.
                            </p>
                            <p style="margin:6px 0 0 0; font-size:11px; color:#9AA0A6;">
                                &copy; {{ date('Y') }} Direktorat Kemahasiswaan, Karier dan Alumni &middot; Telkom University
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
