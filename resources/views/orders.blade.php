<body>
<div id="container">
    <header>Orders</header>
    <form method="post">
        <fieldset>
            @csrf
            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
            <label for="userName" style="color:red"><b>{{ $user->name }}</b></label>
            <br/>
            <br/>
            <input type="text" name="address" id="address" placeholder="Address" >
            @error('address')
            <label for="address"> <b>{{ $message }} </b> </label>
            @enderror
            <br/>

            </label>
            <br/>
            <input type="text" name="phone" id="phone" placeholder="Contact phone" >
            @error('phone')
            <label for="phone"> <b>{{ $message }} </b> </label>
            @enderror
            <br/>
            <label style="color:red">
            </label>
            <br/>
            <h2 style="font-size: 20px; font-weight: bold;">
                <span style="margin-right: 10px;">Total Cost:</span>
                <span>&#36;{{ $totalCost }}</span>
            </h2>
            <label for="submit"></label>
            <input type="submit" name="submit" id="submit" value="Order">
            <br/>
            <div class="buttons">
                <br><br>
                <a href="{{ route('catalog') }}" style="color:darkred" class="register-link">Go to Catalog</a>
            </div>
        </fieldset>
    </form>
</div>
</body>

<style>
    html {
        height: 100%;
        width: 100%;
    }

    body {
        background: url("https://zefirka.club/wallpapers/uploads/posts/2023-02/1677033767_zefirka-club-p-minato-protiv-devyatikhvostogo-6.jpg") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Droid Serif', serif;
    }

    #container {
        background: rgba(12, 5, 40, 0.5);
        width: 18.75rem;
        height: 20rem;
        margin: auto;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    header {
        text-align: center;
        vertical-align: middle;
        line-height: 3rem;
        height: 3rem;
        background: rgba(12, 2, 28, 0.7);
        font-size: 1.4rem;
        color: #d3d3d3;
    }

    fieldset {
        border: 0;
        text-align: center;
    }

    input[type="submit"] {
        background: rgb(2, 11, 191);
        border: 0;
        display: block;
        width: 70%;
        margin: 0 auto;
        color: white;
        padding: 0.7rem;
        cursor: pointer;
    }

    input {
        background: transparent;
        border: 0;
        border-left: 4px solid;
        border-color: #FF0000;
        padding: 10px;
        color: white;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        outline: 0;
        background: rgba(235, 30, 54, 0.3);
        border-radius: 1.2rem;
        border-color: transparent;
    }

    ::placeholder {
        color: #d3d3d3;
    }

    /*Media queries */

    @media all and (min-width: 481px) and (max-width: 568px) {
        #container {
            margin-top: 10%;
            margin-bottom: 10%;
        }
    }
    @media all and (min-width: 569px) and (max-width: 768px) {
        #container {
            margin-top: 5%;
            margin-bottom: 5%;
        }
    }
</style>

