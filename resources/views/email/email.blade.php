<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Akademik</title>
</head>

<body style="margin:0;padding:0;background:#f7f9fb;font-family:'Segoe UI',Arial,sans-serif;">
    <table width="100%" bgcolor="#f7f9fb" cellpadding="0" cellspacing="0" style="padding:32px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px;background:#fff;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.04);overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background:#fff;padding:20px 32px 16px 32px;border-bottom:1px solid #e5eaf2;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="width:100%;">
                                <tr>
                                    <td style="width:48px;vertical-align:middle;">
                                        <img src="https://drive.google.com/uc?export=view&id=1xVuGWBiXXC6eM09-ACPx-y-5cqOj3yXb"
                                            alt="Logo" width="40">
                                    </td>
                                    <td style="text-align:center;vertical-align:middle;">
                                        <span
                                            style="font-size:1.15rem;color:#222;font-weight:600;letter-spacing:0.5px;">Politeknik
                                            Enjinering Indorama</span>
                                    </td>
                                    <td style="width:90px;text-align:right;vertical-align:middle;">
                                        <img src="https://drive.google.com/uc?export=view&id=1dK9JdIpX8XCyUMpqw10axIUBPo9cGiRD"
                                            alt="Dikti" width="32"
                                            style="display:inline-block;margin-right:8px;vertical-align:middle;">
                                        <img src="https://drive.google.com/uc?export=view&id=1ShkWODw6rr6yEInrOcfIakTuQT2aNGWy"
                                            alt="Diktisaintek Berdampak" width="40"
                                            style="display:inline-block;vertical-align:middle;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- End Header -->
                    <tr>
                        <td style="padding:32px 32px 16px 32px;">
                            <h3
                                style="color:#222;margin-top:0;margin-bottom:16px;font-size:1.1rem;font-weight:600;letter-spacing:0.2px;">
                                Notifikasi Akademik untuk Orang Tua/Wali</h3>
                            <div style="color:#222;font-size:1rem;line-height:1.7;margin-bottom:24px;">
                                {!! nl2br(e($pesan)) !!}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 32px 32px;">
                            <div style="color:#555;font-size:0.97rem;margin-bottom:8px;">Salam hormat,</div>
                            <div style="color:#222;font-weight:600;font-size:1rem;">
                                Tim Akademik {{ isset($prodi) ? $prodi : '...' }} Politeknik Enjinering Indorama
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background:#f7f9fb;padding:16px 32px;text-align:center;color:#888;font-size:0.93rem;border-top:1px solid #e5eaf2;">
                            Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background:#f7f9fb;padding:12px 32px 28px 32px;text-align:center;color:#888;font-size:0.97rem;">
                            <div style="margin-bottom:4px;">
                                <a href="https://pei.ac.id"
                                    style="color:#0d6efd;text-decoration:none;font-weight:500;">pei.ac.id</a>
                            </div>
                            <div style="margin-bottom:4px;">Jl. Kembangkuning, Ubrug, Jatiluhur, Purwakarta, Jawa Barat,
                                Indonesia 41152</div>
                            <div style="margin-top:8px;">
                                <span style="color:#0d6efd;font-weight:500;">Kontak WhatsApp:</span> <a
                                    href="https://wa.me/6281381926992" style="color:#222;text-decoration:none;">+62
                                    813-8192-6992</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
