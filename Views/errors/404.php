<style>
    body {
        background-color: #b3d1d8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
    }

    .container {
        text-align: center;
    }

    .rocket {
        font-size: 5rem;
        margin-bottom: 20px;
        transition: transform 3s ease-in-out;
    }

    .rocket.fly {
        transform: translateY(-100vh) rotate(30deg);
    }

    .btn-primary {
        margin-top: 20px;
        background-color: #7ec5d4;
        border: none;
        padding: 10px 20px;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color:rgba(126, 198, 212, 0.62);
    }
</style>

<div class="container text-dark">
    <div class="rocket">🚀</div>
    <h1 class="display-1">404</h1>
    <p class="lead">Oops! Página não encontrada.</p>
    <p>Parece que você se perdeu no espaço. Vamos te levar de volta!</p>
    <button id="launchRocket" class="btn btn-primary">Decolar Foguete</button>
</div>

<script>
    document.getElementById('launchRocket').addEventListener('click', function() {
        const rocket = document.querySelector('.rocket');
        rocket.classList.add('fly'); 
        setTimeout(() => {
            window.location.href = '/'; 
        }, 2000);
    });
</script>