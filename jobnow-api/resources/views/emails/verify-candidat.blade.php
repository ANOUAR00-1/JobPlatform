@extends('emails.layout')

@section('title', 'Vérification de votre email - JobNow')

@section('header-subtitle', 'Vérification de votre compte')

@section('content')
    <div class="greeting">Bienvenue sur JobNow !</div>
    
    <div class="message">
        <p>Merci de vous être inscrit sur JobNow. Pour compléter votre inscription en tant que candidat, veuillez utiliser le code de vérification à 6 chiffres ci-dessous :</p>
    </div>

    <div class="info-box" style="text-align: center; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 4px solid #3b82f6;">
        <div style="font-size: 48px; font-weight: 700; color: #1e40af; letter-spacing: 8px; font-family: 'Courier New', monospace; padding: 20px 0;">
            {{ $code }}
        </div>
        <p style="margin: 0; color: #3b82f6; font-size: 13px; font-weight: 600;">Code de vérification</p>
    </div>

    <div class="message">
        <p>Retournez sur la plateforme JobNow et entrez ce code pour activer votre compte. Ce code est valide pour une utilisation immédiate.</p>
        <p style="color: #ef4444; font-weight: 600;">⚠️ Ne partagez jamais ce code avec qui que ce soit.</p>
    </div>

    <div class="divider"></div>

    <div class="message" style="font-size: 13px; color: #9ca3af;">
        <p>Si vous n'avez pas créé de compte sur JobNow, vous pouvez ignorer cet email en toute sécurité.</p>
    </div>
@endsection
