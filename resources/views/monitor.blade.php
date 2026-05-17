<!DOCTYPE html>
<html>

<head>
    <title>Contact Monitor</title>

    @vite(['resources/js/app.js'])
</head>

<body>

    <h1>Realtime Contact Monitor</h1>

    <div id="contact-status">
        Waiting updates...
    </div>

    <script type="module">
        const contactId = 1;

        window.Echo
            .channel(`contacts.${contactId}`)
            .listen('.contact.score.processed', (event) => {

                console.log(event);

                document.getElementById('contact-status')
                    .innerHTML = `
                        <p><strong>ID:</strong> ${event.id}</p>
                        <p><strong>Email:</strong> ${event.email}</p>
                        <p><strong>Score:</strong> ${event.score}</p>
                        <p><strong>Status:</strong> ${event.status}</p>
                    `;
            });
    </script>

</body>

</html>
