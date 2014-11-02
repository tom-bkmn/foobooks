<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>@yield('title') </title>

     <style>
         body {
             background-color: @yield('color');
         }
    </style>

</head>
<body>

    <h1>@yield('bodyContent_1')</h1>

   <h2>@yield('dynamic')</h2>

</body>
</html>
