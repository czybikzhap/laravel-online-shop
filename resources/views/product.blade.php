<head>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6ab9ac06da.js" crossorigin="anonymous"></script>
</head>
<body>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button> Log Out</button>
</form>

<div class="container ">
    <div class="card asics">
        <p class="price"> {{ $product->price}}   Rub</p>

        <i class="info fas fa-info-circle"></i>

        <div class="imgBx">
            <img src="{{ print_r('img') }} ">
        </div>
        <div class="contentBx  ">
            <h2> {{ $product->product_name }} </h2>

            <div class="size">
                <h3>{{ $product->description }}</h3>
            </div>

            <form action="{{ route('addToCart') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="amount" class="amount" value="0">

                <div class="quantity-field">
                    <button
                        type="button"
                        class="value-button decrease-button"
                        onclick="decreaseValue(this)"
                        title="Уменьшить">-</button>
                    <div class="number">0</div>
                    <button
                        type="button"
                        class="value-button increase-button"
                        onclick="increaseValue(this)"
                        title="Увеличить">+</button>
                </div>

                <button type="submit">Добавить в корзину</button>
            </form>
        </div>
    </div>

    <div class="reviews-section">
        <h3>Отзывы о товаре</h3>
        <ul>
            @foreach($reviews as $elem)
                <li>
                    <span class="review-username">{{ $users[$elem->user_id] }}</span>
                    <span class="review-date">{{ $elem->updated_at }}</span><br>
                    <span class="review-text">{{ $elem->review }}</span>
                </li>
            @endforeach
        </ul>
        <div class="review-form">
            <h3>Оставьте отзыв</h3>
            <form action="{{ route('addReview') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @error('product_id')
                <label for="product_id"> <b>{{ $message }} </b> </label>
                @enderror

                <textarea name="review" rows="4" placeholder="Ваш отзыв..." required></textarea>
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @error('review')
                <label for="review"> <b>{{ $message }} </b> </label>
                @enderror
                <button type="submit">Отправить отзыв</button>
            </form>
        </div>
    </div>
</div>
<div class="buttons">
    <a href="{{ route('catalog') }}" style="color:blue" class="register-link">Go to Catalog</a>
    <br>
    <a href="{{ route('cartItems') }}" style="color:black" class="register-link">Go to Cart</a>
    <br>
    <a href="{{ route('userProfile') }}" style="color:red" class="register-link">My Profile</a>
</div>

</body>

<script>
    function increaseValue(button) {
        var quantityField = button.parentNode.querySelector('.number');
        var amountField = button.parentNode.parentNode.querySelector('.amount'); // Изменил на parentNode для поиска
        var currentValue = parseInt(quantityField.innerText);
        currentValue++;
        quantityField.innerText = currentValue;
        amountField.value = currentValue; // Обновляем скрытое поле
    }

    function decreaseValue(button) {
        var quantityField = button.parentNode.querySelector('.number');
        var amountField = button.parentNode.parentNode.querySelector('.amount'); // Изменил на parentNode для поиска
        var currentValue = parseInt(quantityField.innerText);
        if (currentValue > 0) {
            currentValue--;
            quantityField.innerText = currentValue;
            amountField.value = currentValue; // Обновляем скрытое поле
        }
    }
</script>


<style>
    * {
        margin: 0;
        padding: 0;
        font-family: Quicksand;
    }
    body {
        background: url("https://gamek.mediacdn.vn/133514250583805952/2020/6/18/photo-2-1592465597011363875480.jpg") no-repeat center center fixed;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .price {
        position: absolute;
        top: 1em;
        right: 1em;
        backdrop-filter: blur(8px);
        background-color: rgba(0, 0, 0, 0.112);
        box-shadow: rgba(0, 0, 0, 0.3) 2px 8px 8px;
        border: 0px rgba(255, 255, 255, 0.4) solid;
        border-bottom: 0px rgba(40, 40, 40, 0.35) solid;
        border-right: 0px rgba(40, 40, 40, 0.35) solid;
        padding: 5px 10px;
        font-size: 0.8em;
        border-radius: 4px;
    }

    .container {
        position: relative;
        padding: 0 20px;
    }
    .container .card {
        position: relative;
        width: 320px;
        height: 450px;
        border-radius: 20px;
        background: url("https://99px.ru/sstorage/53/2013/10/tmb_85843_4797.jpg");
        box-shadow: -20px -20px 25px #0e0e0e, 20px 20px 25px #dddddd;
        overflow: hidden;
    }

    .container .card:after {
        content: "";
        position: absolute;
        top: 30%;
        left: -20%;
        font-size: 12em;
        font-weight: 800;
        font-style: italic;
        color: rgba(255, 255, 255, 0.04);
    }
    .container .card .imgBx {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 200;
        width: 100%;
        height: 220px;
        transition: 0.5s ease-in-out;
    }
    .container .card:hover .imgBx {
        top: 0%;
        transform: translateY(0%);
    }
    .container .card .imgBx img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-25deg);
        width: 270px;
    }
    .container .card .contentBx {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 50px;
        text-align: center;
        transition: 1s;
        z-index: 10;
    }
    .container .card:hover .contentBx {
        height: 210px;
    }
    .container .card .contentBx h2 {
        position: relative;
        font-weight: 600;
        letter-spacing: 1px;
        color: #F89716;
    }
    /* Shoe Size */
    .container .card .contentBx .size,
    .container .card .contentBx .color {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 8px 20px;
        transition: 0.5s;
        opacity: 0;
        visibility: hidden;
    }
    .container .card:hover .contentBx .size {
        opacity: 1;
        visibility: visible;
        translate-delay: 0.5s;
    }
    .container .card:hover .contentBx .color {
        opacity: 1;
        visibility: visible;
        translate-delay: 0.6s;
    }
    .container .card .contentBx .size h3,
    .container .card .contentBx .color h3 {
        color: #fff;
        font-weight: 300;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-right: 10px;
    }
    .container .card .contentBx .size span {
        width: 26px;
        height: 26px;
        text-align: center;
        line-height: 26px;
        font-size: 14px;
        display: inline-block;
        color: #111;
        background: #fff;
        margin: 0 5px;
        transition: 0.5s;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Shoe color */
    .container .card .contentBx .color span {
        width: 20px;
        height: 20px;
        background: #ff0;
        border-radius: 50%;
        margin: 0 5px;
        cursor: pointer;
    }

    /* Button/ */
    .container .card .contentBx button {
        display: inline-block;
        padding: 10px 20px;
        background: #fff;
        border-radius: 4px;
        margin-top: 10px;
        text-decoration: none;
        font-weight: 600;
        color: #111;
        opacity: 0;
        transform: translateY(50px);
        transition: 0.5s;
        border: none;
    }
    .container .card:hover .contentBx button {
        opacity: 1;
        transform: translateY(0px);
        transform-delay: 0.75s;
        cursor: pointer;
    }

    /* Button/ */
    .container .card .contentBx button1 {
        display: inline-block;
        padding: 10px 20px;
        background: #fff;
        border-radius: 4px;
        margin-top: 10px;
        text-decoration: none;
        font-weight: 600;
        color: #111;
        opacity: 0;
        transform: translateY(50px);
        transition: 0.5s;
        border: none;
    }
    .container .card:hover .contentBx button1 {
        opacity: 1;
        transform: translateY(0px);
        transform-delay: 0.75s;
        cursor: pointer;
    }

    .info {
        position: absolute;
        top: 1em;
        left: 1em;
        color: #fff;
        cursor: pointer;
        z-index: 300;
    }

    .text {
        display: none;
        color: #fff;
        padding: 2em;
        z-index: 299;
        position: relative;
        backdrop-filter: blur(20px);
        background-color: rgba(0, 0, 0, 0.2);
        height: 100%;
        text-shadow: 1px 0 0 #000, 0 -1px 0 #000, 0 1px 0 #000, -1px 0 0 #000;
    }
    .info:hover + .text {
        display: block;
    }
    .quantity-field {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 120px;
        height: 40px;
        margin: 0 auto;
    }

    .quantity-field .value-button{
        border: 1px solid #ddd;
        margin: 0px;
        width: 40px;
        height: 100%;
        padding: 0;
        background: #eee;
        outline: none;
        cursor: pointer;
    }

    .quantity-field .value-button:hover {
        background: rgb(230, 230, 230);
    }

    .quantity-field .value-button:active{
        background: rgb(210, 210, 210);
    }

    .quantity-field .decrease-button {
        margin-right: -4px;
        border-radius: 8px 0 0 8px;
    }

    .quantity-field .increase-button {
        margin-left: -4px;
        border-radius: 0 8px 8px 0;
    }

    .quantity-field .number{
        display: inline-block;
        text-align: center;
        border: none;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        margin: 0px;
        width: 40px;
        height: 100%;
        line-height: 40px;
        font-size: 11pt;
        box-sizing: border-box;
        background: white;
        font-family: calibri;
    }

    .quantity-field .number::selection{
        background: none;
    }

    .reviews-section {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-top: 20px; /* Отступ сверху для отделения от других элементов */
    }
    .review-form {
        margin-top: 20px; /* Отступ сверху для отделения формы от списка отзывов */
    }

    .review-username {
        font-family: 'Arial Black', Arial, sans-serif;
        font-size: 0.85rem;
        color: #333;
    }
    .review-date {
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.8rem;
        color: #666;
        margin-left: 8px;
    }
    .review-text {
        font-family: 'Georgia', serif;
        font-size: 1rem;
        color: #000;
        margin-top: 4px;
    }
    /* Optional: styling the list items and layout */
    .reviews-section ul {
        list-style-type: none;
        padding-left: 0;
    }
    .reviews-section li {
        margin-bottom: 1.5em;
    }




</style>
