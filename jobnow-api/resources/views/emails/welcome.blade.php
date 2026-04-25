@extends('emails.layout')

@section('title', 'Bienvenue sur JobNow')

@section('header-subtitle', 'Bienvenue dans la communauté JobNow')

@section('content')
    <div class="greeting">Bienvenue {{ $name }} !</div>
    
    <div class="message">
        <p>Nous sommes ravis de vous accueillir sur JobNow, la plateforme qui connecte les talents marocains avec les meilleures opportunités professionnelles.</p>
        <p>Votre compte a été créé avec succès et vous pouvez dès maintenant profiter de toutes nos fonctionnalités.</p>
    </div>

    @if($role === 'candidat')
    <div class="info-box">
        <div class="info-box-title">🚀 Commencez votre recherche d'emploi</div>
        <div class="message" style="margin: 0;">
            <ul style="color: #4b5563; line-height: 1.8; padding-left: 20px; margin: 10px 0;">
                <li>Complétez votre profil pour augmenter vos chances</li>
                <li>Parcourez des milliers d'offres d'emploi</li>
                <li>Postulez en un clic avec votre CV</li>
                <li>Sauvegardez vos offres préférées</li>
                <li>Créez des alertes emploi personnalisées</li>
            </ul>
        </div>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.frontend_url') }}/jobs" class="button">
            Découvrir les offres
        </a>
    </div>
    @else
    <div class="info-box">
        <div class="info-box-title">🎯 Trouvez les meilleurs talents</div>
        <div class="message" style="margin: 0;">
            <ul style="color: #4b5563; line-height: 1.8; padding-left: 20px; margin: 10px 0;">
                <li>Publiez vos offres d'emploi en quelques clics</li>
                <li>Recevez des candidatures qualifiées</li>
                <li>Gérez vos recrutements efficacement</li>
                <li>Planifiez des entretiens directement</li>
                <li>Accédez à des analytics détaillés</li>
            </ul>
        </div>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.frontend_url') }}/entreprise/create-job" class="button">
            Publier une offre
        </a>
    </div>
    @endif

    <div class="divider"></div>

    <div class="message" style="font-size: 14px; color: #6b7280;">
        <p><strong>Besoin d'aide ?</strong></p>
        <p>Notre équipe est là pour vous accompagner. N'hésitez pas à nous contacter si vous avez des questions.</p>
    </div>
@endsection
