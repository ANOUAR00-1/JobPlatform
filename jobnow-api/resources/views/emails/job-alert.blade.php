<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelles offres d'emploi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .job-card {
            background-color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            border-left: 4px solid #3b82f6;
        }
        .job-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .job-company {
            color: #6b7280;
            margin-bottom: 8px;
        }
        .job-details {
            font-size: 14px;
            color: #4b5563;
        }
        .job-link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>JobNow</h1>
        <p>Nouvelles offres correspondant à vos critères</p>
    </div>
    
    <div class="content">
        <p>Bonjour,</p>
        <p>Nous avons trouvé {{ count($jobs) }} nouvelle(s) offre(s) d'emploi correspondant à vos critères :</p>
        
        @foreach($jobs as $job)
        <div class="job-card">
            <div class="job-title">{{ $job->titre }}</div>
            <div class="job-company">{{ $job->entreprise->raison_social ?? 'Entreprise' }}</div>
            <div class="job-details">
                <strong>Type:</strong> {{ $job->type_contrat }} | 
                <strong>Lieu:</strong> {{ $job->ville->nom ?? 'Non spécifié' }}
                @if($job->salaire)
                | <strong>Salaire:</strong> {{ $job->salaire }}
                @endif
            </div>
            <a href="{{ config('app.frontend_url') }}/jobs/{{ $job->id }}" class="job-link">Voir l'offre</a>
        </div>
        @endforeach
        
        <p style="margin-top: 20px;">
            <a href="{{ config('app.frontend_url') }}/candidat/job-alerts" style="color: #3b82f6;">Gérer mes alertes</a>
        </p>
    </div>
    
    <div class="footer">
        <p>Vous recevez cet email car vous avez configuré une alerte emploi sur JobNow.</p>
        <p>© {{ date('Y') }} JobNow. Tous droits réservés.</p>
    </div>
</body>
</html>
