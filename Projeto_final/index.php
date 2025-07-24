<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Huninn&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #AB744B;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .imagem {
            position: fixed;
            width: 101%;
            height: 101%;
            right: -10px;
        }

        #img1 {
            bottom: 2%;
            animation: sla 2.5s linear infinite;
            width: 110%;
        }

        #img2 {
            width: 77%;
            height: 70%;
            right: 13%;
            top: 50%;
            animation: rodar 20s linear infinite;
        }

        #img3 {
            animation: sla 5s linear infinite;
            height: 117%;
            bottom: -10%;
        }

        #img4 {
            height: 145%;
            width: 127%;
            right: -13%;
            bottom: -22%;
            animation: opacidade 5s linear infinite;
        }

        @keyframes opacidade {
            0% {
                opacity: 1;
                transform: scale(1, 1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.978, 0.978);
            }

            100% {
                opacity: 1;
                transform: scale(1, 1);
            }
        }

        #img5 {
            width: 83%;
            height: 60%;
            right: 8%;
            top: -22%;
            animation: rodar 20s linear infinite;
        }

        @keyframes rodar {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        #img6 {
            bottom: 0%;
            height: 115%;
        }

        #img7 {
            width: 110%;
            bottom: 4%;
            animation: sla 2s linear infinite;
        }

        #img8 {
            left: 4%;
            width: 90%;
            height: 117%;
            bottom: -9%;
        }

        @keyframes sla {
            0% {
                transform: scale(1, 1);
                transform: scaleX(1.05);
            }

            50% {
                transform: scale(0.988, 0.988);
            }

            100% {
                transform: scale(1, 1);
                transform: scaleX(1.05);
            }
        }

        button {
            color: rgb(90, 51, 25);
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 9999;
            pointer-events: auto;
            height: 100%;
            width: 100%;
            display: flex;
            align-items: flex-end;
            background-color: transparent;
            border: none;
        }

        h2 {
            text-align: center;
            font-size: 40px;
            font-family: "Huninn", sans-serif;
            font-weight: 500;
            font-style: normal;
            text-transform: uppercase;
            left: -10%;
        }

        .btn-inicio {
            background-color: #ab754b;
            letter-spacing: 5px;
            border-color: #663b25;
            border-bottom: 13px dotted;
            border-top: 13px dotted;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        button:active {
            background-color: white;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela inicial</title>
</head>

<body>
    <img class="imagem" id="img1" src="../img/1.png" alt="1">
    <img class="imagem" id="img2" src="../img/2.png" alt="2">
    <img class="imagem" id="img3" src="../img/3.png" alt="3">
    <img class="imagem" id="img4" src="../img/4.png" alt="4">
    <img class="imagem" id="img5" src="../img/5.png" alt="5">
    <img class="imagem" id="img6" src="../img/6.png" alt="6">
    <img class="imagem" id="img7" src="../img/7.png" alt="7">
    <img class="imagem" id="img8" src="../img/8.png" alt="8">
    <form action="php/abrir_comanda.php" method='post'>
        <button type="submit">
            <div class="btn-inicio">
                <h2>Toque na tela para iniciar</h2>
            </div>
        </button>
    </form>
</body>

</html>