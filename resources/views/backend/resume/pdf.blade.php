<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            color: #333;
        }

        /* LAYOUT */
        .container {
            display: table;
            width: 100%;
        }

        .left {
            display: table-cell;
            width: 30%;
            background: #1e293b;
            color: #fff;
            padding: 20px;
            vertical-align: top;
        }

        .right {
            display: table-cell;
            width: 70%;
            padding: 20px;
            vertical-align: top;
        }

        /* LEFT SIDE */
        .name {
            font-size: 20px;
            font-weight: bold;
        }

        .title {
            font-size: 13px;
            margin-bottom: 15px;
            color: #cbd5e1;
        }

        .section-left {
            margin-top: 20px;
        }

        .section-left h4 {
            font-size: 13px;
            border-bottom: 1px solid #475569;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        /* CONTACT */
        .contact div {
            margin-bottom: 5px;
            font-size: 11px;
        }

        /* SKILLS */
        .skill {
            background: #334155;
            display: inline-block;
            padding: 3px 7px;
            margin: 2px;
            font-size: 10px;
            border-radius: 3px;
        }

        /* RIGHT SIDE */
        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 2px solid #1e293b;
            margin-bottom: 10px;
            padding-bottom: 4px;
        }

        .item {
            margin-bottom: 10px;
        }

        .item-title {
            font-weight: bold;
            font-size: 13px;
        }

        .item-sub {
            font-size: 11px;
            color: #555;
        }

        ul {
            margin: 5px 0 0 15px;
            padding: 0;
        }

        li {
            margin-bottom: 3px;
        }

        .summary {
            font-size: 12px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- LEFT SIDE -->
        <div class="left">

            <div class="name">{{ $resume->name }}</div>
            <div class="title">{{ $resume->title }}</div>

            <!-- CONTACT -->
            <div class="section-left">
                <h4>Contact</h4>
                <div class="contact">
                    <div>{{ $resume->email }}</div>
                    <div>{{ $resume->phone }}</div>
                    <div>{{ $resume->location }}</div>
                </div>
            </div>

            <!-- SKILLS -->
            @if ($resume->skills->count())
                <div class="section-left">
                    <h4>Skills</h4>

                    @foreach ($resume->skills as $skill)
                        <span class="skill">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- RIGHT SIDE -->
        <div class="right">

            <!-- SUMMARY -->
            @if ($resume->summary)
                <div class="section">
                    <div class="section-title">Profile Summary</div>
                    <div class="summary">{{ $resume->summary }}</div>
                </div>
            @endif

            <!-- EXPERIENCE -->
            @if ($resume->experiences->count())
                <div class="section">
                    <div class="section-title">Experience</div>

                    @foreach ($resume->experiences as $exp)
                        <div class="item">
                            <div class="item-title">
                                {{ $exp->designation }} - {{ $exp->company }}
                            </div>

                            <div class="item-sub">
                                {{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}
                            </div>

                            @if ($exp->details->count())
                                <ul>
                                    @foreach ($exp->details as $detail)
                                        <li>{{ $detail->description }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach

                </div>
            @endif

            <!-- EDUCATION -->
            @if ($resume->educations->count())
                <div class="section">
                    <div class="section-title">Education</div>

                    @foreach ($resume->educations as $edu)
                        <div class="item">
                            <div class="item-title">
                                {{ $edu->degree }} - {{ $edu->field }}
                            </div>

                            <div class="item-sub">
                                {{ $edu->institution }}
                            </div>

                            <div class="item-sub">
                                {{ $edu->start_date }} - {{ $edu->end_date }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    </div>

</body>

</html>
