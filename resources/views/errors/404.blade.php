<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 | Page Not Found</title>
</head>
<style>
    @font-face {
        font-family: "Reklame Script";
        font-weight: normal;
        font-style: normal;
        src: url("/fonts/reklamescript-regular_demo-webfont.woff") format("woff");
    }

    * {
        margin: 0;
        padding: 0;
    }

    body {
        min-height: 100vh;
        min-width: 100vw;
        overflow: hidden;
    }

    #backgroundphoto {
        position: relative;
        top: 0;
        left: 0;
        min-height: 100vh;
        min-width: 100vw;

        background-image: url('/img/404_OOPS.jpg');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center center;
    }

    #blurred {
        position: absolute;
        top: -5px;
        left: -10px;
        min-height: 102vh;
        min-width: 102vw;

        background-image: url('/img/404_OOPS.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;

        filter: blur(8px);
    }

    h1 {
        position: absolute;
        bottom: 30%;
        width: 100%;
        text-align: center;

        color: white;
        font-size: 72px;
        font-family: 'Reklame Script', serif;
    }

    @media screen and (max-width: 500px ) {
        h1 {
            font-size: 42px;
        }
    }
</style>
<body>
<div id="blurred"></div>
<div id="backgroundphoto">
    <h1>
        404 Page Not Found
    </h1>
</div>
</body>
</html>