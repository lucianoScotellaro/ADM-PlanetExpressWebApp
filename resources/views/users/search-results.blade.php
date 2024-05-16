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
        @if(!empty($books))
            @foreach($books as $book)
                <div class="flex flex-row justify-between m-5 border-solid border-4 border-gray-200 items-center rounded-md">
                    <div class="basis-1/2 inline-flex m-2">
                        <div>
                            <x-book-image src="{{$book->thumbnailUrl != null ? $book->thumbnailUrl : asset('img/book-image-not-found.png')}}" class="w-2/5"></x-book-image>
                        </div>
                        <div>
                            <x-book-title>{{ $book->title }}</x-book-title>
                            <x-book-author>{{ $book->author }}</x-book-author>
                            <p>Pubblicazione: {{ $book->publishedDate }}</p>
                        </div>
                    </div>
                    <div class="flex basis-1/2 justify-end items-center">
                        <x-button class="m-2" href="{{'/users/search/proposers/'.$book->id}}">Visualizza gli utenti che lo cedono</x-button>
                    </div>
                </div>
            @endforeach
        @endif
    </body>
</html>
