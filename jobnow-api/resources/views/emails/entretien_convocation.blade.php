@extends('emails.layout')

@section('title', 'Convocation à un entretien - JobNow')

@section('header-subtitle', 'Convocation à un entretien')

@section('content')
    <div class="greeting">Félicitations {{ $candidatName }} !</div>
    
    <div class="message">
        <p>Nous avons le plaisir de vous informer que votre candidature pour le poste de <strong>{{ $jobTitle }}</strong> chez <strong>{{ $companyName }}</strong> a retenu notre attention.</p>
        <p>Nous souhaitons vous rencontrer pour un entretien d'embauche.</p>
    </div>

    <div class="info-box">
        <div class="info-box-title">📅 Détails de l'entretien</div>
        <div class="info-item">
            <span class="info-label">Date :</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Heure :</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($date)->format('H:i') }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Lieu :</span>
            <span class="info-value">{{ $lieu }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Entreprise :</span>
            <span class="info-value">{{ $companyName }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Poste :</span>
            <span class="info-value">{{ $jobTitle }}</span>
        </div>
    </div>

    @if($message)
    <div class="message">
        <p><strong>Message du recruteur :</strong></p>
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 6px; font-style: italic; color: #4b5563;">
            "{{ $message }}"
        </div>
    </div>
    @endif

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.frontend_url') }}/candidat/applications" class="button">
            Voir ma candidature
        </a>
    </div>

    <div class="divider"></div>

    <div class="message" style="font-size: 14px;">
        <p><strong>Conseils pour votre entretien :</strong></p>
        <ul style="color: #6b7280; line-height: 1.8; padding-left: 20px;">
            <li>Arrivez 10 minutes en avance</li>
            <li>Préparez des questions sur l'entreprise et le poste</li>
            <li>Apportez une copie de votre CV</li>
            <li>Habillez-vous de manière professionnelle</li>
        </ul>
    </div>

    <div class="message" style="font-size: 13px; color: #9ca3af; margin-top: 20px;">
        <p>En cas d'empêchement, veuillez contacter l'entreprise dans les plus brefs délais.</p>
    </div>
@endsection
