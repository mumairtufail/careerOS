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
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .name {
            font-size: 26pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }
        .title {
            font-size: 14pt;
            color: #64748b;
            margin-bottom: 10px;
        }
        .contact {
            font-size: 10pt;
            color: #475569;
        }
        .contact span {
            margin: 0 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 2px solid #e0e7ff;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            margin-left: 15px;
            white-space: pre-wrap;
        }
        .summary {
            font-style: italic;
            color: #475569;
            line-height: 1.6;
        }
        a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="name">{{ $data['personal']['full_name'] ?? '' }}</div>
        <div class="title">{{ $data['personal']['professional_title'] ?? '' }}</div>
        <div class="contact">
            <span>{{ $data['personal']['email'] ?? '' }}</span>
            @if(!empty($data['personal']['phone']))
                <span>|</span>
                <span>{{ $data['personal']['phone'] }}</span>
            @endif
            @if(!empty($data['personal']['city']))
                <span>|</span>
                <span>{{ $data['personal']['city'] }}@if(!empty($data['personal']['state_country'])), {{ $data['personal']['state_country'] }}@endif</span>
            @endif
        </div>
        @if(!empty($data['personal']['linkedin_url']) || !empty($data['personal']['portfolio_url']))
            <div class="contact" style="margin-top: 5px;">
                @if(!empty($data['personal']['linkedin_url']))
                    <span><a href="{{ $data['personal']['linkedin_url'] }}">LinkedIn</a></span>
                @endif
                @if(!empty($data['personal']['portfolio_url']))
                    <span>|</span>
                    <span><a href="{{ $data['personal']['portfolio_url'] }}">Portfolio</a></span>
                @endif
            </div>
        @endif
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
        <div class="section-title">Skills</div>
        <div class="content">{{ $data['skills_text'] }}</div>
    </div>
    @endif

    <!-- Experience -->
    @if(!empty($data['experience']) && count($data['experience']) > 0)
    <div class="section">
        <div class="section-title">Professional Experience</div>
        <div class="content">
            @foreach($data['experience'] as $exp)
                <div style="margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                        <strong style="color: #1e40af;">{{ $exp['position'] ?? '' }}{{ !empty($exp['company']) ? ' • ' . $exp['company'] : '' }}</strong>
                        <span style="color: #64748b;">
                            @if(!empty($exp['start_month']) && !empty($exp['start_year']))
                                {{ date('M Y', strtotime($exp['start_year'] . '-' . $exp['start_month'] . '-01')) }}
                            @elseif(!empty($exp['start_year']))
                                {{ $exp['start_year'] }}
                            @endif
                            - 
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
                        <div style="color: #64748b; font-size: 10pt; margin-bottom: 5px;">{{ $exp['location'] }}</div>
                    @endif
                    @if(!empty($exp['description']))
                        <div style="white-space: pre-wrap; line-height: 1.6; color: #475569;">{{ $exp['description'] }}</div>
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
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 3px;">
                        <strong style="color: #1e40af;">{{ $edu['degree'] ?? '' }}{{ !empty($edu['field']) ? ' in ' . $edu['field'] : '' }}</strong>
                        <span style="color: #64748b;">
                            @if(!empty($edu['graduation_month']) && !empty($edu['graduation_year']))
                                {{ date('M Y', strtotime($edu['graduation_year'] . '-' . $edu['graduation_month'] . '-01')) }}
                            @elseif(!empty($edu['graduation_year']))
                                {{ $edu['graduation_year'] }}
                            @endif
                        </span>
                    </div>
                    @if(!empty($edu['institution']))
                        <div style="color: #64748b;">{{ $edu['institution'] }}{{ !empty($edu['location']) ? ', ' . $edu['location'] : '' }}</div>
                    @endif
                    @if(!empty($edu['gpa']))
                        <div style="color: #64748b; font-size: 10pt;">GPA: {{ $edu['gpa'] }}</div>
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
