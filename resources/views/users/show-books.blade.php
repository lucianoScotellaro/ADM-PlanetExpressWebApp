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
                <x-page-header>
                    @if(request()->is('users/*/books/ontrade'))
                        Libri in scambio
                    @elseif(request()->is('users/*/books/onloan'))
                        Libri in prestito
                    @elseif(request()->is('users/*/books*'))
                        I tuoi libri
                    @endif
                </x-page-header>
            </div>
            <div>
                <x-button class="rounded-md mr-3 mt-4" href="/users/{{$user->id}}/books">I miei libri</x-button>
                <x-button class="rounded-md mr-3 mt-4" href="/users/{{$user->id}}/books/onloan">I miei libri in prestito</x-button>
                <x-button class="rounded-md mr-3 mt-4" href="/users/{{$user->id}}/books/ontrade">I miei libri in scambio</x-button>
                <x-button class="rounded-md mr-3 mt-4" href="/users/user/books/create">Aggiungi libro</x-button>
            </div>
        </div>
        <div class="flex flex-row">
            <div class="basis-1/4">
            </div>
            <div class="basis-3/4">
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($books as $book)
                        <li class="justify-between gap-x-6 py-5 h-full">
                            <div class="flex flex-row min-w-0 gap-x-4">
                                <div class="basis-3/4">
                                    <div class="flex flex-row">
                                        <x-book-image class="w-1/6"></x-book-image>
                                        <div class="basis-5/6 flex flex-col">
                                            <div class="mb-3">
                                                <x-book-title>{{$book->title}}</x-book-title>
                                                <x-book-author>{{$book->author}}</x-book-author>
                                            </div>
                                            <div>
                                                <x-book-isbn>{{$book->ISBN}}</x-book-isbn>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex basis-1/6 justify-items-center items-center">
                                    <div class="flex flex-col  w-full">
                                        <form action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/onloan" method="POST" id="delete-book-{{ $book->ISBN }}-on-loan" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <x-form-button class="m-1" form="delete-book-{{ $book->ISBN }}-on-loan">Elimina dai prestabili</x-form-button>
                                        <form action="/users/{{ $user->id }}/books/{{ $book->ISBN }}/ontrade" method="POST" id="delete-book-{{ $book->ISBN }}-on-trade" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <x-form-button class="m-1" form="delete-book-{{ $book->ISBN }}-on-trade">Elimina dagli scambiabili</x-form-button>
                                        <x-button class="m-1" href="/loan/{{$user->id}}/{{$book->ISBN}}">Richiedi Prestito</x-button>
                                        <x-button class="m-1" href="/trades/ask/{{$user->id}}/{{$book->ISBN}}">Proponi Scambio</x-button>
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
