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
        <div class="flex flex-row">
            <div class="basis-1/4"></div>
            <div class="basis-3/4">
                @if(session()->has('success'))
                    <div class="flex justify-center my-3">
                        <div class="rounded-md w-1/9 text-center text-white bg-green-600 bg-opacity-80 shadow-inner">
                            <p class="my-1">{{session()->get('success')}}</p>
                        </div>
                    </div>
                @endif
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($requests as $request)
                        <li class="justify-between gap-x-6 py-5 h-full">
                            <div class="flex basis-3/4">
                                <div class="basis-3/7 inline-flex">
                                    <x-book-image class="w-48"></x-book-image>
                                    <div class="max-w-sm">
                                        <div class="mb-3">
                                            <p class="text-bold uppercase text-2xl">Cedi</p>
                                            <x-book-title>{{$request->requestedBook->title}}</x-book-title>
                                            <x-book-author>{{$request->requestedBook->author}}</x-book-author>
                                        </div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="flex flex-auto justify-center items-center">
                                    <img src="{{asset('img/swap.png')}}" class="w-12 h-12" alt="">
                                </div>
                                <div class="basis-3/7 inline-flex">
                                    <x-book-image class="w-48"></x-book-image>
                                    <div class="max-w-sm">
                                        <div class="mb-3">
                                            <p class="text-bold uppercase text-2xl">Ricevi</p>
                                            <x-book-title class="text-semibold">{{$request->proposedBook->title}}</x-book-title>
                                            <x-book-author>{{$request->proposedBook->author}}</x-book-author>
                                        </div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="flex flex-auto justify-end items-center">
                                    <div class="flex flex-col pr-5">
                                        <x-button href="/trades/requests/accept/{{$request->sender->id}}/{{$request->requestedBook->id}}/{{$request->proposedBook->id}}" class="mb-3">Accetta</x-button>
                                        <x-button href="/trades/requests/refuse/{{$request->sender->id}}/{{$request->requestedBook->id}}/{{$request->proposedBook->id}}">Rifiuta</x-button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </body>
</html>
