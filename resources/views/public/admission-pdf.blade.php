<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dossier d'Information - IESCA</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0066cc;
        }
        .header h1 {
            color: #0066cc;
            font-size: 24pt;
            margin: 0;
            padding: 10px 0;
        }
        .header p {
            font-size: 12pt;
            color: #666;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background: #0066cc;
            color: white;
            padding: 10px 15px;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .subsection-title {
            color: #0066cc;
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        ul {
            margin: 10px 0;
            padding-left: 25px;
        }
        li {
            margin: 5px 0;
        }
        .info-box {
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #0066cc;
            margin: 15px 0;
        }
        .price-box {
            background: #0066cc;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .price {
            font-size: 32pt;
            font-weight: bold;
            margin: 10px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        .two-columns {
            display: table;
            width: 100%;
        }
        .column {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>{{ $subtitle }}</p>
        <p style="font-size: 10pt; color: #999;">IESCA - Institut d'Enseignement Supérieur de la Côte Africaine</p>
    </div>

    <div class="section">
        <div class="section-title">📅 Dates Importantes</div>
        <div class="two-columns">
            <div class="column">
                <div class="info-box">
                    <strong>Début des Inscriptions</strong><br>
                    {{ \Carbon\Carbon::parse($inscription_start_date)->format('d M Y') }}
                </div>
            </div>
            <div class="column">
                <div class="info-box">
                    <strong>Début des Cours</strong><br>
                    {{ \Carbon\Carbon::parse($debut_cours)->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">💰 Frais</div>
        <div class="price-box">
            <div>Frais d'Inscription</div>
            <div class="price">{{ number_format($frais_inscription, 0, ',', ' ') }} FCFA</div>
            <div style="margin-top: 15px;">
                @php
                    $fraisBonus = explode("\n", $frais_bonus ?? "Carte d'étudiant : Gratuite\nTote : Gratuite\nAssurance : Gratuite");
                @endphp
                @foreach($fraisBonus as $bonus)
                    @if(trim($bonus))
                        <small>✅ {{ trim($bonus) }}</small><br>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="info-box">
            <strong>💳 Scolarité Mensuelle</strong><br>
            <span style="font-size: 18pt; font-weight: bold;">{{ number_format($frais_mensuels, 0, ',', ' ') }} FCFA</span> par mois
        </div>
    </div>

    <div class="section">
        <div class="section-title">📋 Documents Requis</div>
        <ul>
            @php
                $documents = explode("\n", $documents);
            @endphp
            @foreach($documents as $doc)
                @if(trim($doc))
                    <li>{{ trim($doc) }}</li>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="section">
        <div class="section-title">📋 Services Disponibles</div>
        <ul>
            @php
                $services = explode("\n", $services);
            @endphp
            @foreach($services as $service)
                @if(trim($service))
                    <li>✅ {{ trim($service) }}</li>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="section">
        <div class="section-title">📚 Conditions d'Inscription</div>
        
        <div class="subsection-title">Premier Cycle</div>
        
        <strong>1ère année</strong>
        <ul>
            @php
                $l1_conditions = explode("\n", $conditions_l1);
            @endphp
            @foreach($l1_conditions as $cond)
                @if(trim($cond))
                    <li>✅ {{ trim($cond) }}</li>
                @endif
            @endforeach
        </ul>
        
        <strong>2ème année</strong>
        <ul>
            @php
                $l2_conditions = explode("\n", $conditions_l2);
            @endphp
            @foreach($l2_conditions as $cond)
                @if(trim($cond))
                    <li>✅ {{ trim($cond) }}</li>
                @endif
            @endforeach
        </ul>
        
        <strong>3ème année</strong>
        <ul>
            @php
                $l3_conditions = explode("\n", $conditions_l3);
            @endphp
            @foreach($l3_conditions as $cond)
                @if(trim($cond))
                    <li>✅ {{ trim($cond) }}</li>
                @endif
            @endforeach
        </ul>
        
        <div class="subsection-title">Deuxième Cycle</div>
        
        <strong>Master 1</strong>
        <ul>
            @php
                $m1_conditions = explode("\n", $conditions_m1);
            @endphp
            @foreach($m1_conditions as $cond)
                @if(trim($cond))
                    <li>✅ {{ trim($cond) }}</li>
                @endif
            @endforeach
        </ul>
        
        <strong>Formation continue</strong>
        <ul>
            @php
                $fc_conditions = explode("\n", $conditions_fc);
            @endphp
            @foreach($fc_conditions as $cond)
                @if(trim($cond))
                    <li>✅ {{ trim($cond) }}</li>
                @endif
            @endforeach
        </ul>
        
        <strong>Master 2</strong>
        <ul>
            @php
                $m2_conditions = explode("\n", $conditions_m2);
            @endphp
            @foreach($m2_conditions as $cond)
                @if(trim($cond))
                    <li>✅ {{ trim($cond) }}</li>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="section">
        <div class="section-title">📄 Dossier à Fournir</div>
        
        <div class="subsection-title">Premier cycle</div>
        <ul>
            @php
                $dossier_l1 = explode("\n", $dossier_l1);
            @endphp
            @foreach($dossier_l1 as $doc)
                @if(trim($doc))
                    <li>• {{ trim($doc) }}</li>
                @endif
            @endforeach
        </ul>
        
        <div class="subsection-title">Deuxième cycle</div>
        <ul>
            @php
                $dossier_m1 = explode("\n", $dossier_m1);
            @endphp
            @foreach($dossier_m1 as $doc)
                @if(trim($doc))
                    <li>• {{ trim($doc) }}</li>
                @endif
            @endforeach
        </ul>
    </div>

    @if($avantage)
    <div class="section">
        <div class="section-title">💻 Avantage Spécial</div>
        <div class="info-box">
            {!! nl2br(e($avantage)) !!}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">{{ $contact_title ?? '📞 Contact' }}</div>
        <div class="info-box">
            @if(isset($contact_text) && $contact_text)
                <p>{{ $contact_text }}</p>
            @endif
            @if($phone1)
                <strong>Téléphone:</strong> {{ $phone1 }}<br>
            @endif
            @if($phone2)
                <strong>Téléphone:</strong> {{ $phone2 }}<br>
            @endif
            @if($email)
                <strong>Email:</strong> {{ $email }}
            @endif
        </div>
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>IESCA - Institut d'Enseignement Supérieur de la Côte Africaine</p>
        <p>Pour plus d'informations, visitez notre site web ou contactez-nous directement.</p>
    </div>
</body>
</html>

