<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        #blocForm {
            position: absolute;
            width: 500px;
            height: 506px;
            left: 692px;
            top: 287px;

            background: rgba(255, 255, 255, 0.68);
            box-shadow: 0px 0px 40px -11px rgba(0, 0, 0, 0.25);
            border-radius: 35px;
        }

        form div+div {
            margin-top: 1em;
        }

        label {
            position: absolute;
            width: 244px;
            height: 28px;
            left: 25px;
            font-family: 'Arial';
            font-style: normal;
            font-weight: 400;
            font-size: 32px;
            line-height: 38px;
            color: #434343;
        }

        #loginLabel {
            top: 100px;
        }

        #passwordLabel {
            top: 240px;
        }

        input,
        textarea {
            box-sizing: border-box;
            position: absolute;
            width: 460px;
            height: 48px;
            left: 20px;
            background: #E1E1E1;
            border: 2px solid #B3B3B3;
            box-shadow: inset 0px 4px 4px rgb(0 0 0 / 25%);
            border-radius: 14px;
            text-indent: 10px;

        }

        #login {
            top: 140px;
        }

        #password {
            top: 280px;
        }

        .button {
            /* Pour placer le bouton à la même position que les champs texte */
            padding-left: 90px;
            /* même taille que les étiquettes */
        }

        button {
            position: absolute;
            width: 120px;
            height: 53px;
            left: 330px;
            top: 410px;
            background: #72BDA3;
            border-radius: 14px;
            font-weight: 550;
            font-size: 20px;
            line-height: 21px;
            color: white;
            border-width: 0px;
            letter-spacing: 1px;
        }

    </style>
</head>

<body>
    <div id="blocForm">
        <form action="{{ route('check', $type) }}" method="POST">
            @if (Session::get('fail'))
                <div>{{ Session::get('fail') }}</div>
            @endif

            @csrf
            <span>{{ $type }}'s login</span>
            <div>
                <label id="loginLabel">Login</label>
                <input type="text" id="login" name="user_login" placeholder="insert login"
                    value="{{ old('user_login') }}">
            </div>
            <div>
                <label id="passwordLabel">Password</label>
                <input type="password" id="password" name="user_password" placeholder="insert password">
            </div>
            <div class="button">
                <button type="submit">Log in</button>
            </div>
        </form>
    </div>
</body>

</html>
