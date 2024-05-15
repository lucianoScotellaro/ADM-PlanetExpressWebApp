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
            @if(session()->has('noParametersError'))
                <p>{{session()->get('noParametersError')}}</p>
            @endif
            @if(!empty($books))
                @foreach($books as $book)
                    <div class="flex flex-row justify-between m-5 border-solid border-4 border-gray-200 items-center rounded-md">
                        <div class="basis-1/2 inline-flex m-2">
                            <div>
                                <x-book-image src="{{$book['thumbnailUrl'] != null ? $book['thumbnailUrl'] : asset('img/book-image-not-found.png')}}" class="w-2/5"></x-book-image>
                            </div>
                            <div>
                                <x-book-title>{{ $book['title'] }}</x-book-title>
                                <x-book-author>{{ $book['authors'] != null ? $book['authors'][0] : 'Not Available' }}</x-book-author>
                                <p>Pubblicazione: {{ $book['publishedDate'] }}</p>
                            </div>
                        </div>
                        <div class="flex basis-1/2 justify-end mb-3">
                            <form method="POST" action="/users/{{ $user->id }}/books/{{ $book['id'] }}/onloan" id="add-book-{{ $book['id'] }}-on-loan" class="hidden">
                                @csrf
                            </form>
                            <x-form-button class="m-2" form="add-book-{{ $book['id'] }}-on-loan">Aggiungi libro nei prestiti</x-form-button>
                            <form method="POST" action="/users/{{ $user->id }}/books/{{ $book['id'] }}/ontrade" id="add-book-{{ $book['id'] }}-on-trade" class="hidden">
                                @csrf
                            </form>
                            <x-form-button class="m-2" form="add-book-{{ $book['id'] }}-on-trade">Aggiungi libro in scambio</x-form-button>
                        </div>
                    </div>
                @endforeach
                <a href="{{ preg_replace('/&pageNumber=[0-9]*/','&pageNumber='.($currentPageNumber + 1),request()->getRequestUri())}}">Next</a>
                @if($currentPageNumber > 1)
                    <a href="{{ preg_replace('/&pageNumber=[0-9]*/','&pageNumber='.($currentPageNumber - 1),request()->getRequestUri())}}">Previous</a>
                @endif
            @else
                <p>La ricerca non ha prodotto risultati</p>
                @if($currentPageNumber > 1)
                    <a href="{{ preg_replace('/&pageNumber=[0-9]*/','&pageNumber='.($currentPageNumber - 1),request()->getRequestUri())}}">Previous</a>
                @endif
            @endif
    </body>
</html>


