<!DOCTYPE html>
<html>
<head>
    <title>Contrat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #007BFF; /* Couleur bleue */
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .details {
            margin-top: 20px;
        }
        .details p {
            margin: 10px 0;
            font-size: 16px;
        }
        .details strong {
            color: #555;
        }
        .details .label {
            font-weight: bold;
            color: #007BFF;
        }
        .details .value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Contrat</h1>
    <div class="details">
        <p><span class="label">Client:</span> <span class="value">{{ $opportunity->client }}</span></p>
        <p><span class="label">Commissions:</span> <span class="value">{{ $opportunity->commissions }}</span></p>
        <p><span class="label">Description:</span> <span class="value">{{ $opportunity->description }}</span></p>
        <p><span class="label">Date:</span> <span class="value">{{ $opportunity->date }}</span></p>
    </div>
    <div class="footer">
        <p>Merci d'avoir choisi notre service.</p>
    </div>
</div>
</body>
</html>
