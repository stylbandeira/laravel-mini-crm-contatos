<!DOCTYPE html>
<html>

<head>
    <title>Monitor de Processamentos</title>
    @vite(['resources/js/app.js'])
</head>

<body>
    <h1>Monitor de Processamentos</h1>

    <ul id="events"></ul>

    <script type="module">
        window.Echo
            .channel('contacts')
            .listen('.contact.score.processed', (event) => {
                console.log('Evento recebido:', event);

                const li = document.createElement('li');

                li.innerHTML = `
                    <strong>ID:</strong> ${event.id} |
                    <strong>Email:</strong> ${event.email} |
                    <strong>Score:</strong> ${event.score} |
                    <strong>Status:</strong> ${event.status} |
                    <strong>Processado em:</strong> ${event.processed_at}
                `;

                document.getElementById('events').prepend(li);
            });
    </script>
</body>

</html>
