<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 w-screen h-screen">
    <div class="flex items-center justify-center h-screen">
        <form action="index.php?controle=utilisateur&action=ident" method="post"
            class="w-auto sm:w-1/2 xl:w-1/4 bg-white shadow-md rounded px-8 pt-6 pb-8">
            <h2 class="font-bold text-center mb-12 mt-6 text-base text-xl">Se Connecter</h3>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Nom d'utilisateur
                    </label>
                    <input name="nom-utilisateur" type="text" value="<?php echo $nom;?>"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="username" placeholder="nom d'utilisateur">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Mot de passe
                    </label>
                    <input name="mdp" value="<?php echo $num; ?>"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="password" type="password" placeholder="**********">
                </div>
                <div class="flex items-center justify-center">
                    <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                        Se connecter
                    </button>
                </div>
        </form>
    </div>

</body>

</html>