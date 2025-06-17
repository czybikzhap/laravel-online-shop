

    <div class="container text-center mt-5">
        <h1 class="{{ $status === 'succeeded' ? 'text-success' : 'text-danger' }}">
            {{ $message }}
        </h1>

        <p class="mt-3">Статус платежа: <strong>{{ $status }}</strong></p>

        <a href="{{ route('catalog') }}" class="btn btn-primary mt-4">Вернуться в каталог</a>
    </div>

