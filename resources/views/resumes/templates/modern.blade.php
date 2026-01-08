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
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            background: #fff;
        }
        
        /* Table Layout for PDF Compatibility */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .layout-table td {
            vertical-align: top;
            padding: 0;
            margin: 0;
        }
        
        /* Sidebar */
        .sidebar {
            width: 30%;
            background-color: #f7f9fc; /* Very light blue-grey */
            border-right: 1px solid #e1e4e8;
        }
        .sidebar-content {
            padding: 30px 20px;
        }
        
        .contact-section { margin-bottom: 30px; }
        .contact-item {
            margin-bottom: 12px;
            font-size: 9pt;
            word-break: break-all;
        }
        .contact-label {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8pt;
            color: #4a5568;
            margin-bottom: 2px;
            display: block;
            letter-spacing: 1px;
        }
        
        .sidebar-section { margin-bottom: 30px; }
        .sidebar-title {
            font-size: 10pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding-bottom: 8px;
            border-bottom: 1px solid #cbd5e0;
            margin-bottom: 15px;
            color: #2d3748;
        }
        
        /* Main Content */
        .main {
            width: 70%;
        }
        .main-content {
            padding: 30px 40px;
        }
        
        /* Header in Main */
        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2d3748;
        }
        .name {
            font-size: 28pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a202c;
            line-height: 1;
            margin-bottom: 10px;
        }
        .title-badge {
            display: inline-block;
            background: #2d3748;
            color: #fff;
            padding: 4px 12px;
            font-size: 9pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Sections */
        .section { margin-bottom: 25px; }
        .section-title {
            font-size: 11pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2d3748;
            margin-bottom: 15px;
        }
        
        .exp-item { margin-bottom: 20px; }
        
        /* Experience Header Table */
        .exp-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .exp-role-cell {
            text-align: left;
            width: 70%;
        }
        .exp-date-cell {
            text-align: right;
            width: 30%;
            vertical-align: top;
        }

        .exp-role {
            font-weight: 700;
            font-size: 10pt;
            color: #000;
            text-transform: uppercase;
        }
        .exp-date {
            font-weight: 600;
            font-size: 9pt;
            color: #666;
        }
        .exp-company {
            font-style: italic;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 6px;
            display: block;
        }
        .exp-list {
            margin-left: 16px;
        }
        .exp-list li {
            margin-bottom: 4px;
        }
        
        .summary-text {
            text-align: justify;
        }
        
        /* Skills in Sidebar */
        .skill-tag {
            display: inline-block;
            margin-bottom: 6px;
            margin-right: 4px;
            font-size: 9pt;
        }
        
        /* Education in Sidebar */
        .edu-item { margin-bottom: 15px; }
        .edu-degree { font-weight: 700; font-size: 9pt; }
        .edu-inst { font-size: 9pt; }
        .edu-year { font-size: 8.5pt; color: #666; font-style: italic; }

    </style>
</head>
<body>
    <table class="layout-table">
        <tr>
            <!-- Sidebar -->
            <td class="sidebar">
                <div class="sidebar-content">
                    <!-- Contact -->
                    <div class="contact-section">
                        @if(!empty($data['personal']['phone']))
                        <div class="contact-item">
                            <span class="contact-label">Phone</span>
                            {{ $data['personal']['phone'] }}
                        </div>
                        @endif
                        @if(!empty($data['personal']['email']))
                        <div class="contact-item">
                            <span class="contact-label">Email</span>
                            {{ $data['personal']['email'] }}
                        </div>
                        @endif
                        @if(!empty($data['personal']['city']) || !empty($data['personal']['state_country']))
                        <div class="contact-item">
                            <span class="contact-label">Address</span>
                            {{ $data['personal']['city'] }}<br>{{ $data['personal']['state_country'] }}
                        </div>
                        @endif
                        @if(!empty($data['personal']['linkedin_url']))
                        <div class="contact-item">
                            <span class="contact-label">LinkedIn</span>
                            <a href="{{ $data['personal']['linkedin_url'] }}" style="color: inherit; text-decoration: none;">View Profile</a>
                        </div>
                        @endif
                        @if(!empty($data['personal']['portfolio_url']))
                        <div class="contact-item">
                            <span class="contact-label">Portfolio</span>
                            <a href="{{ $data['personal']['portfolio_url'] }}" style="color: inherit; text-decoration: none;">Link</a>
                        </div>
                        @endif
                    </div>

                    <!-- Skills (Sidebar) -->
                   @if(!empty($data['skills']))
                    <div class="sidebar-section">
                        <div class="sidebar-title">Key Skills</div>
                        <div>
                         @foreach($data['skills'] as $skill)
                           <div class="skill-tag">&#8226; {{ $skill }}</div>
                         @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Education (Sidebar) -->
                    @if(!empty($data['education']))
                    <div class="sidebar-section">
                        <div class="sidebar-title">Education</div>
                        @foreach($data['education'] as $edu)
                        <div class="edu-item">
                            <div class="edu-degree">{{ $edu['degree'] }}</div>
                            <div class="edu-inst">{{ $edu['institution'] }}</div>
                             @php
                                $gradDate = trim(($edu['graduation_month'] ?? '') . ' ' . ($edu['graduation_year'] ?? ''));
                            @endphp
                            @if($gradDate)
                            <div class="edu-year">{{ $gradDate }}</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </td>

            <!-- Main Content -->
            <td class="main">
                <div class="main-content">
                    <!-- Header -->
                    <div class="header">
                        <div class="name">{{ $data['personal']['full_name'] }}</div>
                        @if(!empty($data['personal']['professional_title']))
                        <div class="title-badge">{{ $data['personal']['professional_title'] }}</div>
                        @endif
                    </div>

                    <!-- Summary -->
                    @if(!empty($data['personal']['professional_summary']))
                    <div class="section">
                        <div class="section-title">Profile</div>
                        <div class="summary-text">
                            {{ $data['personal']['professional_summary'] }}
                        </div>
                    </div>
                    @endif

                    <!-- Experience -->
                     @if(!empty($data['experience']))
                    <div class="section">
                        <div class="section-title">Professional Experience</div>
                        
                        @foreach($data['experience'] as $exp)
                        <div class="exp-item">
                             <table class="exp-header-table">
                                <tr>
                                    <td class="exp-role-cell">
                                        <div class="exp-role">{{ $exp['position'] }}</div>
                                    </td>
                                    <td class="exp-date-cell">
                                        @php
                                            $startDate = trim(($exp['start_month'] ?? '') . ' ' . ($exp['start_year'] ?? ''));
                                            $endDate = $exp['currently_working'] ?? false ? 'Present' : trim(($exp['end_month'] ?? '') . ' ' . ($exp['end_year'] ?? ''));
                                            $dateRange = $startDate ? "$startDate - $endDate" : $endDate;
                                        @endphp
                                        <div class="exp-date">{{ $dateRange }}</div>
                                    </td>
                                </tr>
                             </table>
                            
                            @if(!empty($exp['company']))
                            <div class="exp-company">{{ $exp['company'] }}</div>
                            @endif
                            
                            @if(!empty($exp['description']))
                            <div class="summary-text" style="white-space: pre-line;">
                                {{ $exp['description'] }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Projects (if any) -->
                    @if(!empty($data['projects_text']))
                     <div class="section">
                        <div class="section-title">Projects</div>
                        <div class="summary-text" style="white-space: pre-line;">
                             {{ $data['projects_text'] }}
                        </div>
                    </div>
                    @endif

                 </div>
            </td>
        </tr>
    </table>
</body>
</html>