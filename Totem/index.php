<!DOCTYPE html>
<html>

<head>
    <link rel="icon" type="image/png" href="../img/Comes-_1_.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Huninn&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <!-- <style>
        @media screen and (max-width: 450px) {
            body {
                margin: 0;
                padding: 0;
                background-color: #ab744b;
                overflow: hidden;
            }

            .imagem {
                position: fixed;
                top: -1%;
                left: -1%;
                width: 101%;
                height: 101%;
                object-fit: cover;
            }

            #img1 {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                height: 100%;
            }

            #img5 {
                top: 60%;
                left: 50%;
                transform: translate(-50%, -50%);
                height: 70%;
                width: auto;
            }

            #img6,
            #img7,
            #img8 {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                height: 70%;
                width: auto;
            }

            #img6 {
                z-index: 1000;
                animation: opacidade 5s linear infinite;
            }

            #img7,
            #img8 {
                z-index: 1000;
            }

            @keyframes opacidade {
                0% {
                    opacity: 1;
                }

                50% {
                    opacity: 0;
                }

                100% {
                    opacity: 1;
                }
            }

            #img9 {
                z-index: 1;
            }

            button {
                color: rgb(255, 255, 255);
                position: fixed;
                top: -10%;
                left: -35%;
                right: 0%;
                z-index: 9999;
                pointer-events: auto;
                width: 200%;
                height: 200%;
                background-color: transparent;
            }

            h2 {
                text-align: center;
                font-size: 17px;
                font-family: "Huninn", sans-serif;
                font-weight: 500;
                font-style: normal;
                text-transform: uppercase;
                left: -10%;
            }

            button:active {
                background-color: white;
            }
        }
    </style> -->
    <title>Tela inicial</title>
</head>

<body class='body-inicio'>
    <img class="imagem" id="img1" src="../img/1.png" alt="1">
    <img class="imagem" id="img2" src="../img/2.png" alt="2">
    <img class="imagem" id="img3" src="../img/3.png" alt="3">
    <img class="imagem" id="img4" src="../img/4.png" alt="4">
    <img class="imagem" id="img5" src="../img/5.png" alt="5">
    <img class="imagem" id="img6" src="../img/6.png" alt="6">
    <img class="imagem" id="img7" src="../img/7.png" alt="7">
    <img class="imagem" id="img8" src="../img/8.png" alt="8">
    <img class="imagem" id="img9" src="../img/9.png" alt="9">

    <form action="php/abrir_comanda.php" method='post'>
        <button type="submit">
            <div>
                <h2>Toque na tela para iniciar</h2>
            </div>
        </button>
    </form>
</body>

</html>