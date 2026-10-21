<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* Header Styling */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
        }

        .header h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
            opacity: 0.95;
        }

        /* Stats Container */
        .stats-container {
            width: 100%;
        }

        .stat-box {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-left: 4px solid #4e73df;
            padding: 12px;
            margin-bottom: 10px;
            text-align: center;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-box h3 {
            margin: 0 0 5px 0;
            font-size: 22px;
            color: #2c3e50;
            font-weight: bold;
        }

        .stat-box p {
            margin: 0;
            font-size: 9px;
            color: #5a6c7d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e9ecef;
        }

        /* Text Alignment */
        .text-center {
            text-align: center;
        }

        /* Badge Styling */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        /* Footer Styling */
        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 9px;
        }

        /* Chart Bar Styling */
        .chart-bar-bg {
            background-color: #e9ecef;
            height: 12px;
            width: 100%;
            border-radius: 6px;
            overflow: hidden;
        }

        .chart-bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.3s ease;
        }
    </style>
</head>

<body>
    @yield('content')

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis pada {{ $data['tanggal'] }} pukul {{ $data['jam'] }}</p>
    </div>
</body>

</html>