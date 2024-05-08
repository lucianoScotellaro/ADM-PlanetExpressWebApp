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
        <div class="flex justify-between">
            <div>
                <x-page-header>Richieste di prestito ricevute</x-page-header>
            </div>
            <div>
                <x-button class="rounded-md mr-3 mt-4" href="/users/{{$user->id}}/books">I miei libri</x-button>
                <x-button class="rounded-md mr-3 mt-4" href="/users/{{$user->id}}/books/onloan">I miei libri in prestito</x-button>
                <x-button class="rounded-md mr-3 mt-4" href="/users/{{$user->id}}/books/ontrade">I miei libri in scambio</x-button>
                <x-button class="rounded-md mr-3 mt-4" href="/users/user/books/create">Aggiungi libro</x-button>
            </div>
        </div>
        @if(session()->has('success'))
        <div class="flex justify-center my-3">
            <div class="rounded-md w-1/12 text-center text-white bg-green-600 bg-opacity-80 shadow-inner">
                <p class="my-1">{{session()->get('success')}}</p>
            </div>
        </div>
        @endif
        <div class="flex flex-row">
            <div class="basis-1/4">
            </div>
            <div class="basis-3/4">
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($requests as $request)
                        <li class="justify-between gap-x-6 py-5 h-full">
                            <div class="flex flex-row min-w-0 gap-x-4">
                                <div class="basis-3/4">
                                    <div class="flex flex-row">
                                        <x-book-image class="w-1/6"></x-book-image>
                                        <div class="basis-5/6 flex flex-col">
                                            <div class="mb-3">
                                                <x-book-title>{{$request->requestedBook->title}}</x-book-title>
                                                <x-book-author>{{$request->requestedBook->author}}</x-book-author>
                                            </div>
                                            <div class="mb-3">
                                                <p class="text-sm text-semibold">Richiesto da: <strong>{{$request->sender->name}}</strong></p>
                                            </div>
                                            <div>
                                                <x-book-isbn>{{$request->requestedBook->ISBN}}</x-book-isbn>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex basis-1/6 justify-items-center items-center">
                                    <div class="flex flex-col  w-full">
                                        <x-button class="m-1" href="/loans/requests/accept/{{$request->sender->id}}/{{$request->requestedBook->ISBN}}">Cedi in prestito</x-button>
                                        <x-button class="m-1" href="/loans/requests/refuse/{{$request->sender->id}}/{{$request->requestedBook->ISBN}}">Respingi richiesta</x-button>
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
