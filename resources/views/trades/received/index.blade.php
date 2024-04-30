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
    <body>
        <div class="flex flex-row h-screen">
            <div class="basis-1/4"></div>
            <div class="basis-3/4">
                <ul role="list" class="divide-y divide-gray-100 h-full">
                    @foreach($requests as $request)
                        <li class="flex flex-auto justify-between py-5 h-full">
                            <div class="flex flex-row justify-center content-center">
                                <div class="basis-1/3">
                                    <x-book-image class="w-1/7"></x-book-image>
                                </div>
                                <div class="basis-2/3">
                                    <p>Titolo</p>
                                    <p>Autore</p>
                                    <p>ISBN</p>
                                </div>
                            </div>
                            <div class="content-center">
                                <img src="{{asset("img/swap.png")}}" class="h-12 w-12" alt="">
                            </div>
                            <div class="flex flex-row justify-center">
                                <div class="basis-1/3 content-center">
                                    <x-book-image class="w-1/7"></x-book-image>
                                </div>
                                <div class="basis-2/3 content-center">
                                    <p>Titolo</p>
                                    <p>Autore</p>
                                    <p>ISBN</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </body>
</html>
