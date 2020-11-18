<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php wp_title(); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <?php wp_head(); ?>
    <style>
        body {
            font-family: 'Playfair Display', serif;
            font-weight: 400;
            line-height: 1.45;
            color: #333;
        }

        * {
            box-sizing: border-box;
        }

        .wrapper {
            width: 95%;
            margin: 0 auto;
            max-width: 1020px;
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .sidebar {
            padding-right: 1em;
        }

        .sidebar img {
            width: 200px;
            height: 200px;
            vertical-align: middle;
            border-radius: 50%;
        }

        main {
            padding: 10vh 0;
        }

        footer {
            text-align: center;
        }

        article {
            margin: 0 auto;
            max-width: 680px;
        }

        html {
            font-size: 1em;
        }

        p {
            margin-bottom: 1.3em;
            font-size: 1.414em;
        }

        h1, h2, h3, h4 {
            margin: 1.414em 0 0.5em;
            font-weight: inherit;
            line-height: 1.2;
        }

        h1 {
            margin-top: 0;
            font-size: 3.998em;
        }

        h2 {
            font-size: 2.827em;
        }

        h3 {
            font-size: 1.999em
        }

        a,
        a:hover,
        a:focus {
            color: #0D3CFB;
        }

        header p {
            font-size: 1.66em;
        }
    </style>
</head>
<body>
