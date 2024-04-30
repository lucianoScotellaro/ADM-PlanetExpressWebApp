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
            <div class="basis-3/4 overflow:auto">
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($books as $book)
                        <li class="justify-between gap-x-6 py-5 h-full">
                            <div class="flex flex-row min-w-0 gap-x-4">
                                <div class="basis-3/4">
                                    <div class="flex flex-row">
                                        <x-book-image></x-book-image>
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
                                    <div class="flex flex-col w-2/3">
                                        <form method="POST" action="/trades/propose/{{$book->ISBN}}">
                                            @csrf
                                            <x-form-button>Proponi</x-form-button>
                                        </form>
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
