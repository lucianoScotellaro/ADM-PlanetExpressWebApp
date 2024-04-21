@props(['user','book'])

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
                <x-button href="/loan/{{$user->id}}/{{$book->ISBN}}" class="mb-10">Richiedi Prestito</x-button>
                <x-button href="/trade/{{$user->id}}/{{$book->ISBN}}">Proponi Scambio</x-button>
            </div>
        </div>
    </div>
</li>
