<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['personal']['full_name'] ?? 'Resume' }}</title>
    <style>
        @page { margin: 0; size: auto; }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #111;
            padding: 40px 50px;
            max-width: 850px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            margin-bottom: 24px;
        }
        .name {
            font-size: 24pt;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
            color: #000;
        }
        .title {
            font-size: 11pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .contact-line {
            font-size: 10pt;
            color: #111;
            margin-bottom: 4px;
        }
        .contact-line span + span::before {
            content: "|";
            margin: 0 6px;
            color: #999;
        }
        .contact-links {
            font-size: 10pt;
            color: #111;
        }
        .contact-links a {
            color: #111;
            text-decoration: none;
            font-weight: 600;
        }
        .contact-links span + span::before {
            content: "|";
            margin: 0 6px;
            color: #999;
        }
        
        .section {
            margin-bottom: 20px;
        }
        .section-header {
            border-bottom: 2px solid #000;
            margin-bottom: 12px;
            padding-bottom: 4px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: 800;
            text-transform: uppercase;
            color: #000;
        }
        
        /* Experience */
        .exp-item {
            margin-bottom: 14px;
        }
        .exp-row-1 {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 2px;
        }
        .exp-title-company {
            font-size: 10.5pt;
        }
        .exp-position {
            font-weight: 800;
            color: #000;
        }
        .exp-company {
            font-weight: 600;
            color: #222;
        }
        .exp-date {
            font-size: 10pt;
            font-weight: 600;
            text-align: right;
            color: #000;
        }
        .exp-desc-list {
            margin-top: 4px;
            padding-left: 18px;
        }
        .exp-desc-list li {
            margin-bottom: 3px;
            font-size: 10pt;
            line-height: 1.4;
        }
        
        /* Summary & Skills */
        .summary-text {
            font-size: 10pt;
            line-height: 1.5;
            text-align: justify;
        }
        
        /* Education */
        .edu-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .edu-main {
            font-weight: 700;
            font-size: 10.5pt;
        }
        .edu-sub {
            font-weight: 400;
            font-size: 10pt;
        }
        .edu-date {
            font-weight: 600;
            font-size: 10pt;
        }

        a:hover {
            text-decoration: underline;
        }
        
        @media print {
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="name">{{ $data['personal']['full_name'] }}</div>
        <div class="title">{{ $data['personal']['professional_title'] }}</div>
        
        <div class="contact-line">
            @if($data['personal']['email'])<span>{{ $data['personal']['email'] }}</span>@endif
            @if($data['personal']['phone'])<span>{{ $data['personal']['phone'] }}</span>@endif
            @if($data['personal']['city'] || $data['personal']['state_country'])
                <span>{{ $data['personal']['city'] }}{{ ($data['personal']['city'] && $data['personal']['state_country']) ? ', ' : '' }}{{ $data['personal']['state_country'] }}</span>
            @endif
        </div>
        
        @if(!empty($data['personal']['linkedin_url']) || !empty($data['personal']['portfolio_url']))
        <div class="contact-line contact-links">
            @if(!empty($data['personal']['linkedin_url']))
                <span><a href="{{ $data['personal']['linkedin_url'] }}">LinkedIn</a></span>
            @endif
            @if(!empty($data['personal']['portfolio_url']))
                <span><a href="{{ $data['personal']['portfolio_url'] }}">Portfolio</a></span>
            @endif
        </div>
        @endif
    </div>

    @if(!empty($data['personal']['professional_summary']))
    <div class="section">
        <div class="section-header"><div class="section-title">Summary</div></div>
        <div class="summary-text">{{ $data['personal']['professional_summary'] }}</div>
    </div>
    @endif

    @if(!empty($data['experience']))
    <div class="section">
        <div class="section-header"><div class="section-title">Professional Experience</div></div>
        @foreach($data['experience'] as $exp)
        <div class="exp-item">
            <div class="exp-row-1">
                <div class="exp-title-company">
                    <span class="exp-position">{{ $exp['position'] }}</span>, 
                    <span class="exp-company">{{ $exp['company'] }}</span>
                </div>
                <div class="exp-date">
                    {{ $exp['start_month'] }} {{ $exp['start_year'] }} - 
                    @if($exp['currently_working']) Present 
                    @else {{ $exp['end_month'] }} {{ $exp['end_year'] }} @endif
                </div>
            </div>
            @if(isset($exp['description']))
                <ul class="exp-desc-list">
                @foreach(explode("\n", $exp['description']) as $line)
                    @if(trim($line)) <li>{{ trim($line) }}</li> @endif
                @endforeach
                </ul>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    @if(!empty($data['education']))
    <div class="section">
        <div class="section-header"><div class="section-title">Education</div></div>
        @foreach($data['education'] as $edu)
        <div class="edu-item">
            <div>
                <div class="edu-main">{{ $edu['degree'] }}</div>
                <div class="edu-sub">{{ $edu['institution'] }}, {{ $edu['location'] ?? '' }}</div>
            </div>
            <div class="edu-date">{{ $edu['graduation_month'] ?? '' }} {{ $edu['graduation_year'] }}</div>
        </div>
        @endforeach
    </div>
    @endif

     @if(!empty($data['skills_text']))
    <div class="section">
        <div class="section-header"><div class="section-title">Skills & Technologies</div></div>
        <div class="summary-text">{{ $data['skills_text'] }}</div>
    </div>
    @endif
</body>
</html>