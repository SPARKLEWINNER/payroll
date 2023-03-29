<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="icon" type="image/png" href="{{ asset('/images/icon.png')}}"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        .page_break { page-break-before: always; }
        body { margin-top: 63px; }
        #first 
        {
            display:none;
        }
        table { 
            border-spacing: 0;
            border-collapse: collapse;
            margin-top: 10px;
            font-size : 8px;
        }
        body{
            font-family: Calibri;
            font-size: 8px;
        }
        .page-break {
            page-break-after: always;
        }
        header {
            position: fixed;
            top: -15px;
            left: 0px;
            right: 0px;
            color: black;
            text-align: left;
            font
        }
        .text-right
        {
            text-align: right;
        }
        .footer
        {
            position: fixed;
            top: 750px;
            left: 500px;
            right: 0px;
            height: 50px;
        }
        .fixed
        {
            position: fixed;
            top: -35px;
            left: 800px;
            right: 0px;
            height: 20px;
        }
        .page-number:after { content: counter(page); }
    </style>
    
</head>
<body> 
    
  Test

</body>
</html>