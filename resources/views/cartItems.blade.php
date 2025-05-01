
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<div id="mainContent">
    <div id="header">
        <h1>Корзина <i class="fas fa-shopping-cart"></i></h1>
        <h3>Shopping Basket</h3>
    </div>
    <div id="basket">
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Cost</th>
                <th></th>
            </tr>
            @if ($products->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center;">ПУСТО</td>
                </tr>
            @else
                @foreach($cartItems as $elem)
                    @php
                        $item = $products->firstWhere('id', $elem->product_id);
                    @endphp

                    <tr>
                        <td> Product {{ $item->product_name }} </td>
                        <td>&#36;<input name="price" class="price" value="{{ $item->price }}" readonly /></td>
                        <td>
                            <i class="fas fa-minus" title="Decrease Qty"></i>
                            <input class="qty" value="{{ $elem->amount }}" name="qty" maxlength="2" />
                            <i class="fas fa-plus" title="Increase Qty"></i>
                        </td>
                        <td>&#36;<input name="cost" class="cost" value="{{ $item->price * $elem->amount }}" readonly /></td>
                        <td>
                            <form action="{{ route('deleteProduct') }}" method="POST">
                                @csrf
                                <button type="submit" class="fas fa-trash-alt" title="Delete Item"></button>
                                <input name="product_id" type="hidden" value="{{ $elem->product_id }}" />
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>
        <div id="totalAmount">
            <div class="left">
                <h1>Total Cost</h1>
                <p>&#36;<input name="total" id="total" value="{{ print_r('$totalcost') }}" readonly /></p>
                <div class="buttons">
                    <br><br><br><br><br><br>
                    <a href="{{ route('catalog') }}" style="color:purple" class="register-link">Go to Catalog</a>
                </div>
                <div class="buttons">
                    <br><br>
                    <a href="{{ route('orders') }}" style="color:purple" class="register-link">Go to Orders</a>
                </div>
            </div>
        </div>
        <form action="{{ route('deleteCart') }}" method="POST">
            @csrf
            <div id="button">
                <h1>DEL</h1><button type="submit" class="fas fa-shopping-cart"></button>
            </div>
        </form>
    </div>
    <div class="hide">
        <p>An interactive shopping basket </p>
    </div>
</div>

<style>
    body {
        background: url("https://kartinki.pics/uploads/posts/2021-07/1625668386_42-kartinkin-com-p-oboi-po-naruto-krasivie-46.jpg") no-repeat center center;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    /*reset browser*/
    html, body, div, span, h1, h2, h3, h4, h5, h6, p, img, i, dt, dd, ol, ul, li, form, table, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, footer, header, hgroup, menu, nav {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }
    article, aside, details, figcaption, figure,
    footer, header, hgroup, menu, nav, section {
        display: block;
    }
    body {
        line-height: 1;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    a {
        text-decoration: none;
    }
    /*end*/

    body {
        font: 100% Georgia, serif;
        color: #25343F;
        margin-top:25px;
    }

    h1 {
        font-size: 32px;
        font-weight:bold;
    }

    input {
        font-size: 15px;
        color: #25343F;
        border: 0px;
        margin: 0px;
        width: 40px;
        background-color: rgb(211,211,211);
    }

    select {
        width: 50px;
        /*background-color: rgb(211,211,211);*/
        border: 0px;
    }

    select.size {
        background-color: rgb(211,211,211);
    }

    #mainContent {
        width: 800px;
        margin: auto;
        padding: 10px 20px 30px;
        background-color: rgba(211,211,211,0.8);
    }

    #header {
        width: 100%;
        height: 140px;
        text-align: right;
    }

    #header h1 {
        margin-bottom: 10px;
    }

    #header  i {
        color: rgb(181,0,0);
        font-size: 55px;
        text-shadow: 2px 2px 8px;
    }

    #basket {
        margin: auto;
    }

    #basket table {
        font-size: 18px;
        width: 100%;
        text-align: left;
    }

    #basket table td, th {
        border-right: 2px solid #25343F;
        padding: 5px;
        text-align: center;
    }

    #basket table th {
        font-size: 24px;
        padding-bottom: 10px;
        border: 0px;
    }

    #basket table td.button{
        border:none;
    }

    #basket i {
        cursor: pointer;
    }

    #totalAmount {
        height: 80px;
        margin-top: 25px;
        text-align: right;
    }

    #button {
        text-align: right;
        font-size: 65px;
        height: 110px;
        margin-right: 25px;
    }

    #button a {
        color: #25343F;
    }

    input.cost {
        width: 70px;
    }

    .qty {
        text-align: center;
        width: 25px;
    }

    .left {
        float:left;
        width: 450px;
    }

    .left p {
        font-size: 14px;
    }

    .right {
        font-size: 36px;
        margin-right: 20px;
    }

    .right input {
        font-size: 36px;
        width: 130px;
    }

    .hide {
        text-align: center;
        display: none;
    }

    #button a:hover i {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
        -webkit-transition: all 1s;
        -moz-transition: all 1s;
        -ms-transition: all 1s;
        -o-transition: all 1s;
        transition: all 2s;
        color: rgb(181,0,0);
        text-shadow: 3px 3px 8px;
    }

</style>
