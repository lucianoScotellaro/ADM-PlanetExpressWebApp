<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Planet Express</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="h-screen w-screen">
        <header>
            <div class="text-5xl text-black-700 font-bold">
                {{ $header }}
            </div>
        </header>
        <div class="mt-5 w-full">
            <p class="text-3xl text-black-500 font-semibold"> {{ $title }}</p>
        </div>
        <div class="mt-3 h-full w-full">
            {{ $slot  }}
        </div>
    </body>
</html>
