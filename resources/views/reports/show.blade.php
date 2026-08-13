<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $student->full_name }}</title>
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .leaf-border-svg {
            display: none !important;
        }
        .black-bar {
            background-color: transparent !important;
            border: 1px solid #000000 !important;
        }
        .black-bar h2, .black-bar h3 {
            color: #000000 !important;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .report-header {
            font-family: 'Cinzel', serif;
        }
        .mono-font {
            font-family: 'Courier Prime', monospace;
        }
        /* Leafy/formal border mimicking the screenshot */
        .academic-border {
            border: 4px double #475569;
            position: relative;
        }
        @page {
            size: A4 portrait;
            margin: 8mm 8mm !important;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-container {
                border: 2px double #000000 !important;
                border-radius: 0 !important;
                background: transparent !important;
                padding: 8px 30px !important; /* space for left/right borders */
                box-shadow: none !important;
                margin: 0 auto !important;
                width: 194mm !important;
                max-width: 194mm !important;
                height: 278mm !important;
                max-height: 278mm !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                position: relative !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }
            .leaf-border-svg {
                display: block !important;
            }
            .academic-border {
                border: 2px double #000000 !important;
            }
            .text-indigo-400, .text-indigo-300 {
                color: black !important;
            }
            .text-red-500, .text-red-400 {
                color: #b91c1c !important; /* Keep red color for motto/phone in print */
            }
            .text-white {
                color: black !important;
            }
            .subject-name {
                color: #b91c1c !important;
                font-weight: bold !important;
                text-transform: uppercase !important;
            }
            .score-range {
                color: #15803d !important;
                font-family: 'Courier Prime', monospace !important;
            }
            .bg-slate-900, .bg-slate-950, .bg-slate-900\/60, .bg-slate-950\/40, .bg-slate-950\/60 {
                background-color: white !important;
                color: black !important;
            }
            .border-slate-800, .border-slate-700 {
                border-color: black !important;
            }
            table th, table td {
                border-color: black !important;
                color: black !important;
                padding-top: 2px !important;
                padding-bottom: 2px !important;
                padding-left: 4px !important;
                padding-right: 4px !important;
                font-size: 0.6rem !important;
            }
            .black-bar {
                background-color: transparent !important;
                color: black !important;
                border: 1px solid black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin-top: 3px !important;
                margin-bottom: 3px !important;
                padding-top: 1.5px !important;
                padding-bottom: 1.5px !important;
            }
            .black-bar h2, .black-bar h3 {
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .black-bar h2 {
                font-size: 0.65rem !important;
            }
            .black-bar h3 {
                font-size: 0.6rem !important;
            }
            /* Header adjustments */
            .w-28.h-28 {
                width: 65px !important;
                height: 65px !important;
            }
            .w-28.h-28 img {
                width: 56px !important;
                height: 56px !important;
            }
            .report-header.text-3xl {
                font-size: 1.25rem !important;
            }
            .report-header Republic, .report-header .text-sm {
                font-size: 0.75rem !important;
                margin-top: 1px !important;
            }
            .flex-1 p.text-slate-400 {
                font-size: 0.75rem !important;
                margin-top: 1px !important;
            }
            .flex-1 .text-red-500 {
                font-size: 0.65rem !important;
                margin-top: 2px !important;
            }
            .pb-6 {
                padding-bottom: 2px !important;
            }
            .mb-6 {
                margin-bottom: 2px !important;
            }
            .mb-8 {
                margin-bottom: 2px !important;
            }
            .gap-6 {
                gap: 6px !important;
            }
            /* Student details & photo frame adjustments */
            .p-5 {
                padding: 4px 8px !important;
            }
            .gap-y-3\.5 {
                row-gap: 2px !important;
            }
            .w-32.h-36 {
                width: 55px !important;
                height: 65px !important;
            }
            .text-lg {
                font-size: 0.8rem !important;
            }
            /* Remarks section adjustments */
            .space-y-4 {
                margin-top: 2px !important;
                margin-bottom: 2px !important;
                padding-top: 2px !important;
            }
            .space-y-4 > div {
                font-size: 0.6rem !important;
                margin-top: 1px !important;
            }
            /* Legends table font size */
            .legend-container {
                padding: 4px !important;
                border-radius: 4px !important;
            }
            .legend-container table td {
                padding-top: 1px !important;
                padding-bottom: 1px !important;
                font-size: 0.55rem !important;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 p-4 md:p-8 min-h-screen flex flex-col items-center">

    <!-- Top Action bar (hidden in print) -->
    <div class="w-full max-w-4xl no-print flex justify-between items-center mb-6 bg-slate-900 border border-slate-800 p-4 rounded-2xl shadow-lg">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-slate-400">Learner Report Card - Preview Mode</span>
        </div>
        <button onclick="window.print()" 
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl flex items-center gap-2 shadow transition duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.615 0-1.11-.483-1.12-1.099L6.34 18m11.32 0H6.34M16.5 12h.008v.008H16.5V12Zm-.9 6h.008v.008H15.6V18Z" />
            </svg>
            Print Report Card
        </button>
    </div>

    <!-- Official Report Card Layout -->
    <div class="w-full max-w-4xl bg-slate-900/60 border border-slate-800/80 rounded-3xl p-8 md:p-12 shadow-2xl print-container academic-border relative">
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
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 border-b-2 border-slate-800/80 pb-6 mb-6">
            <!-- Crest Badge -->
            <div class="w-28 h-28 rounded-full border border-slate-800 flex items-center justify-center shrink-0 bg-slate-950/60 relative overflow-hidden">
                <img src="/images/logo.png" alt="School Logo" class="w-24 h-24 object-contain">
            </div>
            
            <div class="text-center flex-1">
                <h1 class="report-header text-3xl font-extrabold text-white tracking-wide uppercase leading-none">{{ $schoolSetting->school_name }}</h1>
                <p class="report-header text-sm font-bold text-slate-350 tracking-wider mt-1.5 uppercase">{{ $schoolSetting->address }}</p>
                <div class="flex items-center justify-center gap-6 mt-2 text-xs font-semibold">
                    <span class="text-red-500 italic">Motto: {{ $schoolSetting->school_motto }}</span>
                    <span class="text-red-400 font-mono">Tel: {{ $schoolSetting->phone }}</span>
                </div>
            </div>
        </div>

        <!-- Student & Term Information with Photo -->
        <div class="flex flex-col sm:flex-row items-center sm:items-stretch gap-6 mb-6">
            <!-- Student Details Grid -->
            <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-y-3.5 gap-x-6 text-sm bg-slate-950/40 p-5 rounded-2xl border border-slate-800/60 w-full">
                <div class="col-span-2 md:col-span-3 border-b border-slate-800 pb-2 mb-1">
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider block">NAME OF STUDENT</span>
                    <span class="text-white font-bold text-lg block mt-0.5 uppercase tracking-wide">{{ $student->first_name }}&nbsp;&nbsp;&nbsp;&nbsp;{{ $student->last_name }}</span>
                </div>
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider block">CLASS</span>
                    <span class="text-white font-bold block mt-0.5 uppercase">{{ $student->schoolClass->name }}</span>
                </div>
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider block">TERM</span>
                    <span class="text-white font-bold block mt-0.5 uppercase">{{ $activeTerm->name }}</span>
                </div>
                <div>
                    <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider block">YEAR</span>
                    <span class="text-white font-bold block mt-0.5 uppercase">{{ $activeTerm->academicYear->name }}</span>
                </div>
            </div>
            <!-- Student Photo Frame -->
            <div class="w-32 h-36 rounded-2xl border border-slate-800 bg-slate-950/60 shrink-0 flex items-center justify-center relative overflow-hidden p-1 shadow-inner">
                @if($student->photo_path)
                    <img src="{{ asset($student->photo_path) }}" alt="{{ $student->full_name }}" class="w-full h-full object-cover rounded-xl border border-slate-900">
                @else
                    <div class="text-center text-slate-600 font-bold text-xs uppercase p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mx-auto mb-1 text-slate-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        No Photo
                    </div>
                @endif
            </div>
        </div>

        <!-- Report Card Title Bar -->
        <div class="black-bar text-center py-2 rounded-xl mb-6 border border-slate-800 shadow-md">
            <h2 class="text-sm font-black tracking-widest uppercase">END OF TERM PROGRESSIVE ASSESSMENT REPORT</h2>
        </div>

        <!-- Academic Performance Table -->
        <div class="mb-6">
            @if($marks->isEmpty())
                <p class="text-slate-500 text-center py-8 border border-dashed border-slate-800 rounded-2xl bg-slate-950/20">No scores recorded for this learner.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300 border-collapse border border-slate-800">
                        <thead>
                            <tr class="border-b border-slate-700 bg-slate-950/40 text-slate-400 font-semibold uppercase">
                                <th class="py-3 px-3 border-r border-slate-800">SUBJECT</th>
                                <th class="py-3 px-3 text-center border-r border-slate-800 w-28">COURSE WORK (20)</th>
                                <th class="py-3 px-3 text-center border-r border-slate-800 w-36">SUMMATIVE ASSESMENT (80)</th>
                                <th class="py-3 px-3 text-center border-r border-slate-800 w-28">TOTAL 100%</th>
                                <th class="py-3 px-3 text-center border-r border-slate-800 w-20">GRADE</th>
                                <th class="py-3 px-3 text-center border-r border-slate-800 w-44">LEVEL OF ACHIEVEME NT/3</th>
                                <th class="py-3 px-3 text-center w-24">INITIALS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach($marks as $m)
                                <tr>
                                    <td class="py-2.5 px-3 border-r border-slate-800 font-bold text-white uppercase subject-name">{{ $m->subject->name }}</td>
                                    <td class="py-2.5 px-3 text-center border-r border-slate-800 font-semibold mono-font text-sm">{{ $m->formative_score !== null ? round($m->formative_score) : '' }}</td>
                                    <td class="py-2.5 px-3 text-center border-r border-slate-800 font-semibold mono-font text-sm">{{ $m->summative_score !== null ? round($m->summative_score) : '' }}</td>
                                    <td class="py-2.5 px-3 text-center border-r border-slate-800 font-bold mono-font text-sm text-white">{{ $m->total_score !== null ? round($m->total_score) : '' }}</td>
                                    <td class="py-2.5 px-3 text-center border-r border-slate-800 font-bold text-sm text-indigo-400 uppercase">{{ $m->grade ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-center border-r border-slate-800 font-bold mono-font text-sm">{{ $m->level_of_achievement !== null ? number_format($m->level_of_achievement, 1) : '-' }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-slate-500 uppercase">{{ isset($m->teacher->name) ? (count($parts = array_filter(explode(' ', $m->teacher->name))) > 1 ? reset($parts) . ' ' . end($parts) : reset($parts)) : 'TR' }}</td>
                                </tr>
                            @endforeach
                            <!-- Overall Average Row -->
                            <tr class="bg-slate-950/40 font-bold border-t-2 border-slate-800 text-white">
                                <td colspan="5" class="py-3 px-3 border-r border-slate-800 text-right uppercase tracking-wider text-xs">OVERALL AVERAGE/3</td>
                                <td class="py-3 px-3 text-center border-r border-slate-800 mono-font text-base text-indigo-400">{{ number_format($overallAverage, 1) }}</td>
                                <td class="py-3 px-3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Legends Grid Layout -->
        <div class="grid md:grid-cols-5 gap-6 mb-6">
            <!-- Left: Grading Scale (Spans 3 cols) -->
            <div class="md:col-span-3 legend-container border border-slate-800 rounded-xl p-4 bg-slate-950/40 text-[10px]">
                <table class="w-full text-left text-[9px] text-slate-400">
                    <thead>
                        <tr class="border-b border-slate-800 text-white font-bold uppercase">
                            <th class="pb-1.5 w-12">GRADE</th>
                            <th class="pb-1.5 w-20">SCORE RANGE</th>
                            <th class="pb-1.5">DESCRIPTOR / COMPETENCY MEANING</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900">
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">A*</td>
                            <td class="py-1 font-mono score-range">90 - 100</td>
                            <td class="py-1">Achieved MOST or ALL competencies in the subject exceptionally well</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">A</td>
                            <td class="py-1 font-mono score-range">80 - 89</td>
                            <td class="py-1">Achieved MOST or ALL competencies in the subject exceedingly well</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">B</td>
                            <td class="py-1 font-mono score-range">70 - 79</td>
                            <td class="py-1">Achieved MOST but not ALL competencies well in the subject</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">C</td>
                            <td class="py-1 font-mono score-range">60 - 69</td>
                            <td class="py-1">Achieved a GOOD number of competencies in the subject</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">D</td>
                            <td class="py-1 font-mono score-range">50 - 59</td>
                            <td class="py-1">Achieved a BASIC number of competencies in the subject</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">E</td>
                            <td class="py-1 font-mono score-range">40 - 49</td>
                            <td class="py-1">Achieved a bear minimum number of competencies in the subject just enough to exhibit the required knowledge and skills</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">F</td>
                            <td class="py-1 font-mono score-range">30 - 39</td>
                            <td class="py-1">Achieved a number of competencies but not enough to make her competent in the subject</td>
                        </tr>
                        <tr>
                            <td class="py-1 font-bold text-white font-mono">G</td>
                            <td class="py-1 font-mono score-range">&lt; 30</td>
                            <td class="py-1">Achieved very few or no competencies / below basic</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Right: Identifier (Spans 2 cols) -->
            <div class="md:col-span-2 legend-container border border-slate-800 rounded-xl p-4 bg-slate-950/40 text-[10px]">
                <table class="w-full text-left text-[9px] text-slate-400">
                    <thead>
                        <tr class="border-b border-slate-800 text-white font-bold uppercase">
                            <th class="pb-1.5 w-16">IDENTIFIER</th>
                            <th class="pb-1.5 w-24">SCORE RANGE</th>
                            <th class="pb-1.5">DESCRIPTOR MEANING</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900">
                        <tr>
                            <td class="py-2.5 font-bold text-white font-mono">1</td>
                            <td class="py-2.5 font-mono">0.9 - 1.4</td>
                            <td class="py-2.5">No learning outcomes achieved(Student was absent)</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 font-bold text-white font-mono">2</td>
                            <td class="py-2.5 font-mono">1.5 - 2.4</td>
                            <td class="py-2.5">Some LOS achieved but not sufficient for overall achievement (basic)</td>
                        </tr>
                        <tr>
                            <td class="py-2.5 font-bold text-white font-mono">3</td>
                            <td class="py-2.5 font-mono">2.5 - 3.0</td>
                            <td class="py-2.5">Most LOS achieved enough for overall learning outcomes (moderate)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remarks Section -->
        <div class="space-y-4 mb-6 pt-3 border-t border-slate-800/80 text-xs">
            <!-- Class Teacher's Comment Box -->
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-3 flex flex-col gap-2">
                    <span class="font-bold text-white shrink-0">Class Teacher's comment:</span>
                    <div class="border-b border-dotted border-slate-650 h-5 w-full"></div>
                    <div class="border-b border-dotted border-slate-650 h-5 w-full"></div>
                </div>
                <div class="flex flex-col justify-end items-end pb-0.5">
                    <span class="text-[10px] text-slate-450 font-semibold">Signature: __________________</span>
                </div>
            </div>

            <!-- Head Teacher's Comment Box -->
            <div class="grid grid-cols-4 gap-4 pt-1">
                <div class="col-span-3 flex flex-col gap-2">
                    <span class="font-bold text-white shrink-0">Head teachers' comment:</span>
                    <div class="border-b border-dotted border-slate-650 h-5 w-full"></div>
                    <div class="border-b border-dotted border-slate-650 h-5 w-full"></div>
                </div>
                <div class="flex flex-col justify-end items-end pb-0.5">
                    <span class="text-[10px] text-slate-450 font-semibold">Signature: __________________</span>
                </div>
            </div>
        </div>

        <!-- School Footer / Next term announcement -->
        <div class="black-bar text-center py-2.5 rounded-xl border border-slate-800">
            <h3 class="text-xs font-black tracking-widest uppercase">
                NEXT TERM BEGINS ON {{ $schoolSetting->next_term_begins ? $schoolSetting->next_term_begins->format('jS F Y') : '25TH MAY 2026' }}.
            </h3>
        </div>

    </div>
</body>
</html>
