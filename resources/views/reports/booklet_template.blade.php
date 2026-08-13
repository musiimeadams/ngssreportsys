<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Booklet - {{ $schoolClass->name }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background-color: white;
            color: black;
            margin: 0;
            padding: 0;
        }
        .report-header {
            font-family: 'Cinzel', Times, serif;
        }
        .mono-font {
            font-family: 'Courier Prime', Courier, monospace;
        }
        .page-container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 5mm 6mm;
            box-sizing: border-box;
            page-break-after: always;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        /* Leafy/formal border matching the screenshot */
        .academic-border {
            border: 2px double #000000;
            height: 100%;
            width: 100%;
            box-sizing: border-box;
            position: relative;
            padding: 8px 30px; /* Space for the left/right leafy borders */
        }
        .leaf-border-svg {
            display: block !important;
        }
        /* Tables general */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid black;
            text-align: left;
            padding: 3px 5px;
            font-size: 0.6rem;
        }
        .subject-name {
            color: #b91c1c;
            font-weight: bold;
            text-transform: uppercase;
        }
        .score-range {
            color: #15803d;
            font-family: 'Courier Prime', monospace;
        }
        .black-bar {
            background-color: transparent !important;
            color: black !important;
            border: 1px solid black !important;
            text-align: center;
            padding: 3px 0;
            margin: 4px 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .black-bar h2, .black-bar h3 {
            color: black !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .black-bar h2 {
            font-size: 0.65rem;
            margin: 0;
            font-weight: 900;
            letter-spacing: 0.05em;
        }
        .black-bar h3 {
            font-size: 0.6rem;
            margin: 0;
            font-weight: 900;
            letter-spacing: 0.05em;
        }
        .school-header-table td {
            border: none;
            padding: 0;
        }
        .info-table td {
            border: none;
            padding: 3px 0;
        }
        .school-logo {
            width: 48px;
            height: 48px;
        }
        .school-logo img {
            width: 40px;
            height: 40px;
            object-contain: true;
        }
        .remarks-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
            margin-top: 4px;
        }
        .remarks-row {
            font-size: 0.6rem;
            margin-bottom: 3px;
            display: flex;
            align-items: baseline;
        }
        .remarks-label {
            font-weight: bold;
            flex-shrink: 0;
        }
        .remarks-line {
            border-bottom: 1px dotted black;
            flex-grow: 1;
            padding-left: 5px;
            font-style: italic;
        }
        .signature-label {
            flex-shrink: 0;
            padding-left: 10px;
            color: #4b5563;
        }
        /* Print rules */
        @page {
            size: A4 portrait;
            margin: 0;
        }
        @media print {
            body {
                background-color: white;
            }
            .page-container {
                margin: 0;
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    @foreach($students as $student)
        <div class="page-container">
            <div class="academic-border" style="position: relative;">
                <!-- Left Leafy Border -->
                <svg class="leaf-border-svg left-0" style="position: absolute; top: 0; bottom: 0; left: 8px; width: 20px; height: 100%; pointer-events: none; z-index: 10;">
                    <defs>
                        <pattern id="leafPattern-{{ $student->id }}" width="20" height="33" patternUnits="userSpaceOnUse">
                            <path d="M10,0 L10,33" stroke="black" stroke-width="1.5" />
                            <path d="M10,15 C5,13 2,9 10,5 C6,9 8,12 10,15" fill="black" />
                            <circle cx="7" cy="9" r="0.8" fill="white" />
                            <path d="M10,31 C15,29 18,25 10,21 C14,25 12,28 10,31" fill="black" />
                            <circle cx="13" cy="25" r="0.8" fill="white" />
                        </pattern>
                    </defs>
                    <rect width="20" height="100%" fill="url(#leafPattern-{{ $student->id }})" />
                </svg>
                <!-- Right Leafy Border -->
                <svg class="leaf-border-svg right-0" style="position: absolute; top: 0; bottom: 0; right: 8px; width: 20px; height: 100%; pointer-events: none; z-index: 10;">
                    <rect width="20" height="100%" fill="url(#leafPattern-{{ $student->id }})" />
                </svg>
                
                <!-- School Banner Header -->
                <table class="school-header-table" style="width: 100%; border-bottom: 2px solid black; padding-bottom: 4px; margin-bottom: 4px;">
                    <tr>
                        <td style="width: 50px; vertical-align: middle;">
                            <div class="school-logo" style="border: 1px solid #4b5563; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #f3f4f6; overflow: hidden; width: 44px; height: 44px;">
                                <img src="/images/logo.png" alt="Logo" style="width: 36px; height: 36px;">
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <h1 class="report-header" style="font-size: 1.05rem; font-weight: 800; margin: 0; text-transform: uppercase; color: black; letter-spacing: 0.02em;">{{ $schoolSetting->school_name }}</h1>
                            <p style="font-size: 0.65rem; font-weight: 700; margin: 2px 0 0 0; text-transform: uppercase; color: #374151; letter-spacing: 0.05em;">{{ $schoolSetting->address }}</p>
                            <div style="font-size: 0.55rem; font-weight: bold; margin-top: 3px; color: black;">
                                <span style="color: #b91c1c; font-style: italic; margin-right: 15px;">Motto: {{ $schoolSetting->school_motto }}</span>
                                <span style="color: #b91c1c; font-family: monospace;">Tel: {{ $schoolSetting->phone }}</span>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Student & Term Information -->
                <table class="info-table" style="width: 100%; margin-bottom: 4px;">
                    <tr>
                        <td style="vertical-align: top; width: 80%;">
                            <table style="width: 100%;">
                                <tr>
                                    <td colspan="3" style="border-bottom: 1px solid black; padding-bottom: 2px; padding-top: 0;">
                                        <span style="color: #6b7280; font-size: 0.5rem; font-weight: 600; text-transform: uppercase; display: block;">NAME OF STUDENT</span>
                                        <span style="font-size: 0.8rem; font-weight: bold; text-transform: uppercase; color: black;">{{ $student->first_name }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $student->last_name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 33%; padding-top: 3px; border: none;">
                                        <span style="color: #6b7280; font-size: 0.5rem; font-weight: 600; text-transform: uppercase; display: block;">CLASS</span>
                                        <span style="font-size: 0.65rem; font-weight: bold; color: black; text-transform: uppercase;">{{ $student->schoolClass->name }}</span>
                                    </td>
                                    <td style="width: 33%; padding-top: 3px; border: none;">
                                        <span style="color: #6b7280; font-size: 0.5rem; font-weight: 600; text-transform: uppercase; display: block;">TERM</span>
                                        <span style="font-size: 0.65rem; font-weight: bold; color: black; text-transform: uppercase;">{{ $activeTerm->name }}</span>
                                    </td>
                                    <td style="width: 34%; padding-top: 3px; border: none;">
                                        <span style="color: #6b7280; font-size: 0.5rem; font-weight: 600; text-transform: uppercase; display: block;">YEAR</span>
                                        <span style="font-size: 0.65rem; font-weight: bold; color: black; text-transform: uppercase;">{{ $activeTerm->academicYear->name }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="vertical-align: top; text-align: right; width: 20%;">
                            <div style="width: 55px; height: 65px; border: 1px solid black; border-radius: 4px; display: inline-block; overflow: hidden; padding: 2px; background-color: #f9fafb;">
                                @if($student->photo_path)
                                    <img src="{{ asset($student->photo_path) }}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 2px;">
                                @else
                                    <div style="font-size: 0.45rem; text-align: center; color: #9ca3af; font-weight: bold; padding-top: 20px; text-transform: uppercase;">No Photo</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Report Card Title Bar -->
                <div class="black-bar">
                    <h2>END OF TERM PROGRESSIVE ASSESSMENT REPORT</h2>
                </div>

                <!-- Academic Performance Table -->
                <div style="margin-bottom: 4px;">
                    @if($student->marks->isEmpty())
                        <p style="font-size: 0.65rem; color: #6b7280; text-align: center; padding: 15px; border: 1px dashed black; border-radius: 8px;">No scores recorded for this learner.</p>
                    @else
                        <table>
                            <thead>
                                <tr style="background-color: #f3f4f6; font-weight: bold;">
                                    <th style="padding: 2px 4px; font-size: 0.6rem;">SUBJECT</th>
                                    <th style="padding: 2px 4px; font-size: 0.6rem; text-align: center; width: 90px;">COURSE WORK<br>(20)</th>
                                    <th style="padding: 2px 4px; font-size: 0.6rem; text-align: center; width: 110px;">SUMMATIVE ASSESMENT<br>(80)</th>
                                    <th style="padding: 2px 4px; font-size: 0.6rem; text-align: center; width: 80px;">TOTAL 100%</th>
                                    <th style="padding: 2px 4px; font-size: 0.6rem; text-align: center; width: 60px;">GRADE</th>
                                    <th style="padding: 2px 4px; font-size: 0.6rem; text-align: center; width: 90px;">LEVEL OF<br>ACHIEVEME<br>NT/3</th>
                                    <th style="padding: 2px 4px; font-size: 0.6rem; text-align: center; width: 60px;">INITIALS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student->marks as $m)
                                    <tr>
                                        <td class="subject-name" style="padding: 2px 4px; font-size: 0.6rem;">{{ $m->subject->name }}</td>
                                        <td class="mono-font" style="padding: 2px 4px; font-size: 0.6rem; text-align: center;">{{ $m->formative_score !== null ? round($m->formative_score) : '' }}</td>
                                        <td class="mono-font" style="padding: 2px 4px; font-size: 0.6rem; text-align: center;">{{ $m->summative_score !== null ? round($m->summative_score) : '' }}</td>
                                        <td class="mono-font" style="padding: 2px 4px; font-size: 0.6rem; text-align: center; font-weight: bold;">{{ $m->total_score !== null ? round($m->total_score) : '' }}</td>
                                        <td style="padding: 2px 4px; font-size: 0.6rem; text-align: center; font-weight: bold; color: black; text-transform: uppercase;">{{ $m->grade ?? '-' }}</td>
                                        <td class="mono-font" style="padding: 2px 4px; font-size: 0.6rem; text-align: center; font-weight: bold;">{{ $m->level_of_achievement !== null ? number_format($m->level_of_achievement, 1) : '-' }}</td>
                                        <td style="padding: 2px 4px; font-size: 0.6rem; text-align: center; font-weight: bold; color: #4b5563; text-transform: uppercase;">{{ substr($m->teacher?->name ?? 'T', 0, 2) }}</td>
                                    </tr>
                                @endforeach
                                <!-- Overall Average Row -->
                                <tr style="background-color: #f9fafb; font-weight: bold;">
                                    <td colspan="5" style="padding: 2px 4px; font-size: 0.6rem; text-align: right; text-transform: uppercase; letter-spacing: 0.05em;">OVERALL AVERAGE/3</td>
                                    <td class="mono-font" style="padding: 2px 4px; font-size: 0.65rem; text-align: center; color: black; font-weight: bold;">{{ number_format($student->overall_average, 1) }}</td>
                                    <td style="padding: 2px 4px; border-left: none;"></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Legends Grid Layout -->
                <table style="width: 100%; border: none; margin-bottom: 4px;">
                    <tr>
                        <!-- Left: Grading Scale -->
                        <td class="legend-container" style="border: 1px solid black; width: 60%; vertical-align: top; padding: 4px; border-radius: 4px; background-color: white;">
                            <table style="width: 100%; border: none;">
                                <thead>
                                    <tr style="border-bottom: 1px solid black; font-weight: bold;">
                                        <th style="border: none; padding: 1px 0; font-size: 0.55rem; width: 12%;">GRADE</th>
                                        <th style="border: none; padding: 1px 0; font-size: 0.55rem; width: 22%;">SCORE RANGE</th>
                                        <th style="border: none; padding: 1px 0; font-size: 0.55rem;">DESCRIPTOR / COMPETENCY MEANING</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">A*</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">90 - 100</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved MOST or ALL competencies in the subject exceptionally well</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">A</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">80 - 89</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved MOST or ALL competencies in the subject exceedingly well</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">B</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">70 - 79</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved MOST but not ALL competencies well in the subject</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">C</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">60 - 69</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved a GOOD number of competencies in the subject</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">D</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">50 - 59</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved a BASIC number of competencies in the subject</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">E</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">40 - 49</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved a bear minimum number of competencies in the subject just enough to exhibit the required knowledge and skills</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">F</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">30 - 39</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved a number of competencies but not enough to make her competent in the subject</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">G</td>
                                        <td class="score-range" style="border: none; padding: 1px 0; font-size: 0.5rem;">&lt; 30</td>
                                        <td style="border: none; padding: 1px 0; font-size: 0.5rem; color: #374151;">Achieved very few or no competencies / below basic</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 2%; border: none;"></td>
                        <!-- Right: Identifier -->
                        <td class="legend-container" style="border: 1px solid black; width: 38%; vertical-align: top; padding: 4px; border-radius: 4px; background-color: white;">
                            <table style="width: 100%; border: none;">
                                <thead>
                                    <tr style="border-bottom: 1px solid black; font-weight: bold;">
                                        <th style="border: none; padding: 1px 0; font-size: 0.55rem; width: 22%;">IDENTIFIER</th>
                                        <th style="border: none; padding: 1px 0; font-size: 0.55rem; width: 28%;">SCORE RANGE</th>
                                        <th style="border: none; padding: 1px 0; font-size: 0.55rem;">DESCRIPTOR MEANING</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">1</td>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; font-family: monospace;">0.9 - 1.4</td>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; color: #374151;">No learning outcomes achieved(Student was absent)</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">2</td>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; font-family: monospace;">1.5 - 2.4</td>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; color: #374151;">Some LOS achieved but not sufficient for overall achievement (basic)</td>
                                    </tr>
                                    <tr>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; font-weight: bold; font-family: monospace;">3</td>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; font-family: monospace;">2.5 - 3.0</td>
                                        <td style="border: none; padding: 2px 0; font-size: 0.5rem; color: #374151;">Most LOS achieved enough for overall learning outcomes (moderate)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Remarks Section -->
                <div class="remarks-section">
                    <div class="remarks-row">
                        <span class="remarks-label">Class Teacher's comment:</span>
                        <span class="remarks-line">&nbsp;</span>
                        <span class="signature-label">sign: __________________</span>
                    </div>
                    <div class="remarks-row" style="margin-top: 4px;">
                        <span class="remarks-label">Head teachers' comment:</span>
                        <span class="remarks-line">&nbsp;</span>
                        <span class="signature-label">sign: __________________</span>
                    </div>
                </div>

                <!-- School Footer / Next term announcement -->
                <div class="black-bar" style="margin-top: 4px;">
                    <h3>NEXT TERM BEGINS ON {{ $schoolSetting->next_term_begins ? $schoolSetting->next_term_begins->format('jS F Y') : '25TH MAY 2026' }}.</h3>
                </div>

            </div>
        </div>
    @endforeach

</body>
</html>
