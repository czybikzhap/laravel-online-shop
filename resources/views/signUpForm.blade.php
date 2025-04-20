
<body>
<div id="container">
    <header>Register new account</header>
    <form action="/registration" method="post">
        @csrf
        <fieldset>
            <br/>
            <input type="text" name="name" id="name" placeholder="Username">
                @error('name')
                    <label for="name"> <b>{{ $message }} </b> </label>
                @enderror
            <br/>
            <label style="color:red"></label>
            <br/>
            <input type="text" name="email" id="email" placeholder="E-mail">
                @error('email')
                    <label for="email"> <b>{{ $message }} </b> </label>
                @enderror
            <br/>
            <label style="color:red"></label>
            <br/>
            <input type="password" name="password" id="password" placeholder="Password">
                @error('password')
                    <label for="password"> <b>{{ $message }} </b> </label>
                @enderror
            <br/>
            <label style="color:red"></label>
            <br/>
            <input type="password" name="password_confirmation" id="confirm-password" placeholder="Confirm Password">
                @error('password_confirmation')
                <label for="confirm-password"> <b>{{ $message }} </b> </label>
                @enderror
            <br/>
            <br/>
            <br/>
            <label for="submit"></label>
            <input type="submit" name="submit" id="submit" value="REGISTER">
            <br/>
            <div class="buttons">
                <a href="login" class="register-link">Login</a>
            </div>
        </fieldset>
        <?php print_r($errors) ?>
    </form>
</div>
</body>

<style>
    html {
        height: 100%;
        width: 100%;
    }

    body {
        background: url("https://pw.artfile.me/wallpaper/30-10-2020/650x366/anime-naruto-uchiha-madara-mech-boj-1534114.jpg") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Droid Serif', serif;
    }

    #container {
        background: rgba(3, 3, 55, 0.5);
        width: 18.75rem;
        height: 25rem;
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
        background: rgba(3, 3, 55, 0.7);
        font-size: 1.4rem;
        color: #d3d3d3;
    }

    fieldset {
        border: 0;
        text-align: center;
    }

    input[type="submit"] {
        background: rgba(235, 30, 54, 1);
        border: 0;
        display: block;
        width: 70%;
        margin: 0 auto;
        color: white;
        padding: 0.7rem;
        cursor: pointer;
    }

    input[type="buttons"] {
        background: rgba(235, 30, 54, 1);
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
