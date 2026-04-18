<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convocation à un entretien</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #09090b;
            color: #dcfce3;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #111827;
        }
        .details-box {
            background-color: #f9fafb;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .details-box p {
            margin: 10px 0;
            font-size: 16px;
        }
        .details-box strong {
            color: #374151;
            display: inline-block;
            width: 120px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: #09090b;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Convocation à un entretien</h1>
        </div>
        <div class="content">
            <p class="greeting">Bonjour {{ $candidateName }},</p>
            <p>Félicitations ! Nous sommes ravis de vous informer que votre candidature pour le poste de <strong>{{ $jobTitle }}</strong> a retenu notre attention. Nous souhaitons vous inviter à un entretien.</p>
            
            <div class="details-box">
                <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($interviewDate)->format('d/m/Y') }}</p>
                <p><strong>Heure :</strong> {{ $interviewTime }}</p>
                <p><strong>Lieu / Lien :</strong> <br/> {{ $location }}</p>
            </div>

            <p>Veuillez confirmer votre présence en répondant directement à cet e-mail. Si vous avez un empêchement, merci de nous prévenir au plus tôt.</p>
            
            <p style="margin-top: 30px;">Cordialement,<br>L'équipe Recrutement</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} JobyNow. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
