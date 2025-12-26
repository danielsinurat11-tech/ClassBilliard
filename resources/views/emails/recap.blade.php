<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Laporan</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: #fff; margin: 0; font-size: 24px;">Rekapitulasi Laporan</h1>
        <p style="color: #fff; margin: 10px 0 0 0; opacity: 0.9;">Billiard Class</p>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb;">
        <p style="margin: 0 0 20px 0; font-size: 16px;">Halo,</p>
        
        <p style="margin: 0 0 20px 0;">Berikut adalah rekapitulasi laporan untuk periode:</p>
        
        <div style="background: #fff; padding: 20px; border-radius: 8px; border-left: 4px solid #8b5cf6; margin: 20px 0;">
            <p style="margin: 0; font-size: 18px; font-weight: bold; color: #8b5cf6;">{{ $reportPeriod }}</p>
        </div>
        
        <p style="margin: 20px 0;">File Excel rekapitulasi terlampir dalam email ini. Silakan buka file tersebut untuk melihat detail lengkap.</p>
        
        <div style="background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #e5e7eb;">
            <p style="margin: 0 0 10px 0; font-weight: bold; color: #374151;">Catatan:</p>
            <ul style="margin: 0; padding-left: 20px; color: #6b7280;">
                <li>File terlampir dalam format Excel (.xlsx)</li>
                <li>Pastikan email client Anda mendukung attachment</li>
                <li>Jika tidak melihat attachment, cek folder Spam</li>
            </ul>
        </div>
        
        <p style="margin: 30px 0 0 0; color: #6b7280; font-size: 14px;">Terima kasih,<br><strong>Billiard Class</strong></p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #9ca3af; font-size: 12px;">
        <p style="margin: 0;">Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
    </div>
</body>
</html>

