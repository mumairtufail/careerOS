<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['personal']['full_name'] ?? 'Resume' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Calibri', 'Segoe UI', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.35;
            color: #1a1a1a;
            padding: 28px 32px;
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #1e3a5f;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .name {
            font-size: 22pt;
            font-weight: 700;
            color: #1e3a5f;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .title {
            font-size: 11pt;
            color: #4a5568;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .contact {
            font-size: 8.5pt;
            color: #4a5568;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 4px 16px;
        }
        .contact-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .contact-item::before {
            content: '';
        }
        .section {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 10.5pt;
            font-weight: 700;
            color: #1e3a5f;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding-left: 2px;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .summary {
            color: #374151;
            line-height: 1.5;
            font-size: 9pt;
        }
        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .skill-tag {
            background: #f1f5f9;
            color: #334155;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 8.5pt;
        }
        .exp-item {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .exp-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .exp-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        .exp-title {
            font-weight: 700;
            color: #1e3a5f;
            font-size: 9.5pt;
        }
        .exp-company {
            color: #4a5568;
            font-weight: 500;
        }
        .exp-date {
            color: #64748b;
            font-size: 8.5pt;
            white-space: nowrap;
        }
        .exp-location {
            color: #64748b;
            font-size: 8.5pt;
            margin-bottom: 5px;
        }
        .exp-desc {
            white-space: pre-wrap;
            line-height: 1.5;
            color: #374151;
            font-size: 8.5pt;
        }
        .edu-item {
            margin-bottom: 10px;
        }
        .edu-item:last-child {
            margin-bottom: 0;
        }
        a {
            color: #2563eb;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="name">{{ $data['personal']['full_name'] ?? '' }}</div>
        <div class="title">{{ $data['personal']['professional_title'] ?? '' }}</div>
        <div class="contact">
            @if(!empty($data['personal']['email']))
                <span class="contact-item">{{ $data['personal']['email'] }}</span>
            @endif
            @if(!empty($data['personal']['phone']))
                <span class="contact-item">{{ $data['personal']['phone'] }}</span>
            @endif
            @if(!empty($data['personal']['city']))
                <span class="contact-item">{{ $data['personal']['city'] }}@if(!empty($data['personal']['state_country'])), {{ $data['personal']['state_country'] }}@endif</span>
            @endif
            @if(!empty($data['personal']['linkedin_url']))
                <span class="contact-item"><a href="{{ $data['personal']['linkedin_url'] }}">LinkedIn</a></span>
            @endif
            @if(!empty($data['personal']['portfolio_url']))
                <span class="contact-item"><a href="{{ $data['personal']['portfolio_url'] }}">Portfolio</a></span>
            @endif
        </div>
    </div>

    <!-- Professional Summary -->
    @if(!empty($data['personal']['professional_summary']))
    <div class="section">
        <div class="section-title">Professional Summary</div>
        <div class="content summary">{{ $data['personal']['professional_summary'] }}</div>
    </div>
    @endif

    <!-- Skills -->
    @if(!empty($data['skills_text']))
    <div class="section">
        <div class="section-title">Core Competencies</div>
        <div class="content">{{ $data['skills_text'] }}</div>
    </div>
    @endif

    <!-- Experience -->
    @if(!empty($data['experience']) && count($data['experience']) > 0)
    <div class="section">
        <div class="section-title">Professional Experience</div>
        <div class="content">
            @foreach($data['experience'] as $exp)
                <div class="exp-item">
                    <div class="exp-header">
                        <div>
                            <span class="exp-title">{{ $exp['position'] ?? '' }}</span>
                            @if(!empty($exp['company']))
                                <span class="exp-company"> | {{ $exp['company'] }}</span>
                            @endif
                        </div>
                        <span class="exp-date">
                            @if(!empty($exp['start_month']) && !empty($exp['start_year']))
                                {{ date('M Y', strtotime($exp['start_year'] . '-' . $exp['start_month'] . '-01')) }}
                            @elseif(!empty($exp['start_year']))
                                {{ $exp['start_year'] }}
                            @endif
                            —
                            @if(!empty($exp['currently_working']) && $exp['currently_working'])
                                Present
                            @elseif(!empty($exp['end_month']) && !empty($exp['end_year']))
                                {{ date('M Y', strtotime($exp['end_year'] . '-' . $exp['end_month'] . '-01')) }}
                            @elseif(!empty($exp['end_year']))
                                {{ $exp['end_year'] }}
                            @endif
                        </span>
                    </div>
                    @if(!empty($exp['location']))
                        <div class="exp-location">{{ $exp['location'] }}</div>
                    @endif
                    @if(!empty($exp['description']))
                        <div class="exp-desc">{{ $exp['description'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Education -->
    @if(!empty($data['education']) && count($data['education']) > 0)
    <div class="section">
        <div class="section-title">Education</div>
        <div class="content">
            @foreach($data['education'] as $edu)
                <div class="edu-item">
                    <div class="exp-header">
                        <div>
                            <span class="exp-title">{{ $edu['degree'] ?? '' }}@if(!empty($edu['field'])) in {{ $edu['field'] }}@endif</span>
                        </div>
                        <span class="exp-date">
                            @if(!empty($edu['graduation_month']) && !empty($edu['graduation_year']))
                                {{ date('M Y', strtotime($edu['graduation_year'] . '-' . $edu['graduation_month'] . '-01')) }}
                            @elseif(!empty($edu['graduation_year']))
                                {{ $edu['graduation_year'] }}
                            @endif
                        </span>
                    </div>
                    @if(!empty($edu['institution']))
                        <div class="exp-location">{{ $edu['institution'] }}@if(!empty($edu['location'])), {{ $edu['location'] }}@endif</div>
                    @endif
                    @if(!empty($edu['gpa']))
                        <div style="color: #64748b; font-size: 8.5pt;">GPA: {{ $edu['gpa'] }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Certifications -->
    @if(!empty($data['certifications_text']))
    <div class="section">
        <div class="section-title">Certifications</div>
        <div class="content">{{ $data['certifications_text'] }}</div>
    </div>
    @endif

    <!-- Projects -->
    @if(!empty($data['projects_text']))
    <div class="section">
        <div class="section-title">Projects</div>
        <div class="content">{{ $data['projects_text'] }}</div>
    </div>
    @endif
</body>
</html>
