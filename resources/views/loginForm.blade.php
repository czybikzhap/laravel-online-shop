
<body>
<div id="container">
    <header>Login</header>
    <form method="post">
        <fieldset>
            @csrf
            <input type="text" name="email" id="email" placeholder="E-mail" >
            @error('email')
            <label for="email"> <b>{{ $message }} </b> </label>
            @enderror
            <br/>
            <label style="color:red">
            </label>
            <br/>
            <input type="password" name="password" id="password" placeholder="Password" >
            @error('password')
            <label for="password"> <b>{{ $message }} </b> </label>
            @enderror
            <br/>
            <label style="color:red">
            </label>
            <br/>
            <label for="submit"></label>
            <input type="submit" name="submit" id="submit" value="LOGIN">
            <br/>
            <div class="buttons">
                <a href="{{ route('registration') }}" class="register-link">Register</a>
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
        background: url("https://pibig.info/uploads/posts/2022-11/thumbs/1669754624_26-pibig-info-p-itachi-oboi-vkontakte-28.jpg") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Droid Serif', serif;
    }

    #container {
        background: rgba(91, 2, 2, 0.5);
        width: 18.75rem;
        height: 15rem;
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
        background: rgba(129, 3, 3, 1);
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
