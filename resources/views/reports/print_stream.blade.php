<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Reports - {{ $schoolClass->name }}</title>
    @vite(['resources/css/app.css'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #030712;
            color: #f3f4f6;
        }
        .report-header {
            font-family: 'Cinzel', serif;
        }
        .mono-font {
            font-family: 'Courier Prime', monospace;
        }
        .page-break {
            page-break-after: always;
        }
        .academic-border {
            border: 4px double #475569;
            position: relative;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
                color: black !important;
                padding: 0;
                margin: 0;
            }
            .print-border {
                border: 3px double #000000 !important;
                border-radius: 0 !important;
                background: white !important;
                padding: 20px !important;
                box-shadow: none !important;
                margin-bottom: 0 !important;
            }
            .academic-border {
                border: 3px double #000000 !important;
            }
            .text-indigo-400, .text-indigo-300 {
                color: black !important;
            }
            .text-red-500, .text-red-400 {
                color: #e11d48 !important;
            }
            .text-white {
                color: black !important;
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
            }
            .black-bar {
                background-color: black !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="bg-slate-950 p-4 md:p-8 min-h-screen flex flex-col items-center">

    <!-- Top Action bar (hidden in print) -->
    <div class="w-full max-w-4xl no-print flex justify-between items-center mb-6 bg-slate-900 border border-slate-800 p-4 rounded-2xl shadow-lg text-slate-100">
        <div>
            <h1 class="text-base font-bold">Print Class Bundle</h1>
            <p class="text-xs text-slate-400">Bulk printing {{ $students->count() }} report cards for {{ $schoolClass->name }}</p>
        </div>
        <button onclick="window.print()" 
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl flex items-center gap-2 shadow transition duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.615 0-1.11-.483-1.12-1.099L6.34 18m11.32 0H6.34M16.5 12h.008v.008H16.5V12Zm-.9 6h.008v.008H15.6V18Z" />
            </svg>
            Print All Now
        </button>
    </div>

    <!-- Loop through all students -->
    @foreach($students as $student)
        <div class="w-full max-w-4xl bg-slate-900/60 border border-slate-800/80 rounded-3xl p-8 md:p-12 shadow-2xl print-border academic-border mb-8 page-break">
            
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
                        <span class="text-white font-bold text-lg block mt-0.5 uppercase tracking-wide">{{ $student->full_name }}</span>
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
            <div class="black-bar bg-slate-950 text-center py-2 rounded-xl mb-6 border border-slate-800 shadow-md">
                <h2 class="text-sm font-black tracking-widest text-white uppercase">END OF TERM PROGRESSIVE ASSESSMENT REPORT</h2>
            </div>

            <!-- Academic Performance Table -->
            <div class="mb-6">
                @if($student->marks->isEmpty())
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
                                @foreach($student->marks as $m)
                                    <tr>
                                        <td class="py-2.5 px-3 border-r border-slate-800 font-bold text-white uppercase">{{ $m->subject->name }}</td>
                                        <td class="py-2.5 px-3 text-center border-r border-slate-800 font-semibold mono-font text-sm">{{ $m->formative_score !== null ? round($m->formative_score) : '' }}</td>
                                        <td class="py-2.5 px-3 text-center border-r border-slate-800 font-semibold mono-font text-sm">{{ $m->summative_score !== null ? round($m->summative_score) : '' }}</td>
                                        <td class="py-2.5 px-3 text-center border-r border-slate-800 font-bold mono-font text-sm text-white">{{ $m->total_score !== null ? round($m->total_score) : '' }}</td>
                                        <td class="py-2.5 px-3 text-center border-r border-slate-800 font-bold text-sm text-indigo-400 uppercase">{{ $m->grade ?? '-' }}</td>
                                        <td class="py-2.5 px-3 text-center border-r border-slate-800 font-bold mono-font text-sm">{{ $m->level_of_achievement !== null ? number_format($m->level_of_achievement, 1) : '-' }}</td>
                                        <td class="py-2.5 px-3 text-center font-bold text-slate-500 uppercase">{{ substr($m->teacher?->name ?? 'T', 0, 2) }}</td>
                                    </tr>
                                @endforeach
                                <!-- Overall Average Row -->
                                <tr class="bg-slate-950/40 font-bold border-t-2 border-slate-800 text-white">
                                    <td colspan="5" class="py-3 px-3 border-r border-slate-800 text-right uppercase tracking-wider text-xs">OVERALL AVERAGE/3</td>
                                    <td class="py-3 px-3 text-center border-r border-slate-800 mono-font text-base text-indigo-400">{{ number_format($student->overall_average, 1) }}</td>
                                    <td class="py-3 px-3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Legends Grid Layout -->
            <div class="grid grid-cols-5 gap-6 mb-6">
                <!-- Left: Grading Scale (Spans 3 cols) -->
                <div class="col-span-3 border border-slate-800 rounded-xl p-4 bg-slate-950/40 text-[10px]">
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
                                <td class="py-1 font-mono">90 - 100</td>
                                <td class="py-1">Achieved MOST or ALL competencies in the subject exceptionally well</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">A</td>
                                <td class="py-1 font-mono">80 - 89</td>
                                <td class="py-1">Achieved MOST or ALL competencies in the subject exceedingly well</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">B</td>
                                <td class="py-1 font-mono">70 - 79</td>
                                <td class="py-1">Achieved MOST but not ALL competencies well in the subject</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">C</td>
                                <td class="py-1 font-mono">60 - 69</td>
                                <td class="py-1">Achieved a GOOD number of competencies in the subject</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">D</td>
                                <td class="py-1 font-mono">50 - 59</td>
                                <td class="py-1">Achieved a BASIC number of competencies in the subject</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">E</td>
                                <td class="py-1 font-mono">40 - 49</td>
                                <td class="py-1">Achieved a bear minimum number of competencies in the subject just enough to exhibit the required knowledge and skills</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">F</td>
                                <td class="py-1 font-mono">30 - 39</td>
                                <td class="py-1">Achieved a number of competencies but not enough to make her competent in the subject</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-bold text-white font-mono">G</td>
                                <td class="py-1 font-mono">&lt; 30</td>
                                <td class="py-1">Achieved very few or no competencies</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right: Identifier (Spans 2 cols) -->
                <div class="col-span-2 border border-slate-800 rounded-xl p-4 bg-slate-950/40 text-[10px]">
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
            <div class="space-y-4 mb-8 pt-4 border-t border-slate-800/80 text-xs text-slate-300">
                <div class="flex flex-col md:flex-row md:items-baseline gap-2">
                    <span class="font-bold text-white shrink-0">Class Teacher's comment:</span>
                    <span class="border-b border-dotted border-slate-600 flex-1 text-slate-350 italic py-0.5">
                        {{ $student->reportCard->class_teacher_comment ?? 'No remarks provided' }}
                    </span>
                    <span class="shrink-0 text-slate-500 font-semibold pl-4">sign: __________________</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-baseline gap-2 pt-2">
                    <span class="font-bold text-white shrink-0">Head teachers' comment:</span>
                    <span class="border-b border-dotted border-slate-600 flex-1 text-slate-350 italic py-0.5">
                        {{ $student->reportCard->headteacher_comment ?? 'Steady progress registered. Recommended to maintain this performance.' }}
                    </span>
                    <span class="shrink-0 text-slate-500 font-semibold pl-4">sign: __________________</span>
                </div>
            </div>

            <!-- School Footer / Next term announcement -->
            <div class="black-bar bg-slate-950 text-center py-2.5 rounded-xl border border-slate-800">
                <h3 class="text-xs font-black tracking-widest text-indigo-400 uppercase">
                    NEXT TERM BEGINS ON {{ $schoolSetting->next_term_begins ? $schoolSetting->next_term_begins->format('jS F Y') : '25TH MAY 2026' }}.
                </h3>
            </div>

        </div>
    @endforeach
</body>
</html>
